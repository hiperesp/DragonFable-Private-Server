<?php
$ch = \curl_init();
\curl_setopt($ch, CURLOPT_URL, "https://play.dragonfable.com/game/gamefiles/{$_GET['url']}");
\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
\curl_setopt($ch, CURLOPT_HEADER, 0);
$response = \curl_exec($ch);
$httpcode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = \curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
\curl_close($ch);

if ($httpcode == 200) {
    $path = __DIR__ . '/' . $_GET['url'];
    $dir = \dirname($path);
    if (!\is_dir($dir)) {
        \mkdir($dir, 0777, true);
    }
    \file_put_contents($path, $response);
}

header('Content-Type: ' . $contentType);
\http_response_code($httpcode);
echo $response;