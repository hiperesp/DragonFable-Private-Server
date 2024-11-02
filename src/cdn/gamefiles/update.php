<?php declare(strict_types=1);

$pathInfo = \strtolower($_SERVER['PATH_INFO']);

function update(string $pathInfo): string|false {
    list($filemtimeInterval, $pathInfo) = parsePathInfo($pathInfo);

    $fetchRemote = fetchRemote((int)$filemtimeInterval, $pathInfo);

    if($fetchRemote) {
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
    }

    include 'local.php';
    $out = local($pathInfo);
    if($out!==false) {
        return $out;
    }

    return $out;
}

function parsePathInfo(string $pathInfo): array {
    $pathParts = \explode('/', $pathInfo);
    $filemtimeInterval = $pathParts[1];
    unset($pathParts[1]);
    return [ $filemtimeInterval, \implode('/', $pathParts) ];
}

function fetchRemote(int $filemtimeInterval, string $pathInfo): bool {

    if(!\preg_match('/\.swf$/', $pathInfo)) {
        return false;
    }

    $localFile = __DIR__.$pathInfo;

    if(\file_exists($localFile)) {
        $localFilemtime = \filemtime($localFile);
        if($localFilemtime + $filemtimeInterval > \time()) {
            return false;
        }
    }

    return true;
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