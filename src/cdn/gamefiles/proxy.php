<?php declare(strict_types=1);
$requestPath = $_SERVER['REQUEST_URI'];
$requestPath = \preg_replace('/\?.*$/', '', $requestPath);
$requestPath = \preg_replace('/^\/cdn\/gamefiles\//', '', $requestPath);
$localFile = __DIR__."/".\strtolower(\urldecode($requestPath));

if(!\file_exists($localFile)) {
    $ch = \curl_init();
    \curl_setopt($ch, CURLOPT_URL, "https://play.dragonfable.com/game/gamefiles/{$requestPath}");
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, CURLOPT_HEADER, 0);
    $response = \curl_exec($ch);
    $httpcode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = \curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    \curl_close($ch);

    if ($httpcode != 200) {
        \http_response_code(404);
        echo 'File not found';
        exit;
    }

    $dir = \dirname($localFile);
    if (!\is_dir($dir)) {
        \mkdir($dir, 0777, true);
    }
    \file_put_contents($localFile, $response);
}
$contentType = \mime_content_type($localFile);
\header("Content-Type: {$contentType}");
\readfile($localFile);
