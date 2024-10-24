#!/usr/bin/env php
<?php
// saveMode can be "merged" or "individual".
// - "merged" will save all data in a single file.
// - "individual" will save each data in a separate file.
// both modes will check if the data already exists and will not save it if it's the same.
// if the data is different, it will throw an exception.
$saveMode = 'merged';


$xsd = [
    "quest" => [ // from quest dir
        "jsonKey" => "quest",
        "type" => "single",
        "ignoreParams" => [
            "intCounter", // not convert counter because it has some random values every time, idk where it is used and if it is important
        ],
        "config" => [
            "id"                => [ "type" => "int"   , "from" => "QuestID"                 , ],
            "name"              => [ "type" => "string", "from" => "strName"                 , ],
            "description"       => [ "type" => "string", "from" => "strDescription"          , ],
            "complete"          => [ "type" => "string", "from" => "strComplete"             , ],
            "swf"               => [ "type" => "string", "from" => "strFileName"             , ],
            "swfX"              => [ "type" => "string", "from" => "strXFileName"            , ],
            "maxSilver"         => [ "type" => "int"   , "from" => "intMaxSilver"            , ],
            "maxGold"           => [ "type" => "int"   , "from" => "intMaxGold"              , ],
            "maxGems"           => [ "type" => "int"   , "from" => "intMaxGems"              , ],
            "maxExp"            => [ "type" => "int"   , "from" => "intMaxExp"               , ],
            "minTime"           => [ "type" => "int"   , "from" => "intMinTime"              , ],
            // "counter"           => [ "type" => "int"   , "from" => "intCounter"              , ],
            "counter"           => [ "type" => "int"   , "defined"     => "0"                , ],
            "extra"             => [ "type" => "string", "from" => "strExtra"                , ],
            "dailyIndex"        => [ "type" => "int"   , "from" => "intDailyIndex"           , ],
            "dailyReward"       => [ "type" => "int"   , "from" => "intDailyReward"          , ],
            "monsterMinLevel"   => [ "type" => "int"   , "from" => "intMonsterMinLevel"      , ],
            "monsterMaxLevel"   => [ "type" => "int"   , "from" => "intMonsterMaxLevel"      , ],
            "monsterType"       => [ "type" => "string", "from" => "strMonsterType"          , ],
            "monsterGroupSwf"   => [ "type" => "string", "from" => "strMonsterGroupFileName" , ],
        ],
        "children" => [
            "monsters" => [
                "jsonKey" => "monster",
                "type" => "multiple",
                "ignoreParams" => [
                    "intMonsterRef", // not save it, because this is the index of the monster in the quest and we calculate based on id
                ],
                "config" => [
                    "id"            => [ "type" => "int"   , "from" => "MonsterID"         , ],
                    "name"          => [ "type" => "string", "from" => "strCharacterName"  , ],
                    "level"         => [ "type" => "int"   , "from" => "intLevel"          , ],
                    "experience"    => [ "type" => "int"   , "from" => "intExp"            , ],
                    "hitPoints"     => [ "type" => "int"   , "from" => "intHP"             , ],
                    "manaPoints"    => [ "type" => "int"   , "from" => "intMP"             , ],
                    "silver"        => [ "type" => "int"   , "from" => "intSilver"         , ],
                    "gold"          => [ "type" => "int"   , "from" => "intGold"           , ],
                    "gems"          => [ "type" => "int"   , "from" => "intGems"           , ],
                    "coins"         => [ "type" => "int"   , "from" => "intDragonCoins"    , ],
                    "gender"        => [ "type" => "string", "from" => "strGender"         , ],
                    "hairStyle"     => [ "type" => "int"   , "from" => "intHairStyle"      , ],
                    "colorHair"     => [ "type" => "string", "from" => "intColorHair"      , "parseMethod" => "parseColor", ],
                    "colorSkin"     => [ "type" => "string", "from" => "intColorSkin"      , "parseMethod" => "parseColor", ],
                    "colorBase"     => [ "type" => "string", "from" => "intColorBase"      , "parseMethod" => "parseColor", ],
                    "colorTrim"     => [ "type" => "string", "from" => "intColorTrim"      , "parseMethod" => "parseColor", ],
                    "strength"      => [ "type" => "int"   , "from" => "intStr"            , ],
                    "dexterity"     => [ "type" => "int"   , "from" => "intDex"            , ],
                    "intelligence"  => [ "type" => "int"   , "from" => "intInt"            , ],
                    "luck"          => [ "type" => "int"   , "from" => "intLuk"            , ],
                    "charisma"      => [ "type" => "int"   , "from" => "intCha"            , ],
                    "endurance"     => [ "type" => "int"   , "from" => "intEnd"            , ],
                    "wisdom"        => [ "type" => "int"   , "from" => "intWis"            , ],
                    "element"       => [ "type" => "string", "from" => "strElement"        , ],
                    "raceId"        => [ "type" => "int"   , "from" => "RaceID"            , ],
                    "movName"       => [ "type" => "string", "from" => "strMovName"        , ],
                    "swf"           => [ "type" => "string", "from" => "strMonsterFileName", ],
                    "armorId"       => [ "type" => "int"   , "generated" => "armor"        , ],
                    "weaponId"      => [ "type" => "int"   , "generated" => "weapon"       , ],
                ],
                "newChildren" => [
                    [
                        "jsonKey" => "item",
                        "type" => "single",
                        "config" => [
                            "id"            => [ "type" => "int"   , "fromParsedParent" => "armorId" , "parentLevel" => 0, ],
                            "name"          => [ "type" => "string", "from" => "strArmorName"        , ],
                            "description"   => [ "type" => "string", "from" => "strArmorDescription" , ],
                            "designInfo"    => [ "type" => "string", "from" => "strArmorDesignInfo"  , ],
                            "resists"       => [ "type" => "string", "from" => "strArmorResists"     , ],
                            "defenseMelee"  => [ "type" => "int"   , "from" => "intDefMelee"         , ],
                            "defensePierce" => [ "type" => "int"   , "from" => "intDefPierce"        , ],
                            "defenseMagic"  => [ "type" => "int"   , "from" => "intDefMagic"         , ],
                            "parry"         => [ "type" => "int"   , "from" => "intParry"            , ],
                            "dodge"         => [ "type" => "int"   , "from" => "intDodge"            , ],
                            "block"         => [ "type" => "int"   , "from" => "intBlock"            , ],
                            "categoryId"    => [ "type" => "int"   , "defined" => "2" /* armor */    , ],
                        ],
                    ],
                    [
                        "jsonKey" => "item",
                        "type" => "single",
                        "config" => [
                            "id"            => [ "type" => "int"   , "fromParsedParent" => "weaponId", "parentLevel" => 0, ],
                            "name"          => [ "type" => "string", "from" => "strWeaponName"       , ],
                            "description"   => [ "type" => "string", "from" => "strWeaponDescription", ],
                            "designInfo"    => [ "type" => "string", "from" => "strWeaponDesignInfo" , ],
                            "resists"       => [ "type" => "string", "from" => "strWeaponResists"    , ],
                            "level"         => [ "type" => "int"   , "defined" => "0"                , ],
                            "icon"          => [ "type" => "string", "defined" => ""                 , ],
                            "type"          => [ "type" => "string", "from" => "strType"             , ],
                            "itemType"      => [ "type" => "string", "defined" => ""                 , ],
                            "critical"      => [ "type" => "int"   , "from" => "intCrit"             , ],
                            "damageMin"     => [ "type" => "int"   , "from" => "intDmgMin"           , ],
                            "damageMax"     => [ "type" => "int"   , "from" => "intDmgMax"           , ],
                            "bonus"         => [ "type" => "int"   , "from" => "intBonus"            , ],
                            "swf"           => [ "type" => "string", "from" => "strWeaponFile"       , ],
                            "categoryId"    => [ "type" => "int"   , "defined" => "1" /* weapon */   , ],
                        ],
                    ],
                    [
                        "jsonKey" => "quest_monster",
                        "type" => "single",
                        "config" => [
                            "id"           => [ "type" => "int", "generated" => "quest_monster" , ],
                            "questId"      => [ "type" => "int", "fromParsedParent" => "id"     , "parentLevel" => 1, ],
                            "monsterId"    => [ "type" => "int", "from" => "MonsterID"          , "parentLevel" => 0, ],
                        ]
                    ],
                    [
                        "jsonKey" => "race",
                        "type" => "single",
                        "config" => [
                            "id"      => [ "type" => "int"   , "from" => "RaceID"        , ],
                            "name"    => [ "type" => "string", "from" => "strRaceName"   , ],
                            "resists" => [ "type" => "string", "from" => "strRaceResists", "default" => "" ],
                        ],
                    ]
                ]
            ]
        ]
    ],
    "newTown" => [ // from town dir
        "jsonKey" => "quest",
        "type" => "single",
        "config" => [
            "id"                => [ "type" => "int"   , "fromSpecial" => "idFromFileName"   , ],
            "name"              => [ "type" => "string", "defined"     => ""                 , ],
            "description"       => [ "type" => "string", "defined"     => ""                 , ],
            "complete"          => [ "type" => "string", "defined"     => ""                 , ],
            "swf"               => [ "type" => "string", "from"        => "strQuestFileName" , ],
            "swfX"              => [ "type" => "string", "from"        => "strQuestXFileName", ],
            "maxSilver"         => [ "type" => "int"   , "defined"     => "0"                , ],
            "maxGold"           => [ "type" => "int"   , "defined"     => "0"                , ],
            "maxGems"           => [ "type" => "int"   , "defined"     => "0"                , ],
            "maxExp"            => [ "type" => "int"   , "defined"     => "0"                , ],
            "minTime"           => [ "type" => "int"   , "defined"     => "0"                , ],
            "counter"           => [ "type" => "int"   , "defined"     => "0"                , ],
            "extra"             => [ "type" => "string", "from"        => "strExtra"         , ],
            "dailyIndex"        => [ "type" => "int"   , "defined"     => "0"                , ],
            "dailyReward"       => [ "type" => "int"   , "defined"     => "0"                , ],
            "monsterMinLevel"   => [ "type" => "int"   , "defined"     => "0"                , ],
            "monsterMaxLevel"   => [ "type" => "int"   , "defined"     => "0"                , ],
            "monsterType"       => [ "type" => "string", "defined"     => ""                 , ],
            "monsterGroupSwf"   => [ "type" => "string", "defined"     => ""                 , ],
        ],
    ],
    "character" => [ // from class dir
        "jsonKey" => "class",
        "type" => "single",
        "config" => [
            "id"            => [ "type" => "int"   , "from" => "ClassID"          , ],
            "name"          => [ "type" => "string", "from" => "strClassName"     , ],
            "element"       => [ "type" => "string", "from" => "strElement"       , ],
            "equippable"    => [ "type" => "string", "from" => "strEquippable"    , ],
            "swf"           => [ "type" => "string", "from" => "strClassFileName" , ],
            "savable"       => [ "type" => "int"   , "from" => "intSavable"       , ],
            "armorId"       => [ "type" => "int"   , "generated" => "class_armor" , ],
            "weaponId"      => [ "type" => "int"   , "generated" => "class_weapon", ],
        ],
        "newChildren" => [
            [
                "jsonKey" => "item",
                "type" => "single",
                "config" => [
                    "id"            => [ "type" => "int"   , "fromParsedParent" => "armorId" , "parentLevel" => 0, ],
                    "name"          => [ "type" => "string", "from" => "strArmorName"        , ],
                    "description"   => [ "type" => "string", "from" => "strArmorDescription" , ],
                    "designInfo"    => [ "type" => "string", "defined" => ""                 , ],
                    "resists"       => [ "type" => "string", "from" => "strArmorResists"     , ],
                    "defenseMelee"  => [ "type" => "int"   , "from" => "intDefMelee"         , ],
                    "defensePierce" => [ "type" => "int"   , "from" => "intDefPierce"        , ],
                    "defenseMagic"  => [ "type" => "int"   , "from" => "intDefMagic"         , ],
                    "parry"         => [ "type" => "int"   , "from" => "intParry"            , ],
                    "dodge"         => [ "type" => "int"   , "from" => "intDodge"            , ],
                    "block"         => [ "type" => "int"   , "from" => "intBlock"            , ],
                    "categoryId"    => [ "type" => "int"   , "defined" => "2" /* armor */    , ],
                ],
            ],
            [
                "jsonKey" => "item",
                "type" => "single",
                "config" => [
                    "id"            => [ "type" => "int"   , "fromParsedParent" => "weaponId", "parentLevel" => 0, ],
                    "name"          => [ "type" => "string", "from" => "strWeaponName"       , ],
                    "description"   => [ "type" => "string", "from" => "strWeaponDescription", ],
                    "designInfo"    => [ "type" => "string", "from" => "strWeaponDesignInfo" , "default" => "" ],
                    "resists"       => [ "type" => "string", "from" => "strWeaponResists"    , ],
                    "level"         => [ "type" => "int"   , "from" => "intWeaponLevel"      , ],
                    "icon"          => [ "type" => "string", "from" => "strWeaponIcon"       , ],
                    "type"          => [ "type" => "string", "from" => "strType"             , ],
                    "itemType"      => [ "type" => "string", "from" => "strItemType"         , ],
                    "critical"      => [ "type" => "int"   , "from" => "intCrit"             , ],
                    "damageMin"     => [ "type" => "int"   , "from" => "intDmgMin"           , ],
                    "damageMax"     => [ "type" => "int"   , "from" => "intDmgMax"           , ],
                    "bonus"         => [ "type" => "int"   , "from" => "intBonus"            , ],
                    "swf"           => [ "type" => "string", "defined" => ""                 , ],
                    "categoryId"    => [ "type" => "int"   , "defined" => "1" /* weapon */   , ],
                    "name"          => [ "type" => "string", "from" => "strWeaponName"       , ],
                ],
            ],
        ]
    ],
    "intrface" => [
        "jsonKey" => "interface",
        "type" => "single",
        "config" => [
            "id"        => [ "type" => "int"   , "from" => "InterfaceID" , ],
            "name"      => [ "type" => "string", "from" => "strName"     , ],
            "swf"       => [ "type" => "string", "from" => "strFileName" , ],
            "loadUnder" => [ "type" => "int",    "from" => "bitLoadUnder", ],
        ],
    ],
];

