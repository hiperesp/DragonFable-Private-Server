#!/usr/bin/env php
<?php
$cdn = "http://localhost/cdn/";
$maxFilemtime = 24 * 60 * 60; // 24 hours

$toDownload = [
    
    "interface" => [
        [ "field" => "swf", "basePath" => "", ],
    ],
    "quest" => [
        [ "field" => "swf", "basePath" => "", ],
        [ "field" => "swfX", "basePath" => "", ],
        [ "field" => "monsterGroupSwf", "basePath" => "monsters/", ],
        [ "field" => "extra", "basePath" => "", "special" => "quest_extra" ],
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

downloadAll($toDownload);

function downloadAll($toDownload) {
    global $cdn, $maxFilemtime;

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

    $total = \array_reduce($dataToDownload, function(int $carry, array $data) {
        return $carry + \count($data);
    }, 0);
    $current = 0;
    foreach($dataToDownload as $type => $data) {
        foreach($data as $uri) {
            echo "[0] Downloading {$uri}... ".getPercentString($current, $total)."\n";
            $downloaded = !!@\file_get_contents("{$cdn}gamefiles/update.php/{$maxFilemtime}/{$uri}");
            if($downloaded) {
                \file_put_contents("download-swf-success.txt", "{$uri}\n", FILE_APPEND);
            } else {
                echo "[0] Failed to download {$uri}...\n";
                \file_put_contents("download-swf-fail.txt", "{$uri}\n", FILE_APPEND);
            }
            $current++;
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
        if(\preg_match('/\d+/', $item)) {
            continue;
        }
        if(\in_array($item, [
            "", "x", "none", "nothing",
        ])) {
            continue;
        }

        $newUri = \trim("{$basePath}{$item}");

        if(\preg_match('/\.swf/', $newUri) === 0) {
            throw new \Exception("Invalid swf uri: {$newUri}");
        }

        $uriList[] = $newUri;
    }

    return $uriList;
}

function getPercentString(int $current, int $total): string {
    $percent = (\number_format($current / $total, 5) * 100)."%";
    $memoryUsageMB = \memory_get_usage(true) / 1024 / 1024;
    $memoryUsageStr = \number_format($memoryUsageMB)."M";

    return "{$current} of {$total} ({$percent}) - MEM: {$memoryUsageStr}";
}