<?php declare(strict_types=1);

function dynamic(string $pathInfo): string|false {
    include_once 'local.php';
    $out = local($pathInfo);
    if($out!==false) {
        return $out;
    }

    include_once 'remote.php';
    $out = remote($pathInfo);
    if($out===false) {
        return false;
    }

    return $out;
}

if(__FILE__ == \realpath($_SERVER["SCRIPT_FILENAME"])) {
    $pathInfo = \strtolower($_SERVER['PATH_INFO']);

    $out = dynamic($pathInfo);
    if($out===false) {
        \http_response_code(404);
        echo 'File not found';
    } else {
        \header("Content-Type: application/x-shockwave-flash");
        echo $out;
    }
}