<?php

\set_time_limit(0);
\ini_set('memory_limit', '16384M');

$save = [
    "quest" => true,
    "shop" => false,
];

if($save["quest"]) {
    $questFiles = \scandir(__DIR__."/quests");
    \usort($questFiles, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });

    foreach($questFiles as $file) {
        if(\pathinfo($file, PATHINFO_EXTENSION) !== 'xml') continue;
        if(\preg_match('/da_quest([\d]+)\.xml/', $file)) continue;
        if(\preg_match('/lvl_quest([\d]+)\.xml/', $file)) continue;

        $data = convert("quest", __DIR__."/quests/".$file);
        save("quest", $data);
    }
}

if($save["shop"]) {
    $shopFiles = \scandir(__DIR__."/shops");
    \usort($shopFiles, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });

    foreach($shopFiles as $file) {
        if(\pathinfo($file, PATHINFO_EXTENSION) !== 'xml') continue;
        if(\preg_match('/empty_shop([\d]+)\.xml/', $file)) continue;
        $data = convert("shop", __DIR__."/shops/".$file);

        save("shop", $data);
    }
}

function save(string $type, array $newData): void {
    static $uniqueId = 9_000_000;

    $subTypes = match($type) {
        "shop" => [ "shop", "item", "shop_item" ],
        "quest" => [ "race", "quest", "monster", "quest_monster", "item" ],
    };

    $outDir = __DIR__."/json/";

    foreach($subTypes as $subType) {

        if(!\is_dir("{$outDir}{$subType}")) {
            \mkdir("{$outDir}{$subType}", 0777, true);
        }

        $file = "{$outDir}{$subType}/extracted-from-{$type}.json";

        if(!\file_exists($file)) {
            \file_put_contents($file, "[]");
        }
        $currentData = \json_decode(\file_get_contents($file), true);
        foreach($newData[$subType] as $newItem) {
            if(!\in_array($subType, ["quest_monster"])) { // can have multiple duplicates and dont have id
                for($i=0; $i<\count($currentData); $i++) {
                    $currentItem = $currentData[$i];
                    if(!isset($currentItem["id"])) {
                        throw new \Exception("Missing id in current item. SubType: {$subType}");
                    }
                    if($currentItem["id"] == $newItem["id"]) {
                        foreach($newItem as $key => $value) {
                            if($key[0] == "#") continue;
                            if($currentItem[$key] != $value) {
                                var_dump($currentItem, $newItem);
                                throw new \Exception("Duplicate id found: {$newItem["id"]}. Different data found. SubType: {$subType}");
                                break;
                            }
                        }
                        continue 2;
                    }
                }
            }
            if($subType == "monster") {
                // find armor and weapon by properties
                foreach([
                    "armor" => [
                        "type" => "item",
                        "appendData" => [
                            "category" => "Armor",
                        ],
                    ],
                    "weapon" => [
                        "type" => "item",
                        "appendData" => [
                            "category" => "Weapon",
                        ],
                    ],
                ] as $key => $findProps) {
                    $findPropsFile = "{$outDir}{$findProps["type"]}/extracted-from-{$type}.json";
                    if(!\file_exists($findPropsFile)) {
                        \file_put_contents($findPropsFile, "[]");
                    }
                    $subData = \json_decode(\file_get_contents($findPropsFile), true);
                    if($subData===null) {
                        throw new \Exception("Failed to load json file: {$findPropsFile} - Monster: {$newItem["name"]}, ID: {$newItem["id"]}, Key: {$key}");
                    }
                    $subItem = null;
                    foreach($subData as $subItemTest) {
                        foreach($newItem["#{$key}"] as $subKey => $subValue) {
                            if($subItemTest[$subKey] != $subValue) {
                                continue 2;
                            }
                        }
                        $subItem = $subItemTest;
                    }
                    if($subItem === null) {
                        $newItem["#{$key}"]["id"] = $uniqueId++;
                        foreach($findProps["appendData"] as $appendKey => $appendValue) {
                            $newItem["#{$key}"][$appendKey] = $appendValue;
                        }
                        $dataToSave = [];
                        foreach($subTypes as $emptyParam) {
                            $dataToSave[$emptyParam] = [];
                        }
                        $dataToSave[$findProps["type"]] = [$newItem["#{$key}"]];
                        save($type, $dataToSave);

                        $subItem = $newItem["#{$key}"];
                    }
                    $newItem["{$key}Id"] = $subItem["id"];
                }
                unset($newItem["#armor"]);
                unset($newItem["#weapon"]);
            }
            \array_push($currentData, $newItem);
        }
        \file_put_contents($file, \json_encode($currentData, JSON_PRETTY_PRINT));
    }
}

