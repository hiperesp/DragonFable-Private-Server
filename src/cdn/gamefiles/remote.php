<?php declare(strict_types=1);

function remote(string $pathInfo): string|false {
    if(!\preg_match('/\.swf$/', $pathInfo)) {
        return false;
    }

    $pathInfo = \str_replace(' ', '%20', $pathInfo);
    $remoteFile = "https://play.dragonfable.com/game/gamefiles{$pathInfo}";

    $ch = \curl_init();
    \curl_setopt($ch, CURLOPT_URL, $remoteFile);
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, CURLOPT_HEADER, 0);
    \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = \curl_exec($ch);
    $httpcode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
    \curl_close($ch);

    if ($httpcode != 200) {
        return false;
    }

    return $response;
}

if(__FILE__ == \realpath($_SERVER["SCRIPT_FILENAME"])) {
    $pathInfo = \strtolower($_SERVER['PATH_INFO']);

    $out = remote($pathInfo);
    if($out===false) {
        \http_response_code(404);
        echo 'File not found';
    } else {
        \header("Content-Type: application/x-shockwave-flash");
        echo $out;
    }
}