<?php
try {
    if(!isset($_SERVER['PATH_INFO'])) {
        throw new \Exception("Invalid request method");
    }
    \hiperesp\server\controllers\Controller::entry($_SERVER["PATH_INFO"]);
} catch(\Exception $e) {
    \http_response_code(500);
    echo $e->getMessage();
    echo $e->getTraceAsString();
}
