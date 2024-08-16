<?php
try {
    if(!isset($_SERVER['PATH_INFO'])) {
        throw new \Exception("Invalid request method");
    }
    $method = \strtolower($_SERVER["PATH_INFO"]);
    $class = (function() use($method, $serverMode, $base) {
        $controllers = \scandir("{$base}/hiperesp/server/controllers");

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
                    $rMethod = $request->getEndpoint();

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
} catch(\Exception $e) {
    \http_response_code(500);
    echo $e->getMessage();
    die;
}