$merges = [
    "quest" => function(array $quest1, array $quest2): array {
        $keys = \array_keys($quest1);
        if($keys !== \array_keys($quest2)) {
            throw new \Exception("Data already exists with different keys");
        }

        $quest3 = \array_combine($keys, \array_map(function(string $keyName, $value1, $value2) {
            if($value1===$value2) return $value1;

            return match($keyName) {
                "counter" => 0,
                default => $value1 ?: $value2,
            };
        }, $keys, $quest1, $quest2));

        return $quest3;
    },
    "default" => function(array $data1, array $data2): array {
        $keys = \array_keys($data1);
        if($keys !== \array_keys($data2)) {
            throw new \Exception("File already exists with different KEYS");
        }

        return \array_combine($keys, \array_map(function(string $keyName, $value1, $value2) {
            if($value1===$value2) return $value1;
            throw new \Exception("File already exists with different VALUES");
        }, $keys, $data1, $data2));
    },
];


// convertAll(\array_filter(\scandir("downloaded"), function(string $folder) {
//     return $folder !== "." && $folder !== "..";
// }));
convertAll([
    // "quest",
    // "town",
    // "class",
    "interface",
    // "shop",
    // "mergeShop",
    // "houseShop",
    // "houseItemShop",
]);

function convertAll(array $folders) {
    $totalFolders = \count($folders);
    $maxProgressPerFolder = 1 / $totalFolders;

    foreach ($folders as $i => $folder) {

        $files = \scandir("downloaded/{$folder}");
        \usort($files, function(string $a, string $b) {
            return \strnatcasecmp($a, $b);
        });
        $totalFiles = \count($files);

        foreach ($files as $i2 => $file) {
            if ($file === "." || $file === "..") {
                continue;
            }

            $percent = (\number_format($maxProgressPerFolder * $i + ($i2 - 2) / ($totalFiles - 2) * $maxProgressPerFolder, 5) * 100)."%";
            echo "[0] Converting {$folder}/{$file} ({$percent})\n";

            convertFile($folder, $file);
        }
    }
}

