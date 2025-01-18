#!/usr/bin/env php
<?php
include_once "../../cdn/gamefiles/update.php";

$skipDownloaded = false;

$toDownload = [
    "interface" => [
        [ "field" => "swf", "basePath" => "", ],
    ],
    "quest" => [
        [ "field" => "swf", "basePath" => "maps/", ],
        [ "field" => "swfX", "basePath" => "maps/", ],
        [ "field" => "monsterGroupSwf", "basePath" => "monsters/", ],
        [ "field" => "extra", "basePath" => "maps/", "special" => "quest_extra" ],
    ],
    "monster" => [
        [ "field" => "swf", "basePath" => "", ],
    ],
    "class" => [
        [ "field" => "swf", "basePath" => "classes/f/", ],
        [ "field" => "swf", "basePath" => "classes/m/", ],
    ],
    "item" => [
        [ "field" => "swf", "basePath" => "", ],
    ],
];

$startTime = (int)\microtime(true);
downloadAll($toDownload);

function downloadAll($toDownload) {
    global $skipDownloaded;

    \file_put_contents("download-swf-success.txt", "");
    \file_put_contents("download-swf-fail.txt", "");
    \file_put_contents("download-swf-skip.txt", "");

    $dataToDownload = [];
    foreach($toDownload as $type => $definitions) {
        $dataToDownload[$type] = [];

        $jsonList = \array_filter(\scandir("converted/{$type}/"), function(string $file) {
            return $file !== "." && $file !== "..";
        });
        foreach($jsonList as $json) {
            $data = \json_decode(\file_get_contents("converted/{$type}/{$json}"), true);
            foreach($data as $item) {
                foreach($definitions as $definition) {
                    if(!isset($item[$definition["field"]])) {
                        continue;
                    }
                    foreach(createUri($item, $definition) as $newUri) {
                        $dataToDownload[$type][] = $newUri;
                    }
                }
            }
        }
    }

    foreach($dataToDownload as $type => $data) {
        $dataToDownload[$type] = \array_unique($data);
    }

    $total = \array_reduce($dataToDownload, function(int $carry, array $data) {
        return $carry + \count($data);
    }, 0);
    $current = 0;
    foreach($dataToDownload as $type => $data) {
        foreach($data as $uri) {
            echo "[0] Downloading {$uri}... ".getPercentString($current, $total)."\n";
            if($skipDownloaded) {
                $uriLc = \strtolower($uri);
                if(\file_exists("../../cdn/gamefiles/{$uriLc}")) {
                    echo "[0] Skipping {$uri}...\n";
                    $current++;
                    \file_put_contents("download-swf-success.txt", "{$uri}\n", FILE_APPEND);
                    continue;
                }
            }

            $parsedUri = "/".\parse_url($uri, PHP_URL_PATH);
            $parsedVersion = \parse_url($uri, PHP_URL_QUERY);

            $downloaded = update(\strtolower(\urldecode($parsedUri)), $parsedVersion);
            if($downloaded) {
                \file_put_contents("download-swf-success.txt", "{$uri}\n", FILE_APPEND);
            } else {
                echo "[0] Failed to download {$uri}...\n";
                \file_put_contents("download-swf-fail.txt", "{$uri}\n", FILE_APPEND);
            }
            $current++;

            // free memory
            \gc_collect_cycles();
            \gc_mem_caches();
            unset($downloaded);
        }
    }
    return $dataToDownload;
}

function createUri(array $data, array $definition): array {
    if(!isset($definition["special"])) {
        $definition["special"] = "none";
    }

    $basePath = $definition["basePath"];
    $field = $definition["field"];
    $value = $data[$field];

    $toParse = [];
    switch($definition["special"]) {
        case "quest_extra":
            if($value) {
                if(\preg_match('/\r?\n/', $value)) {
                    $extra = \preg_split('/\r?\n/', $value);
                } else {
                    $extra = [$value];
                }
                foreach($extra as $extraItem) {
                    if(!\preg_match('/\.swf/', $extraItem)) {
                        continue;
                    } 
                    if(\preg_match('/(?:.+?=)?(.+)/', $extraItem, $matches)) {
                        $toParse[] = $matches[1];
                    } else {
                        echo "Skipping quest extra: {$extraItem}\n";
                    }
                }
            }
            break;
        default:
            $toParse[] = $value;
            break;
    }

    $uriList = [];
    foreach($toParse as $item) {
        $item = \trim($item);

        if(\in_array($item, [
            "", "x", "none", "nothing",
        ])) {
            continue;
        }

        $replaces = [
            " " => "%20",
            "\\" => "/",
        ];
        $item = \str_replace(\array_keys($replaces), \array_values($replaces), $item);

        $item = \trim($item);
        if(\strpos($item, $basePath)===0) {
            $newUri = $item;
        } else {
            $newUri = \trim("{$basePath}{$item}");
        }

        if($newUri==="items/artifacts/") {
            continue;
        }
        if(\strpos($newUri, '.swf') === false) {
            \file_put_contents("download-swf-skip.txt", "{$newUri}\n", FILE_APPEND);
            continue;
            throw new \Exception("Invalid swf uri: {$newUri}");
        }

        $uriList[] = $newUri;
    }

    return $uriList;
}

function getPercentString(int $current, int $total): string {
    global $startTime;

    $percent = (\number_format($current / $total, 5) * 100)."%";
    $memoryUsageMB = \memory_get_usage(true) / 1024 / 1024;
    $memoryUsageStr = \number_format($memoryUsageMB)."M";

    $elapsedTime = (int)\microtime(true) - $startTime;
    $estimatedTotalTime = ($elapsedTime / ($current + 1)) * $total;
    $remainingTime = $estimatedTotalTime - $elapsedTime;
    $eta = \gmdate("H:i:s", (int)$remainingTime);

    return "({$percent}) - MEM: {$memoryUsageStr} - ETA: {$eta}";
}