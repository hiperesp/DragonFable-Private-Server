<?php declare(strict_types=1);

function local(string $pathInfo): string|false {
    $pathInfo = \strtolower($pathInfo);
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
    $out = local($_SERVER['PATH_INFO']);
    if($out===false) {
        \http_response_code(404);
        echo 'File not found';
    } else {
        \header("Content-Type: application/x-shockwave-flash");
        echo $out;
    }
}