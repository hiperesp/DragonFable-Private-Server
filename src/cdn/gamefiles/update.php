<?php declare(strict_types=1);

function update(string $pathInfo, ?string $requestedVersion = null): bool {
    $versionFile = \preg_replace('/\.swf$/', '.ver', __DIR__.$pathInfo);

    $needUpdate = true;
    if(\file_exists(__DIR__.$pathInfo)) {
        $needUpdate = false;
        if($requestedVersion) {
            $needUpdate = true;
            if(\file_exists($versionFile)) {
                $version = \file_get_contents($versionFile);
                if($version === $requestedVersion) {
                    $needUpdate = false;
                }
            }
        }
    }

    if(!$needUpdate) {
        return true; // already updated;
    }

    include_once 'remote.php';
    $out = remote($pathInfo);
    if($out!==false) {
        $localFile = __DIR__.$pathInfo;
        $localDir = \dirname($localFile);
        if(!\is_dir($localDir)) {
            \mkdir($localDir, 0755, true);
        }
        \file_put_contents($localFile, $out);

        if($requestedVersion) {
            \file_put_contents($versionFile, $requestedVersion);
        }
    }

    return !!$out;
}

if(__FILE__ == \realpath($_SERVER["SCRIPT_FILENAME"])) {
    \http_response_code(403);
    echo 'Not Allowed';
}