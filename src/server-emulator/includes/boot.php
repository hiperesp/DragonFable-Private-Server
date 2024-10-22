<?php declare(strict_types=1);
try {
    if(!isset($_SERVER['PATH_INFO'])) {
        throw new \Exception("Invalid request method");
    }

    $controller = new \ReflectionClass(\hiperesp\server\controllers\Controller::class);
    $method = $controller->getMethod("entry");
    $method->setAccessible(true);
    $method->invoke(null, $_SERVER["PATH_INFO"]);
} catch(\Exception $e) {
    \http_response_code(500);
    echo $e->getMessage();
    echo $e->getTraceAsString();
}
