<?php declare(strict_types=1);

$pathInfo = \strtolower($_SERVER['PATH_INFO']);

function dynamic(string $pathInfo): string|false {
    include 'local.php';
    $out = local($pathInfo);
    if($out!==false) {
        return $out;
    }

    include 'remote.php';
    $out = remote($pathInfo);
    if($out!==false) {
        return false;
    }

    return $out;
}

if(__FILE__ == $_SERVER["SCRIPT_FILENAME"]) {
    $out = dynamic($pathInfo);
    if($out===false) {
        \http_response_code(404);
        echo 'File not found';
    } else {
        \header("Content-Type: application/x-shockwave-flash");
        echo $out;
    }
}