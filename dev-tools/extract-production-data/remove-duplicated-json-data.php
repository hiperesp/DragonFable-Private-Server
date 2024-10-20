<?php

\set_time_limit(0);
\ini_set('memory_limit', '16384M');
\error_reporting(E_ALL);
\ini_set('display_errors', '1');

foreach([
    "class", "houseItem", "houseItemShop", "houseItemShop_houseItem", "house", "houseShop", "houseShop_house", "interface", "item", "itemShop", "itemShop_item", "mergeShop", "mergeShop_item",
    "monster", "quest", "quest_monster", "race"
] as $dir) {
    $files = \scandir(__DIR__."/json/{$dir}/");
    $files = \array_filter($files, function($file) {
        return $file !== '.' && $file !== '..';
    });

    $allData = [];
    foreach($files as $file) {
        $fileData = \json_decode(\file_get_contents(__DIR__."/json/{$dir}/{$file}"), true);
        foreach($fileData as $item) {
            if(!@$item['id']) {
                continue;
            }
            foreach($allData as $allItem) {
                if(!@$allItem['id']) {
                    continue;
                }
                if($allItem['id'] === $item['id']) {
                    foreach($item as $key => $value) {
                        if(!@$item[$key]) continue;
                        if($allItem[$key]) {
                            if($item[$key] == $allItem[$key]) {
                                continue;
                            }
                            throw new \Exception("Duplicated data found in {$file} with id {$item['id']} and key {$key}");
                        }
                        $allItem[$key] = $item[$key];
                        continue 2;
                    }
                }
            }
            $allData[] = $item;
        }
    }

    $allData = \array_values($allData);

    \usort($allData, function($a, $b) {
        return $a['id'] - $b['id'];
    });

    if($allData) {
        \file_put_contents(__DIR__."/json/{$dir}/merged.json", \json_encode($allData, JSON_PRETTY_PRINT));
    }
}