function generatedIds(string $type, array $parents): int {
    if(\in_array($type, $itemLogic = [ "weapon", "armor", "pet", "item" ])) {
        $monsterId = $parents[0]["parsed"]["id"];
        $itemCategoryId = \array_search($type, $itemLogic);
        $length = \count($itemLogic);
        return 9900000 + $monsterId * $length + $itemCategoryId;
    }
    if(\in_array($type, $itemLogic = [ "class_weapon", "class_armor", "class_pet", "class_item" ])) {
        $classId = $parents[0]["parsed"]["id"];
        $itemCategoryId = \array_search($type, $itemLogic);
        $length = \count($itemLogic);
        return 9800000 + $classId * $length + $itemCategoryId;
    }
    if($type === "quest_monster") {
        static $questMonsterId = 0;
        return ++$questMonsterId;
    }
    throw new \Exception("Generated ID not found: {$type}");
};

function convertFile(string $folder, string $fileName): void {
    global $saveMode;
    global $xsd, $merges;

    $xmlStr = \file_get_contents("downloaded/{$folder}/{$fileName}");
    $xmlStr = \preg_replace('/\r?\n/', "HIPERESP-NEWLINE", \trim($xmlStr));
    $xmlStr = \str_replace(">HIPERESP-NEWLINE<", ">\n<", $xmlStr);
    $xml = \simplexml_load_string($xmlStr);
    $json = \json_decode(\json_encode($xml), true);
    $json = normalizeJsonNewLine($json);

    $json = convertToJson($fileName, $json, $xsd);

    foreach($json as $newFolder => $newData) {
        $newDir = "converted/{$newFolder}";
        if (!\is_dir($newDir)) {
            \mkdir($newDir, 0777, true);
        }
        foreach($newData as $newJson) {
            if(!isset($newJson["id"])) {
                throw new \Exception("ID not found");
            }

            if($saveMode=="individual") {
                if(\file_exists("{$newDir}/{$newJson["id"]}.json")) {
                    $currentFileData = \file_get_contents("{$newDir}/{$newJson["id"]}.json");
                    if($currentFileData === \json_encode([$newJson], JSON_PRETTY_PRINT)) {
                        continue;
                    }
                    try {
                        $newJson = $merges["default"](\json_decode($currentFileData, true)[0], $newJson);
                    } catch(\Exception $e) {
                        if(!isset($merges[$newFolder])) {
                            throw new \Exception("{$e->getMessage()}: {$newDir}/{$newJson["id"]}.json\nCurrent data:{$currentFileData}\nNew data    :".\json_encode($newJson, JSON_PRETTY_PRINT));
                        }
                        $newJson = $merges[$newFolder](\json_decode($currentFileData, true)[0], $newJson);
                    }
                }
                $dataToSave = \json_encode([$newJson], JSON_PRETTY_PRINT);
                \file_put_contents("{$newDir}/{$newJson["id"]}.json", $dataToSave);
            } else if($saveMode=="merged") {
                if(!\file_exists("{$newDir}/merged.json")) {
                    \file_put_contents("{$newDir}/merged.json", "[]");
                }
                $currentData = \json_decode(\file_get_contents("{$newDir}/merged.json"), true);
                foreach($currentData as $currentDataKey => $currentDataItem) {
                    if($currentDataItem===$newJson) {
                        continue 2;
                    }
                    if($currentDataItem["id"] === $newJson["id"]) {
                        try {
                            $newJson = $merges["default"]($currentDataItem, $newJson);
                        } catch(\Exception $e) {
                            if(!isset($merges[$newFolder])) {
                                throw new \Exception("{$e->getMessage()}: {$newDir}/merged.json\nCurrent data:".\json_encode($currentDataItem)."\nNew data    :".\json_encode($newJson));
                            }
                            $newJson = $merges[$newFolder]($currentDataItem, $newJson);
                        }
                        unset($currentData[$currentDataKey]);
                        break;
                    }
                }
                $currentData[] = $newJson;

                \usort($currentData, function(array $a, array $b): int {
                    return \strnatcasecmp($a["id"], $b["id"]);
                });

                \file_put_contents("{$newDir}/merged.json", \json_encode($currentData, JSON_PRETTY_PRINT));
            } else {
                throw new \Exception("Save mode not supported: {$saveMode}");
            }
        }
    }
}

