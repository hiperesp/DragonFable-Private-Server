<?php

\set_time_limit(0);
\ini_set('memory_limit', '16384M');
\error_reporting(E_ALL);
\ini_set('display_errors', '1');

$save = [
    "town" => true,
    "quest" => true,
    "shop" => true,
    "interface" => true,
    "houseShop" => true,
    "houseItemShop" => true,
    "mergeShop" => true,
    "classes" => true,
];

if($save["town"]) {
    $questFiles = \scandir(__DIR__."/towns");
    \usort($questFiles, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });

    foreach($questFiles as $file) {
        if(\pathinfo($file, PATHINFO_EXTENSION) !== 'xml') continue;
        if(!\preg_match('/^town([\d]+)\.xml/', $file)) continue;

        $data = convert("town", __DIR__."/towns/".$file);
        save("town", $data);
    }
}

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

        save("itemShop", $data);
    }
}

if($save["interface"]) {
    $interfaceFiles = \scandir(__DIR__."/interfaces");
    \usort($interfaceFiles, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });

    foreach($interfaceFiles as $file) {
        if(\pathinfo($file, PATHINFO_EXTENSION) !== 'xml') continue;
        $data = convert("interface", __DIR__."/interfaces/".$file);

        save("interface", $data);
    }
}

if($save["houseShop"]) {
    $shopFiles = \scandir(__DIR__."/houseShops");
    \usort($shopFiles, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });

    foreach($shopFiles as $file) {
        if(\pathinfo($file, PATHINFO_EXTENSION) !== 'xml') continue;
        $data = convert("houseShop", __DIR__."/houseShops/".$file);

        save("houseShop", $data);
    }
}

if($save["houseItemShop"]) {
    $shopFiles = \scandir(__DIR__."/houseItemShops");
    \usort($shopFiles, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });

    foreach($shopFiles as $file) {
        if(\pathinfo($file, PATHINFO_EXTENSION) !== 'xml') continue;
        $data = convert("houseItemShop", __DIR__."/houseItemShops/".$file);

        save("houseItemShop", $data);
    }
}

if($save["mergeShop"]) {
    $shopFiles = \scandir(__DIR__."/mergeShops");
    \usort($shopFiles, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });

    foreach($shopFiles as $file) {
        if(\pathinfo($file, PATHINFO_EXTENSION) !== 'xml') continue;
        $data = convert("mergeShop", __DIR__."/mergeShops/".$file);

        save("mergeShop", $data);
    }
}

if($save["classes"]) {
    $classFiles = \scandir(__DIR__."/classes");
    \usort($classFiles, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });

    foreach($classFiles as $file) {
        if(\pathinfo($file, PATHINFO_EXTENSION) !== 'xml') continue;
        $data = convert("classes", __DIR__."/classes/".$file);

        save("classes", $data);
    }
}

