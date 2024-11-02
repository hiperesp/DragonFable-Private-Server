<?php declare(strict_types=1);

$pathInfo = \strtolower($_SERVER['PATH_INFO']);

function local(string $pathInfo): string|false {
    if(!\preg_match('/\.swf$/', $pathInfo)) {
        return false;
    }

    $localFile = __DIR__.$pathInfo;

    if(!\file_exists($localFile)) {
        return false;
    }

    return \file_get_contents($localFile);
}

if(__FILE__ == $_SERVER["SCRIPT_FILENAME"]) {
    $out = local($pathInfo);
    if($out===false) {
        \http_response_code(404);
        echo 'File not found';
    } else {
        \header("Content-Type: application/x-shockwave-flash");
        echo $out;
    }
}