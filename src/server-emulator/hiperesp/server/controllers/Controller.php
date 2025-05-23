<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\traits\InjectDependency;

abstract class Controller {

    use InjectDependency;

    final public function __construct() {
        $this->injectDependencies();
    }

    /**
     * Called from boot.php to start the server.
     * It can be final static protected or final static public.
     * But i prefer private, to avoid any other class to call this method except the boot.php.
     */
    final public static function ___bootServer(string $requestUri): void {
        self::cors();

        $selected = null;

        $controllers = (function(): array {
            $filesRecursive = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__));

            // maintaning only php files
            $files = \array_filter(\array_keys(\iterator_to_array($filesRecursive)), fn($fileOrDir) => \is_file($fileOrDir) && \pathinfo($fileOrDir, \PATHINFO_EXTENSION)==='php');
            // removing this current file from the list
            $files = \array_filter($files, fn($file) => $file!==__FILE__);
            // removing the __DIR__ from the file path
            $files = \array_map(fn($file) => \substr($file, \strlen(__DIR__)+1), $files);
            // removing the .php extension
            $files = \array_map(fn($file) => \substr($file, 0, -4), $files);
            // replacing / with \
            $files = \array_map(fn($file) => \str_replace(\DIRECTORY_SEPARATOR, '\\', $file), $files);

            return $files;
        })();
        foreach($controllers as $className) {
            $rClass = new \ReflectionClass("\\hiperesp\\server\\controllers\\{$className}");
            foreach($rClass->getMethods() as $rMethod) {
                foreach($rMethod->getAttributes(Request::class) as $rAttribute) {
                    $requestAttribute = $rAttribute->newInstance();
                    if($requestAttribute->isEndpoint($requestUri)) {
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
            throw new \Exception("No method found for {$requestUri} and no default method was provided.");
        }

        $attribute = $selected->attribute;
        $method = $selected->method;
        $controller = $selected->controller;

        /** @var Request $attribute */
        /** @var \ReflectionMethod $method */
        /** @var \ReflectionClass $controller */

        try {
            /** @var Controller $instance */
            $instance = $controller->newInstance();

            $input = $attribute->getInput();
            $output = $instance->{$method->getName()}($input);
            $attribute->displayOutput($output);
        } catch(DFException $e) {
            $attribute->displayError($e);
        } catch(\Throwable $e) {
            $attribute->displayError($e);
        }
    }

    /**
     * Ref: https://stackoverflow.com/questions/8719276/cross-origin-request-headerscors-with-php-headers
     */
    private static function cors(): void {
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

}