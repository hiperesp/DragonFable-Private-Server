<?php

$files = \scandir(__DIR__."/json/item/");
$files = \array_filter($files, function($file) {
    return $file !== '.' && $file !== '..';
});

$mergeShopFile = "extracted-from-mergeShop.json";

if(!\in_array($mergeShopFile, $files)) {
    echo "File not found: {$mergeShopFile}\n";
    exit;
}

$allData = [];
$mergeShopData = [];
foreach($files as $file) {
    $data = \json_decode(\file_get_contents(__DIR__."/json/item/{$file}"), true);
    if($file === $mergeShopFile) {
        $mergeShopData = \array_merge($mergeShopData, $data);
    } else {
        $allData = \array_merge($allData, $data);
    }
}

foreach($mergeShopData as $key => $mergeShopItem) {
    $found = false;
    foreach($allData as $item) {
        if($item['id'] === $mergeShopItem['id']) {
            $found = true;
            break;
        }
    }

    if(!$found) {
        continue;
    }

    unset($mergeShopData[$key]);
}

$mergeShopData = \array_values($mergeShopData);

\file_put_contents(__DIR__."/json/item/{$mergeShopFile}", \json_encode($mergeShopData, JSON_PRETTY_PRINT));