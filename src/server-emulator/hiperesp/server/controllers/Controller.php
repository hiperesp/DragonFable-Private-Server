<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\storage\Storage;

abstract class Controller {

    private readonly Storage $storage;

    public function __construct() {
        $this->cors();
        $this->autoInstanciateModels();
    }

    private function cors() { // https://stackoverflow.com/questions/8719276/cross-origin-request-headerscors-with-php-headers
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            \header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            \header('Access-Control-Allow-Credentials: true');
            \header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                \header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                \header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
    }

    private function autoInstanciateModels(): void {
        $rClass = new \ReflectionClass($this);
        foreach($rClass->getProperties() as $rProperty) {
            $rType = $rProperty->getType();
            if($rType===null) continue;
            $rTypeName = $rType->getName();
            if(!\class_exists($rTypeName)) continue;
            if(\is_subclass_of($rTypeName, \hiperesp\server\models\Model::class)) {
                if(!isset($this->storage)) {
                    $this->storage = Storage::getStorage();
                }
                $rProperty->setValue($this, new $rTypeName($this->storage));
            }
        }
    }

    public final static function entry(string $method): void {
        $selected = null;

        $controllers = \array_filter(\scandir(__DIR__), fn(string $file) => \is_file(__DIR__."/{$file}") && \preg_match('/\.php$/', $file));
        foreach($controllers as $controller) {
            $className = \pathinfo($controller, \PATHINFO_FILENAME);
            $rClass = new \ReflectionClass("\\hiperesp\\server\\controllers\\{$className}");
            foreach($rClass->getMethods() as $rMethod) {
                foreach($rMethod->getAttributes(Request::class) as $rAttribute) {
                    $requestAttribute = $rAttribute->newInstance();
                    if($requestAttribute->isEndpoint($method)) {
                        $selected = new \stdClass();
                        $selected->controller = $rClass;
                        $selected->method = $rMethod;
                        $selected->attribute = $requestAttribute;
                        break 3;
                    }
                    if($requestAttribute->isDefaultEndpoint()) {
                        if($selected) {
                            throw new \Exception("Multiple default endpoints found.");
                        }
                        $selected = new \stdClass();
                        $selected->controller = $rClass;
                        $selected->method = $rMethod;
                        $selected->attribute = $requestAttribute;
                    }
                }
            }
        }

        if($selected===null) {
            throw new \Exception("No method found for {$method} and no default method was provided.");
        }

        try {
            $input = $selected->attribute->getInput();
            $output = $selected->controller->newInstance()->{$selected->method->getName()}($input);
            $selected->attribute->displayOutput($output);
        } catch(DFException $e) {
            $selected->attribute->displayError($e);
        }
    }

}