function convertToJson(string $fileName, array $json, array $xsd, array $parents = []): array {
    $newJson = [];
    foreach($json as $jsonKey => $jsonItemMult) {
        if($jsonKey=="@attributes") continue;
        if(!isset($xsd[$jsonKey])) {
            throw new \Exception("Key not found in XSD: {$jsonKey}");
        }
        $xsdItem = $xsd[$jsonKey];
        $newJsonKey = $xsd[$jsonKey]["jsonKey"];
        if(!isset($newJson[$newJsonKey])) {
            $newJson[$newJsonKey] = [];
        }
        if($xsdItem["type"] === "single") {
            if(!isset($jsonItemMult["@attributes"])) {
                throw new \Exception("Multiple data found in single xsd item: {$jsonKey}");
            }
            $jsonItemMult = [ $jsonItemMult ];
        } else {
            if(isset($jsonItemMult["@attributes"])) {
                $jsonItemMult = [ $jsonItemMult ];
            }
        }
        foreach($jsonItemMult as $jsonItem) {
            $newJsonItem = convertToJsonFromConfig($fileName, $jsonItem, $xsdItem, $parents);
            $newJson[$newJsonKey][] = $newJsonItem;

            if(isset($xsdItem["children"])) {
                $newDataAppend = convertToJson($fileName, $jsonItem, $xsdItem["children"], createParents($newJsonItem, $jsonItem, $parents));
                foreach($newDataAppend as $newFolder => $newData) {
                    if(!isset($newJson[$newFolder])) {
                        $newJson[$newFolder] = [];
                    }
                    $newJson[$newFolder] = \array_merge($newJson[$newFolder], $newData);
                }
            }

            if(isset($xsdItem["newChildren"])) {
                foreach($xsdItem["newChildren"] as $newChild) {
                    $newChildJsonKey = $newChild["jsonKey"];
                    if(!isset($newJson[$newChildJsonKey])) {
                        $newJson[$newChildJsonKey] = [];
                    }
                    $newJson[$newChildJsonKey][] = convertToJsonFromConfig($fileName, $jsonItem, $newChild, createParents($newJsonItem, $jsonItem, $parents), true);
                }
            }
        }
    }

    return $newJson;
}

