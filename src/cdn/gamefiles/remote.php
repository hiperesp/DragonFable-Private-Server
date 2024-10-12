<?php declare(strict_types=1);

function remote(string $pathInfo): string|false {
    if(!\preg_match('/\.swf$/', $pathInfo)) {
        return false;
    }

    $remoteFile = "https://play.dragonfable.com/game/gamefiles{$pathInfo}";

    $ch = \curl_init();
    \curl_setopt($ch, CURLOPT_URL, $remoteFile);
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, CURLOPT_HEADER, 0);
    $response = \curl_exec($ch);
    $httpcode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
    \curl_close($ch);

    if ($httpcode != 200) {
        return false;
    }

    return $response;
}

if(__FILE__ == $_SERVER["SCRIPT_FILENAME"]) {
    $out = remote($_SERVER['PATH_INFO']);
    if($out===false) {
        \http_response_code(404);
        echo 'File not found';
    } else {
        \header("Content-Type: application/x-shockwave-flash");
        echo $out;
    }
}