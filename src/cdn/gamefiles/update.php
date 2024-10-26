<?php declare(strict_types=1);

$pathInfo = $_SERVER['PATH_INFO'];

function update(string $pathInfo): string|false {
    include 'remote.php';
    $out = remote($pathInfo);
    if($out!==false) {
        $localFile = __DIR__.$pathInfo;
        $localDir = \dirname($localFile);
        if(!\is_dir($localDir)) {
            \mkdir($localDir, 0755, true);
        }
        \file_put_contents($localFile, $out);
    }

    include 'local.php';
    $out = local($pathInfo);
    if($out!==false) {
        return $out;
    }

    return $out;
}

if(__FILE__ == $_SERVER["SCRIPT_FILENAME"]) {
    $out = update($pathInfo);
    if($out===false) {
        \http_response_code(404);
        echo 'File not found';
    } else {
        \header("Content-Type: application/x-shockwave-flash");
        echo $out;
    }
}