function convert(string $type, string $file): array {
    $xml = \simplexml_load_file($file);
    if($xml===false) {
        throw new \Exception("Failed to load xml file: {$file}");
    }
    $xmlJsonStr = \json_encode($xml, JSON_PRETTY_PRINT);
    $xmlJson = \json_decode($xmlJsonStr, true); // fast way to get xml props as array

    $out = [];

    if($type=="quest") {

        $out["quest"]         = [];
        $out["monster"]       = [];
        $out["quest_monster"] = [];
        $out["race"]          = [];
        $out["item"]          = [];

        if(!isset($xmlJson["quest"][0])) {
            $xmlJson["quest"] = [$xmlJson["quest"]];
        }
        foreach($xmlJson["quest"] as $quest) {
            $out["quest"][] = [
                "id"              => (int)$quest['@attributes']['QuestID'],
                "name"            =>      $quest['@attributes']['strName'],
                "description"     =>      $quest['@attributes']['strDescription'],
                "complete"        =>      $quest['@attributes']['strComplete'],
                "swf"             =>      $quest['@attributes']['strFileName'],
                "swfX"            =>      $quest['@attributes']['strXFileName'],
                "maxSilver"       => (int)$quest['@attributes']['intMaxSilver'],
                "maxGold"         => (int)$quest['@attributes']['intMaxGold'],
                "maxGems"         => (int)$quest['@attributes']['intMaxGems'],
                "maxExp"          => (int)$quest['@attributes']['intMaxExp'],
                "minTime"         => (int)$quest['@attributes']['intMinTime'],
                "counter"         => (int)$quest['@attributes']['intCounter'],
                "extra"           =>      $quest['@attributes']['strExtra'],
                "dailyIndex"      => (int)$quest['@attributes']['intDailyIndex'],
                "dailyReward"     => (int)$quest['@attributes']['intDailyReward'],
                "monsterMinLevel" => (int)$quest['@attributes']['intMonsterMinLevel'],
                "monsterMaxLevel" => (int)$quest['@attributes']['intMonsterMaxLevel'],
                "monsterType"     =>      $quest['@attributes']['strMonsterType'],
                "monsterGroupSwf" =>      $quest['@attributes']['strMonsterGroupFileName'],
            ];

            if(!isset($quest["monsters"])) continue;
            if(!isset($quest["monsters"][0])) {
                $quest["monsters"] = [$quest["monsters"]];
            }
            foreach($quest["monsters"] as $monster) {
                $out["monster"][] = [
                    "id"            =>    (int)$monster['@attributes']['MonsterID'],
                    "name"          =>         $monster['@attributes']['strCharacterName'],
                    "level"         =>    (int)$monster['@attributes']['intLevel'],
                    "experience"    =>    (int)$monster['@attributes']['intExp'],
                    "hitPoints"     =>    (int)$monster['@attributes']['intHP'],
                    "manaPoints"    =>    (int)$monster['@attributes']['intMP'],
                    "silver"        =>    (int)$monster['@attributes']['intSilver'],
                    "gold"          =>    (int)$monster['@attributes']['intGold'],
                    "gems"          =>    (int)$monster['@attributes']['intGems'],
                    "coins"         =>    (int)$monster['@attributes']['intDragonCoins'],
                    "gender"        =>         $monster['@attributes']['strGender'],
                    "hairStyle"     => \dechex($monster['@attributes']['intHairStyle']),
                    "colorHair"     => \dechex($monster['@attributes']['intColorHair']),
                    "colorSkin"     => \dechex($monster['@attributes']['intColorSkin']),
                    "colorBase"     => \dechex($monster['@attributes']['intColorBase']),
                    "colorTrim"     => \dechex($monster['@attributes']['intColorTrim']),
                    "strength"      =>    (int)$monster['@attributes']['intStr'],
                    "dexterity"     =>    (int)$monster['@attributes']['intDex'],
                    "intelligence"  =>    (int)$monster['@attributes']['intInt'],
                    "luck"          =>    (int)$monster['@attributes']['intLuk'],
                    "charisma"      =>    (int)$monster['@attributes']['intCha'],
                    "endurance"     =>    (int)$monster['@attributes']['intEnd'],
                    "wisdom"        =>    (int)$monster['@attributes']['intWis'],
                    "element"       =>         $monster['@attributes']['strElement'],
                    "raceId"        =>    (int)$monster['@attributes']['RaceID'],
                    "movName"       =>         $monster['@attributes']['strMovName'],
                    "swf"           =>         $monster['@attributes']['strMonsterFileName'],

                    "#armor" => [
                        "id"            =>      NULL, // auto generated for now or verify if exists at shop
                        "name"          =>      $monster['@attributes']['strArmorName'],
                        "description"   =>      $monster['@attributes']['strArmorDescription'],
                        "designInfo"    =>      $monster['@attributes']['strArmorDesignInfo'],
                        "resists"       =>      $monster['@attributes']['strArmorResists'],
                        "defenseMelee"  => (int)$monster['@attributes']['intDefMelee'],
                        "defensePierce" => (int)$monster['@attributes']['intDefPierce'],
                        "defenseMagic"  => (int)$monster['@attributes']['intDefMagic'],
                        "parry"         => (int)$monster['@attributes']['intParry'],
                        "dodge"         => (int)$monster['@attributes']['intDodge'],
                        "block"         => (int)$monster['@attributes']['intBlock'],
                    ],
                    "#weapon" => [
                        "id"            =>      NULL, // auto generated for now or verify if exists at shop
                        "name"          =>      $monster['@attributes']['strWeaponName'],
                        "description"   =>      $monster['@attributes']['strWeaponDescription'],
                        "designInfo"    =>      $monster['@attributes']['strWeaponDesignInfo'],
                        "resists"       =>      $monster['@attributes']['strWeaponResists'],
                        "level"         =>      0, // default
                        "icon"          =>      "", // default
                        "type"          =>      $monster['@attributes']['strType'],
                        "itemType"      =>      "", // default
                        "critical"      => (int)$monster['@attributes']['intCrit'],
                        "damageMin"     => (int)$monster['@attributes']['intDmgMin'],
                        "damageMax"     => (int)$monster['@attributes']['intDmgMax'],
                        "bonus"         => (int)$monster['@attributes']['intBonus'],
                        "swf"           =>      $monster['@attributes']['strWeaponFile'],
                    ]
                ];
                $out["quest_monster"][] = [
                    "questId"   => (int)$quest['@attributes']['QuestID'],
                    "monsterId" => (int)$monster['@attributes']['MonsterID'],
                ];
                $out["race"][] = [
                    "id"        => (int)$monster['@attributes']['RaceID'],
                    "name"      =>      $monster['@attributes']['strRaceName'],
                    "resists"   =>      "", // default
                ];
            }
        }
    } else if($type == "shop") {

        $out["shop"]      = [];
        $out["item"]      = [];
        $out["shop_item"] = [];

        if(!isset($xmlJson["shop"][0])) {
            $xmlJson["shop"] = [$xmlJson["shop"]];
        }
        foreach($xmlJson["shop"] as $shop) {
            $out["shop"][] = [
                'id'    => (int)$shop['@attributes']['ShopID'],
                'name'  =>      $shop['@attributes']['strCharacterName'],
                'count' => (int)$shop['@attributes']['intCount'],
            ];

            if(!isset($shop["items"])) continue;
            if(!isset($shop["items"][0])) {
                $shop["items"] = [$shop["items"]];
            }
            foreach($shop['items'] as $item) {
                if(!isset($item['@attributes']['ItemID'])) {
                    var_dump($shop);die;
                }
                $out["item"][] = [
                    "id"            =>    (int)$item['@attributes']['ItemID'],
                    "name"          =>         $item['@attributes']['strItemName'],
                    "description"   =>         $item['@attributes']['strItemDescription'],
                    "visible"       =>    (int)$item['@attributes']['bitVisible'],
                    "destroyable"   =>    (int)$item['@attributes']['bitDestroyable'],
                    "sellable"      =>    (int)$item['@attributes']['bitSellable'],
                    "dragonAmulet"  =>    (int)$item['@attributes']['bitDragonAmulet'],
                    "currency"      =>    (int)$item['@attributes']['intCurrency'],
                    "cost"          =>    (int)$item['@attributes']['intCost'],
                    "maxStackSize"  =>    (int)$item['@attributes']['intMaxStackSize'],
                    "bonus"         =>    (int)$item['@attributes']['intBonus'],
                    "rarity"        =>    (int)$item['@attributes']['intRarity'],
                    "level"         =>    (int)$item['@attributes']['intLevel'],
                    "type"          =>         $item['@attributes']['strType'],
                    "element"       =>         $item['@attributes']['strElement'],
                    "category"      =>         $item['@attributes']['strCategory'],
                    "equipSpot"     =>         $item['@attributes']['strEquipSpot'],
                    "itemType"      =>         $item['@attributes']['strItemType'],
                    "swf"           =>         $item['@attributes']['strFileName'],
                    "icon"          =>         $item['@attributes']['strIcon'],
                    "strength"      =>    (int)$item['@attributes']['intStr'],
                    "dexterity"     =>    (int)$item['@attributes']['intDex'],
                    "intelligence"  =>    (int)$item['@attributes']['intInt'],
                    "luck"          =>    (int)$item['@attributes']['intLuk'],
                    "charisma"      =>    (int)$item['@attributes']['intCha'],
                    "endurance"     =>    (int)$item['@attributes']['intEnd'],
                    "wisdom"        =>    (int)$item['@attributes']['intWis'],
                    "damageMin"     =>    (int)$item['@attributes']['intMin'],
                    "damageMax"     =>    (int)$item['@attributes']['intMax'],
                    "defenseMelee"  =>    (int)$item['@attributes']['intDefMelee'],
                    "defensePierce" =>    (int)$item['@attributes']['intDefPierce'],
                    "defenseMagic"  =>    (int)$item['@attributes']['intDefMagic'],
                    "critical"      =>    (int)$item['@attributes']['intCrit'],
                    "parry"         =>    (int)$item['@attributes']['intParry'],
                    "dodge"         =>    (int)$item['@attributes']['intDodge'],
                    "block"         =>    (int)$item['@attributes']['intBlock'],
                    "resists"       =>         $item['@attributes']['strResists'],
                ];

                $out["shop_item"][] = [
                    "id"     => (int)$item['@attributes']['ShopItemID'], // associative key??
                    "shopId" => (int)$shop['@attributes']['ShopID'],
                    "itemId" => (int)$item['@attributes']['ItemID'],
                ];
            }
        }
    }

    return $out;
}