function save(string $type, array $newData): void {
    static $uniqueId = 9_900_000;

    $subTypes = match($type) {
        "itemShop" => [ "itemShop", "item", "itemShop_item" ],
        "town" => [ "quest", ],
        "quest" => [ "race", "quest", "monster", "quest_monster", "item" ],
        "interface" => [ "interface" ],
        "houseShop" => [ "houseShop", "house", "houseShop_house" ],
        "houseItemShop" => [ "houseItemShop", "houseItem", "houseItemShop_houseItem" ],
        "mergeShop" => [ "mergeShop", "item", "mergeShop_item" ],
        "classes" => [ "class", "item", ]
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
            if(!\in_array($subType, ["quest_monster", "houseShop_house", "houseItemShop_houseItem", "mergeShop_item"])) { // can have multiple duplicates or dont have id
                for($i=0; $i<\count($currentData); $i++) {
                    $currentItem = $currentData[$i];
                    if(!isset($currentItem["id"])) {
                        throw new \Exception("Missing id in current item. SubType: {$subType}");
                    }
                    if($currentItem["id"] == $newItem["id"]) {
                        foreach($newItem as $key => $value) {
                            if($key[0] == "#") continue;
                            if(!isset($currentItem[$key])) {
                                // join data
                                $currentItem[$key] = $value;
                            }
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
                            "categoryId" => "2", // armor
                        ],
                    ],
                    "weapon" => [
                        "type" => "item",
                        "appendData" => [
                            "categoryId" => "1", // weapon
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

function normalizeNewLine(array $xml): array {
    foreach($xml as $key => $value) {
        if(\is_array($value)) {
            $xml[$key] = normalizeNewLine($value);
        } else if(\is_string($value)) {
            $xml[$key] = \str_replace("HIPERESP-NEWLINE", "\n", $value);
        }
    }
    return $xml;
}

function convert(string $type, string $file): array {
    $xmlStr = \file_get_contents($file);
    $xmlStr = \trim($xmlStr);
    $xmlStr = \preg_replace('/\r?\n/', "HIPERESP-NEWLINE", $xmlStr);
    $xmlStr = \str_replace('>HIPERESP-NEWLINE<', ">\n<", $xmlStr);
    $xml = \simplexml_load_string($xmlStr);
    if($xml===false) {
        throw new \Exception("Failed to load xml file: {$file}");
    }
    $xmlJsonStr = \json_encode($xml, JSON_PRETTY_PRINT);
    $xmlJson = \json_decode($xmlJsonStr, true); // fast way to get xml props as array

    $xmlJson = normalizeNewLine($xmlJson);

    $out = [];

    if($type=="town") {
        $out["quest"]         = [];

        if(!isset($xmlJson["newTown"][0])) {
            $xmlJson["newTown"] = [$xmlJson["newTown"]];
        }
        if(\count($xmlJson["newTown"]) != 1) {
            throw new \Exception("Invalid town file: {$file}");
        }

        $id = \preg_replace('/[^0-9]/', '', $file);
        if(!\is_numeric($id)) {
            throw new \Exception("Invalid town file: {$file}");
        }

        foreach($xmlJson["newTown"] as $town) {
            $out["quest"][] = [
                "id"              => (int)$id,
                "name"            =>      "",
                "description"     =>      "",
                "complete"        =>      "",
                "swf"             =>      $town['@attributes']['strQuestFileName'],
                "swfX"            =>      $town['@attributes']['strQuestXFileName'],
                "maxSilver"       =>      0,
                "maxGold"         =>      0,
                "maxGems"         =>      0,
                "maxExp"          =>      0,
                "minTime"         =>      0,
                "counter"         =>      0,
                "extra"           =>      $town['@attributes']['strExtra'],
                "dailyIndex"      =>      0,
                "dailyReward"     =>      0,
                "monsterMinLevel" =>      0,
                "monsterMaxLevel" =>      0,
                "monsterType"     =>      0,
                "monsterGroupSwf" =>      0,
            ];
        }
    } else if($type=="quest") {

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
                $colorHair = \dechex($monster['@attributes']['intColorHair']);
                if($colorHair == "ffffffffffffffff") $colorHair = "000000";
                $colorSkin = \dechex($monster['@attributes']['intColorSkin']);
                if($colorSkin == "ffffffffffffffff") $colorSkin = "000000";
                $colorBase = \dechex($monster['@attributes']['intColorBase']);
                if($colorBase == "ffffffffffffffff") $colorBase = "000000";
                $colorTrim = \dechex($monster['@attributes']['intColorTrim']);
                if($colorTrim == "ffffffffffffffff") $colorTrim = "000000";

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
                    "hairStyle"     =>         $monster['@attributes']['intHairStyle'],
                    "colorHair"     =>         $colorHair,
                    "colorSkin"     =>         $colorSkin,
                    "colorBase"     =>         $colorBase,
                    "colorTrim"     =>         $colorTrim,
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

        $out["itemShop"]      = [];
        $out["item"]      = [];
        $out["itemShop_item"] = [];

        if(!isset($xmlJson["shop"][0])) {
            $xmlJson["shop"] = [$xmlJson["shop"]];
        }
        foreach($xmlJson["shop"] as $shop) {
            $out["itemShop"][] = [
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
                    "categoryId"    =>       [
                        "Weapon" => 1,
                        "Armor"  => 2,
                        "Pet"    => 3,
                        "Item"   => 4,
                    ][$item['@attributes']['strCategory']],
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

                $out["itemShop_item"][] = [
                    "id"     => (int)$item['@attributes']['ShopItemID'], // associative key??
                    "itemShopId" => (int)$shop['@attributes']['ShopID'],
                    "itemId" => (int)$item['@attributes']['ItemID'],
                ];
            }
        }
    } else if($type=="interface") {
        $out["interface"][] = [
            "id"        => (int)$xmlJson['intrface']['@attributes']['InterfaceID'],
            "name"      =>      $xmlJson['intrface']['@attributes']['strName'],
            "swf"       =>      $xmlJson['intrface']['@attributes']['strFileName'],
            "loadUnder" => (int)$xmlJson['intrface']['@attributes']['bitLoadUnder'],
        ];
    } else if($type=="houseShop") {

        $out["houseShop"]       = [];
        $out["house"]           = [];
        $out["houseShop_house"] = [];

        if(!isset($xmlJson["shop"][0])) {
            $xmlJson["shop"] = [$xmlJson["shop"]];
        }
        foreach($xmlJson["shop"] as $shop) {
            $out["houseShop"][] = [
                'id'    => (int)$shop['@attributes']['ShopID'],
                'name'  =>      $shop['@attributes']['strCharacterName'],
            ];

            if(!isset($shop["sHouses"])) continue;
            if(!isset($shop["sHouses"][0])) {
                $shop["sHouses"] = [$shop["sHouses"]];
            }
            foreach($shop['sHouses'] as $house) {
                $out["house"][] = [
                    "id"            => (int)$house['@attributes']['HouseID'],
                    "name"          =>      $house['@attributes']['strHouseName'],
                    "description"   =>      $house['@attributes']['strHouseDescription'],
                    "visible"       => (int)$house['@attributes']['bitVisible'],
                    "destroyable"   => (int)$house['@attributes']['bitDestroyable'],
                    "equippable"    => (int)$house['@attributes']['bitEquippable'],
                    "randomDrop"    => (int)$house['@attributes']['bitRandomDrop'],
                    "sellable"      => (int)$house['@attributes']['bitSellable'],
                    "dragonAmulet"  => (int)$house['@attributes']['bitDragonAmulet'],
                    "enc"           => (int)$house['@attributes']['bitEnc'], // not sure what this is
                    "cost"          => (int)$house['@attributes']['intCost'],
                    "currency"      => (int)$house['@attributes']['intCurrency'],
                    "rarity"        => (int)$house['@attributes']['intRarity'],
                    "level"         => (int)$house['@attributes']['intLevel'],
                    "category"      => (int)$house['@attributes']['intCategory'],
                    "equipSpot"     => (int)$house['@attributes']['intEquipSpot'],
                    "type"          => (int)$house['@attributes']['intType'],
                    "random"        => (int)$house['@attributes']['bitRandom'],
                    "element"       => (int)$house['@attributes']['intElement'],
                    "type"          =>      $house['@attributes']['strType'],
                    "icon"          =>      $house['@attributes']['strIcon'],
                    "designInfo"    =>     @$house['@attributes']['strDesignInfo'] ?: "",
                    "swf"           =>      $house['@attributes']['strFileName'],
                    "region"        => (int)$house['@attributes']['intRegion'],
                    "theme"         => (int)$house['@attributes']['intTheme'],
                    "size"          => (int)$house['@attributes']['intSize'],
                    "baseHP"        => (int)$house['@attributes']['intBaseHP'],
                    "storageSize"   => (int)$house['@attributes']['intStorageSize'],
                    "maxGuards"     => (int)$house['@attributes']['intMaxGuards'],
                    "maxRooms"      => (int)$house['@attributes']['intMaxRooms'],
                    "maxExtItems"   => (int)$house['@attributes']['intMaxExtItems'],
                ];

                $out["houseShop_house"][] = [
                    "houseShopId"   => (int)$shop['@attributes']['ShopID'],
                    "houseId"       => (int)$house['@attributes']['HouseID'],
                ];
            }
        }

    } else if($type=="houseItemShop") {

        $out["houseItemShop"]           = [];
        $out["houseItem"]               = [];
        $out["houseItemShop_houseItem"] = [];

        if(!isset($xmlJson["houseitemshop"][0])) {
            $xmlJson["houseitemshop"] = [$xmlJson["houseitemshop"]];
        }
        foreach($xmlJson["houseitemshop"] as $shop) {
            $out["houseItemShop"][] = [
                'id'    => (int)$shop['@attributes']['houseItemShopID'],
                'name'  =>      $shop['@attributes']['strName'],
            ];

            if(!isset($shop["houseitems"])) continue;
            if(!isset($shop["houseitems"][0])) {
                $shop["houseitems"] = [$shop["houseitems"]];
            }
            foreach($shop['houseitems'] as $item) {
                $out["houseItem"][] = [
                    "id"            => (int)$item['@attributes']['HouseItemID'],
                    "name"          =>      $item['@attributes']['strItemName'],
                    "description"   =>      $item['@attributes']['strItemDescription'],
                    "visible"       => (int)$item['@attributes']['bitVisible'],
                    "destroyable"   => (int)$item['@attributes']['bitDestroyable'],
                    "equippable"    => (int)$item['@attributes']['bitEquippable'],
                    "randomDrop"    => (int)$item['@attributes']['bitRandomDrop'],
                    "sellable"      => (int)$item['@attributes']['bitSellable'],
                    "dragonAmulet"  => (int)$item['@attributes']['bitDragonAmulet'],
                    "enc"           => (int)$item['@attributes']['bitEnc'], // not sure what this is
                    "cost"          => (int)$item['@attributes']['intCost'],
                    "currency"      => (int)$item['@attributes']['intCurrency'],
                    "maxStackSize"  => (int)$item['@attributes']['intMaxStackSize'],
                    "rarity"        => (int)$item['@attributes']['intRarity'],
                    "level"         => (int)$item['@attributes']['intLevel'],
                    "maxLevel"      => (int)$item['@attributes']['intMaxLevel'],
                    "category"      => (int)$item['@attributes']['intCategory'],
                    "equipSpot"     => (int)$item['@attributes']['intEquipSpot'],
                    "type"          => (int)$item['@attributes']['intType'],
                    "random"        => (int)$item['@attributes']['bitRandom'],
                    "element"       => (int)@$item['@attributes']['intElement'] ?: 1,
                    "type"          =>      $item['@attributes']['strType'],
                    "swf"           =>      $item['@attributes']['strFileName'],
                ];

                $out["houseItemShop_houseItem"][] = [
                    "houseItemShopId"   => (int)$shop['@attributes']['houseItemShopID'],
                    "houseItemId"       => (int)$item['@attributes']['HouseItemID'],
                ];
            }
        }

    } else if($type=="mergeShop") {

        $out["mergeShop"]      = [];
        $out["item"]           = [];
        $out["mergeShop_item"] = [];

        if(!isset($xmlJson["mergeshop"][0])) {
            $xmlJson["mergeshop"] = [$xmlJson["mergeshop"]];
        }
        foreach($xmlJson["mergeshop"] as $shop) {
            $out["mergeShop"][] = [
                'id'    => (int)$shop['@attributes']['MSID'],
                'name'  =>      $shop['@attributes']['strName'],
            ];

            if(!isset($shop["items"])) continue;
            if(!isset($shop["items"][0])) {
                $shop["items"] = [$shop["items"]];
            }
            foreach($shop['items'] as $item) {
                if(!isset($item['@attributes']['ID']) || !isset($item['@attributes']['ItemID1']) || !isset($item['@attributes']['ItemID2']) || !isset($item['@attributes']['NewItemID'])) {
                    var_dump($shop);die;
                }
                $item1 = [
                    'id'    => (int)$item['@attributes']['ItemID1'],
                    'name'  =>      @$item['@attributes']['Item1'] ?: "",
                ];
                $item2 = [
                    'id'    => (int)$item['@attributes']['ItemID2'],
                    'name'  =>      @$item['@attributes']['Item2'] ?: "",
                ];
                $newItem = [
                    'id'            => (int)$item['@attributes']['NewItemID'],
                    'name'          =>      $item['@attributes']['strItemName'],
                    'description'   =>     @$item['@attributes']['strItemDescription'] ?: "",
                    'level'         => (int)$item['@attributes']['intLevel'],
                    'rarity'        => (int)$item['@attributes']['intRarity'],
                    'icon'          =>      $item['@attributes']['strIcon'],
                    'dragonAmulet'  => (int)$item['@attributes']['bitDragonAmulet'],
                    'currency'      => (int)$item['@attributes']['intCurrency'],
                    'element'       =>      $item['@attributes']['strElement'],
                    "categoryId"    =>       [
                        "Weapon" => 1,
                        "Armor"  => 2,
                        "Pet"    => 3,
                        "Item"   => 4,
                    ][$item['@attributes']['strCategory']],
                    'equipSpot'     =>      $item['@attributes']['strEquipSpot'],
                    'itemType'      =>      $item['@attributes']['strItemType'],
                    'swf'           =>      $item['@attributes']['strFileName'],
                    'damageMin'     => (int)$item['@attributes']['intMin'],
                    'damageMax'     => (int)$item['@attributes']['intMax'],
                    'strength'      => (int)$item['@attributes']['intStr'],
                    'dexterity'      => (int)$item['@attributes']['intDex'],
                    'intelligence'  => (int)$item['@attributes']['intInt'],
                    'luck'          => (int)$item['@attributes']['intLuk'],
                    'charisma'      => (int)$item['@attributes']['intCha'],
                    'endurance'     => (int)$item['@attributes']['intEnd'],
                    'wisdom'        => (int)$item['@attributes']['intWis'],
                    'critical'      => (int)$item['@attributes']['intCrit'],
                    'bonus'         => (int)$item['@attributes']['intBonus'],
                    'parry'         => (int)$item['@attributes']['intParry'],
                    'dodge'         => (int)$item['@attributes']['intDodge'],
                    'block'         => (int)$item['@attributes']['intBlock'],
                    'defenseMelee'  => (int)$item['@attributes']['intDefMelee'],
                    'defensePierce' => (int)$item['@attributes']['intDefPierce'],
                    'defenseMagic'  => (int)$item['@attributes']['intDefMagic'],
                    'maxStackSize'  => (int)$item['@attributes']['intMaxStackSize'],
                    'resists'       =>      $item['@attributes']['strResists'],
                ];

                $out["item"][] = $item1;
                $out["item"][] = $item2;
                $out["item"][] = $newItem;

                $out["mergeShop_item"][] = [
                    "mergeShopId"   => (int)$shop['@attributes']['MSID'],
                    "itemId1"       => (int)$item['@attributes']['ItemID1'],
                    'amountItem1'   => (int)$item['@attributes']['Qty1'],
                    "itemId2"       => (int)$item['@attributes']['ItemID2'],
                    'amountItem2'   => (int)$item['@attributes']['Qty2'],
                    "itemIdNew"     => (int)$item['@attributes']['NewItemID'],
                    'string'        => (int)$item['@attributes']['intString'],
                    'index'         => (int)$item['@attributes']['intIndex'],
                    'value'         => (int)$item['@attributes']['intValue'],
                    'level'         => (int)$item['@attributes']['intReqdLevel'],
                ];
            }
        }

    } else if($type=="classes") {

        $out["class"]  = [];
        $out["item"]  = [];

        if(!isset($xmlJson["character"][0])) {
            $xmlJson["character"] = [$xmlJson["character"]];
        }
        foreach($xmlJson["character"] as $class) {
            $out["class"][] = [
                "id"            =>             (int)$class['@attributes']['ClassID'],
                "name"          =>                  $class['@attributes']['strClassName'],
                "element"       =>                  $class['@attributes']['strElement'],
                "equippable"    =>                  $class['@attributes']['strEquippable'],
                "swf"           =>                  $class['@attributes']['strClassFileName'],
                "savable"       =>             (int)$class['@attributes']['intSavable'],
                "armorId"       =>  $armorId = (int)$class['@attributes']['ClassID'] * 2 + 8_000_000 - 2,
                "weaponId"      => $weaponId = (int)$class['@attributes']['ClassID'] * 2 + 8_000_001 - 2,
            ];

            $out["item"][] = [
                "id"            =>      $armorId,
                "name"          =>      $class['@attributes']['strArmorName'],
                "description"   =>      $class['@attributes']['strArmorDescription'],
                "designInfo"    =>     @$class['@attributes']['strArmorDesignInfo'] ?: "",
                "resists"       =>      $class['@attributes']['strArmorResists'],
                "defenseMelee"  => (int)$class['@attributes']['intDefMelee'],
                "defensePierce" => (int)$class['@attributes']['intDefPierce'],
                "defenseMagic"  => (int)$class['@attributes']['intDefMagic'],
                "parry"         => (int)$class['@attributes']['intParry'],
                "dodge"         => (int)$class['@attributes']['intDodge'],
                "block"         => (int)$class['@attributes']['intBlock'],
            ];

            $out["item"][] = [
                "id"            =>      $weaponId,
                "name"          =>      $class['@attributes']['strWeaponName'],
                "description"   =>      $class['@attributes']['strWeaponDescription'],
                "designInfo"    =>     @$class['@attributes']['strWeaponDesignInfo'] ?: "",
                "resists"       =>      $class['@attributes']['strWeaponResists'],
                "level"         => (int)$class['@attributes']['intWeaponLevel'],
                "icon"          =>      $class['@attributes']['strWeaponIcon'],
                "type"          =>\trim($class['@attributes']['strType']),
                "itemType"      =>      $class['@attributes']['strItemType'],
                "critical"      => (int)$class['@attributes']['intCrit'],
                "damageMin"     => (int)$class['@attributes']['intDmgMin'],
                "damageMax"     => (int)$class['@attributes']['intDmgMax'],
                "bonus"         => (int)$class['@attributes']['intBonus'],
            ];
        }

    } else {
        throw new \Exception("Unknown type: {$type}");
    }

    return $out;
}