function convertToJsonFromConfig(string $fileName, array $jsonItem, array $xsdItem, array $parents = [], bool $ignoreValidation = false): array {
    $newJsonItem = [];

    if(!$ignoreValidation) {
        foreach($jsonItem as $key => $value) {
            if($key === "@attributes") {
                foreach($value as $key2 => $value2) {
                    if(isset($xsdItem["ignoreParams"])) {
                        if(\in_array($key2, $xsdItem["ignoreParams"])) {
                            continue;
                        }
                    }
                    foreach($xsdItem["config"] as $config) {
                        if(isset($config["from"]) && $config["from"] === $key2) {
                            continue 2;
                        }
                    }
                    if(isset($xsdItem["newChildren"])) {
                        foreach($xsdItem["newChildren"] as $newChild) {
                            if(isset($newChild["config"])) {
                                foreach($newChild["config"] as $config) {
                                    if(isset($config["from"]) && $config["from"] === $key2) {
                                        continue 3;
                                    }
                                }
                            }
                        }
                    }
                    throw new \Exception("Attribute key not found in XSD config: {$key2}");
                }
                continue;
            }
            if(!isset($xsdItem["children"][$key])) {
                throw new \Exception("Child key not found in XSD config: {$key}");
            }
        }
    }

    foreach($xsdItem["config"] as $key => $config) {
        if(isset($config["generated"])) {
            $newJsonItem[$key] = null;
        } else if(isset($config["from"])) {
            if(!isset($jsonItem["@attributes"][$config["from"]])) {
                if(!isset($config["default"])) {
                    throw new \Exception("Attribute not found from XSD config: {$config["from"]}, data: ".\json_encode($jsonItem["@attributes"]));
                }
                $jsonItem["@attributes"][$config["from"]] = $config["default"];
            }
            $newJsonItem[$key] = $jsonItem["@attributes"][$config["from"]];
        } else if(isset($config["fromParsedParent"])) {
            if(!isset($config["parentLevel"])) {
                throw new \Exception("Parent level not found in XSD config: {$key}");
            }
            $newJsonItem[$key] = $parents[$config["parentLevel"]]["parsed"][$config["fromParsedParent"]];
        } else if(isset($config["fromRawParent"])) {
            if(!isset($config["parentLevel"])) {
                throw new \Exception("Parent level not found in XSD config: {$key}");
            }
            $newJsonItem[$key] = $parents[$config["parentLevel"]]["raw"]["@attributes"][$config["fromRawParent"]];
        }else if(isset($config["fromSpecial"])) {
            if($config["fromSpecial"]=="idFromFileName") {
                $newJsonItem[$key] = (int)\str_replace(".xml", "", $fileName);
            } else {
                throw new \Exception("Invalid fromSpecial: {$config["fromSpecial"]}");
            }
        } else if(isset($config["defined"])) {
            $newJsonItem[$key] = $config["defined"];
        } else {
            throw new \Exception("No data provider provided in XSD config: {$key}");
        }
        if(isset($config["parseMethod"])) {
            $newJsonItem[$key] = $config["parseMethod"]($newJsonItem[$key]);
        }
        if(!isset($config["type"])) {
            throw new \Exception("Type not found in XSD config: {$key}");
        }
        if($config["type"] === "int") {
            $newJsonItem[$key] = (int)$newJsonItem[$key];
        } else if($config["type"] === "string") {
            $newJsonItem[$key] = (string)$newJsonItem[$key];
        } else {
            throw new \Exception("Type not supported in XSD config: {$config["type"]}");
        }
    }
    foreach($xsdItem["config"] as $key => $config) {
        if(isset($config["generated"])) {
            $newJsonItem[$key] = generatedIds($config["generated"], createParents($newJsonItem, $jsonItem, $parents));
        }
        if($config["type"] === "int") {
            $newJsonItem[$key] = (int)$newJsonItem[$key];
        } else if($config["type"] === "string") {
            $newJsonItem[$key] = (string)$newJsonItem[$key];
        } else {
            throw new \Exception("Type not supported in XSD config: {$config["type"]}");
        }
    }
    return $newJsonItem;
}

function createParents(array $newJsonItem, array $jsonItem, array $parents): array {
    return \array_merge([
        [
            "parsed" => $newJsonItem,
            "raw" => $jsonItem,
        ]
    ], $parents);
}

function normalizeJsonNewLine(array $xml): array {
    foreach($xml as $key => $value) {
        if(\is_array($value)) {
            $xml[$key] = normalizeJsonNewLine($value);
        } else if(\is_string($value)) {
            $xml[$key] = \str_replace("HIPERESP-NEWLINE", "\n", $value);
        }
    }
    return $xml;
}


function parseColor(string $color): string {
    $color = \dechex($color);
    $color = \str_pad($color, 6, "0", STR_PAD_LEFT);
    $color = \substr($color, 0, 6); // max 6 char
    return $color;
}