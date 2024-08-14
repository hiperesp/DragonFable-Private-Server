<?php

include "config.php";

\spl_autoload_register(function ($className) {
    $classFile = __DIR__.DIRECTORY_SEPARATOR.\preg_replace('/\\\\/', DIRECTORY_SEPARATOR, $className).".php";
    if(!\file_exists($classFile)) {
        throw new \Exception("The file {$classFile} does not exists. See {$className}");
    }
    include $classFile;
});

$method = \strtolower($_SERVER["PATH_INFO"]);
$class = (function() use($method, $serverMode) {
    $controllers = \scandir(__DIR__ . '/hiperesp/server/controllers');

    if($serverMode == 'proxy') {
        $controllers = ["Proxy.php"];
    } else if($serverMode == 'server') {
        $key = \array_search("Proxy.php", $controllers);
        if($key !== false) unset($controllers[$key]);
    } else {
        throw new \Exception("Invalid server mode: {$serverMode}");
    }

    $defaultController = null;

    foreach($controllers as $controller) {
        if($controller == '.' || $controller == '..') continue;
        $rClass = new \ReflectionClass('\\hiperesp\\server\\controllers\\'.\preg_replace('/\.php$/', '', $controller));
        if(!$rClass->isSubclassOf(\hiperesp\server\controllers\Controller::class)) {
            continue;
        }
        $rMethods = $rClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach($rMethods as $rMethod) {
            $attributes = $rMethod->getAttributes();
            foreach($attributes as $attribute) {
                /** @var \hiperesp\server\attributes\Request $request */
                $request = $attribute->newInstance();
                $rMethod = $request->getMethod();

                if($rMethod == 'default') {
                    $defaultController = $rClass->getName();
                    continue;
                }
                if($rMethod[0]!=='/') throw new \Exception("Invalid path: {$rMethod} will never match. Must start with /");
                if($rMethod != $method) continue;

                $controller = $rClass->getName();
                return $controller;
            }
        }
    }

    if($defaultController) {
        return $defaultController;
    }

    return null;
})();

if(!$class) throw new \Exception("The method {$method} does not exists");

$classInstance = new $class();
if(!($classInstance instanceof \hiperesp\server\controllers\Controller)) {
    throw new \Exception("The class {$class} is not a instance of default controller");
}

$classInstance->entry($method);
die;
