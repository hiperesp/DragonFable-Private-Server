<?php
// saveMode can be "merged" or "individual".
// - "merged" will save all data in a single file.
// - "individual" will save each data in a separate file.
// both modes will check if the data already exists and will not save it if it's the same.
// if the data is different, it will throw an exception.
$saveMode = 'merged';


$xsd = [
    "quest" => [
        "jsonKey" => "quest",
        "type" => "single",
        "config" => [
            "id"                => [ "type" => "int"   , 'from' => 'QuestID'                 , ],
            "name"              => [ "type" => "string", 'from' => 'strName'                 , ],
            "description"       => [ "type" => "string", 'from' => 'strDescription'          , ],
            "complete"          => [ "type" => "string", 'from' => 'strComplete'             , ],
            "swf"               => [ "type" => "string", 'from' => 'strFileName'             , ],
            "swfX"              => [ "type" => "string", 'from' => 'strXFileName'            , ],
            "maxSilver"         => [ "type" => "int"   , 'from' => 'intMaxSilver'            , ],
            "maxGold"           => [ "type" => "int"   , 'from' => 'intMaxGold'              , ],
            "maxGems"           => [ "type" => "int"   , 'from' => 'intMaxGems'              , ],
            "maxExp"            => [ "type" => "int"   , 'from' => 'intMaxExp'               , ],
            "minTime"           => [ "type" => "int"   , 'from' => 'intMinTime'              , ],
            "counter"           => [ "type" => "int"   , 'from' => 'intCounter'              , ],
            "extra"             => [ "type" => "string", 'from' => 'strExtra'                , ],
            "dailyIndex"        => [ "type" => "int"   , 'from' => 'intDailyIndex'           , ],
            "dailyReward"       => [ "type" => "int"   , 'from' => 'intDailyReward'          , ],
            "monsterMinLevel"   => [ "type" => "int"   , 'from' => 'intMonsterMinLevel'      , ],
            "monsterMaxLevel"   => [ "type" => "int"   , 'from' => 'intMonsterMaxLevel'      , ],
            "monsterType"       => [ "type" => "string", 'from' => 'strMonsterType'          , ],
            "monsterGroupSwf"   => [ "type" => "string", 'from' => 'strMonsterGroupFileName' , ],
        ],
        "children" => [
            "monsters" => [
                "jsonKey" => "monster",
                "type" => "multiple",
                "config" => [
                    "id"            => [ "type" => "int"   , 'from' => 'MonsterID'         , ],
                    "name"          => [ "type" => "string", 'from' => 'strCharacterName'  , ],
                    "level"         => [ "type" => "int"   , 'from' => 'intLevel'          , ],
                    "experience"    => [ "type" => "int"   , 'from' => 'intExp'            , ],
                    "hitPoints"     => [ "type" => "int"   , 'from' => 'intHP'             , ],
                    "manaPoints"    => [ "type" => "int"   , 'from' => 'intMP'             , ],
                    "silver"        => [ "type" => "int"   , 'from' => 'intSilver'         , ],
                    "gold"          => [ "type" => "int"   , 'from' => 'intGold'           , ],
                    "gems"          => [ "type" => "int"   , 'from' => 'intGems'           , ],
                    "coins"         => [ "type" => "int"   , 'from' => 'intDragonCoins'    , ],
                    "gender"        => [ "type" => "string", 'from' => 'strGender'         , ],
                    "hairStyle"     => [ "type" => "int"   , 'from' => 'intHairStyle'      , ],
                    "colorHair"     => [ "type" => "string", 'from' => 'intColorHair'      , 'parseMethod' => 'parseColor', ],
                    "colorSkin"     => [ "type" => "string", 'from' => 'intColorSkin'      , 'parseMethod' => 'parseColor', ],
                    "colorBase"     => [ "type" => "string", 'from' => 'intColorBase'      , 'parseMethod' => 'parseColor', ],
                    "colorTrim"     => [ "type" => "string", 'from' => 'intColorTrim'      , 'parseMethod' => 'parseColor', ],
                    "strength"      => [ "type" => "int"   , 'from' => 'intStr'            , ],
                    "dexterity"     => [ "type" => "int"   , 'from' => 'intDex'            , ],
                    "intelligence"  => [ "type" => "int"   , 'from' => 'intInt'            , ],
                    "luck"          => [ "type" => "int"   , 'from' => 'intLuk'            , ],
                    "charisma"      => [ "type" => "int"   , 'from' => 'intCha'            , ],
                    "endurance"     => [ "type" => "int"   , 'from' => 'intEnd'            , ],
                    "wisdom"        => [ "type" => "int"   , 'from' => 'intWis'            , ],
                    "element"       => [ "type" => "string", 'from' => 'strElement'        , ],
                    "raceId"        => [ "type" => "int"   , 'from' => 'RaceID'            , ],
                    "movName"       => [ "type" => "string", 'from' => 'strMovName'        , ],
                    "swf"           => [ "type" => "string", 'from' => 'strMonsterFileName', ],
                    "armorId"       => [ "type" => "int"   , 'generated' => 'armor'        , ],
                    "weaponId"      => [ "type" => "int"   , 'generated' => 'weapon'       , ],
                ],
                "newChildren" => [
                    [
                        "jsonKey" => "item",
                        "type" => "single",
                        "config" => [
                            "id"            => [ "type" => "int"   , 'fromParsedParent' => "armorId" , 'parentLevel' => 0, ],
                            "name"          => [ "type" => "string", 'from' => 'strArmorName'        , ],
                            "description"   => [ "type" => "string", 'from' => 'strArmorDescription' , ],
                            "designInfo"    => [ "type" => "string", 'from' => 'strArmorDesignInfo'  , ],
                            "resists"       => [ "type" => "string", 'from' => 'strArmorResists'     , ],
                            "defenseMelee"  => [ "type" => "int"   , 'from' => 'intDefMelee'         , ],
                            "defensePierce" => [ "type" => "int"   , 'from' => 'intDefPierce'        , ],
                            "defenseMagic"  => [ "type" => "int"   , 'from' => 'intDefMagic'         , ],
                            "parry"         => [ "type" => "int"   , 'from' => 'intParry'            , ],
                            "dodge"         => [ "type" => "int"   , 'from' => 'intDodge'            , ],
                            "block"         => [ "type" => "int"   , 'from' => 'intBlock'            , ],
                            "categoryId"    => [ "type" => "int"   , 'defined' => '2' /* armor */    , ],
                        ],
                    ],
                    [
                        "jsonKey" => "item",
                        "type" => "single",
                        "config" => [
                            "id"            => [ "type" => "int"   , 'fromParsedParent' => "weaponId", 'parentLevel' => 0, ],
                            "name"          => [ "type" => "string", 'from' => 'strWeaponName'       , ],
                            "description"   => [ "type" => "string", 'from' => 'strWeaponDescription', ],
                            "designInfo"    => [ "type" => "string", 'from' => 'strWeaponDesignInfo' , ],
                            "resists"       => [ "type" => "string", 'from' => 'strWeaponResists'    , ],
                            "level"         => [ "type" => "int"   , 'defined' => '0'                , ],
                            "icon"          => [ "type" => "string", 'defined' => ''                 , ],
                            "type"          => [ "type" => "string", 'from' => 'strType'             , ],
                            "itemType"      => [ "type" => "string", 'defined' => ''                 , ],
                            "critical"      => [ "type" => "int"   , 'from' => 'intCrit'             , ],
                            "damageMin"     => [ "type" => "int"   , 'from' => 'intDmgMin'           , ],
                            "damageMax"     => [ "type" => "int"   , 'from' => 'intDmgMax'           , ],
                            "bonus"         => [ "type" => "int"   , 'from' => 'intBonus'            , ],
                            "swf"           => [ "type" => "string", 'from' => 'strWeaponFile'       , ],
                            "categoryId"    => [ "type" => "int"   , 'defined' => '1' /* weapon */   , ],
                        ],
                    ],
                    [
                        "jsonKey" => "quest_monster",
                        "type" => "single",
                        "config" => [
                            "id"           => [ "type" => "int", 'generated' => "quest_monster" , ],
                            "questId"      => [ "type" => "int", 'fromParsedParent' => 'id'     , 'parentLevel' => 1, ],
                            "monsterId"    => [ "type" => "int", 'from' => 'MonsterID'          , 'parentLevel' => 0, ],
                        ]
                    ],
                    [
                        "jsonKey" => "race",
                        "type" => "single",
                        "config" => [
                            "id"      => [ "type" => "int"   , 'from' => 'RaceID'        , ],
                            "name"    => [ "type" => "string", 'from' => 'strRaceName'   , ],
                            "resists" => [ "type" => "string", 'from' => 'strRaceResists', "default" => "" ],
                        ],
                    ]
                ]
            ]
        ]
    ],
];


function generatedIds(string $type, array $parents): int {
    if(\in_array($type, $itemLogic = [ 'weapon', 'armor', 'pet', 'item' ])) {
        $monsterId = $parents[0]['parsed']['id'];
        $itemCategoryId = \array_search($type, $itemLogic);
        $length = \count($itemLogic);
        return 9900000 + $monsterId * $length + $itemCategoryId;
    }
    if($type === 'quest_monster') {
        static $questMonsterId = 0;
        return ++$questMonsterId;
    }
    throw new \Exception("Generated ID not found: {$type}");
};


// $folders = \scandir('downloaded');
$folders = [ 'quest' ];
foreach ($folders as $folder) {
    if ($folder === '.' || $folder === '..') {
        continue;
    }

    $files = \scandir("downloaded/{$folder}");
    \usort($files, function($a, $b) {
        return \strnatcasecmp($a, $b);
    });
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        convert($folder, $file);
    }
}

function convert(string $folder, string $file): void {
    global $xsd, $saveMode;

    $xmlStr = \file_get_contents("downloaded/{$folder}/{$file}");
    $xmlStr = \trim($xmlStr);
    $xmlStr = \preg_replace('/\r?\n/', "HIPERESP-NEWLINE", $xmlStr);
    $xmlStr = \str_replace('>HIPERESP-NEWLINE<', ">\n<", $xmlStr);
    $xml = \simplexml_load_string($xmlStr);
    $json = \json_decode(\json_encode($xml), true);
    $json = normalizeJsonNewLine($json);

    $json = convertJson($json, $xsd);

    foreach($json as $newFolder => $newData) {
        $newDir = "converted/{$newFolder}";
        if (!\is_dir($newDir)) {
            \mkdir($newDir, 0777, true);
        }
        foreach($newData as $newJson) {
            if(!isset($newJson['id'])) {
                throw new \Exception("ID not found");
            }

            if($saveMode=="individual") {
                $dataToSave = \json_encode($newJson, JSON_PRETTY_PRINT);
                if(\file_exists("{$newDir}/{$newJson['id']}.json")) {
                    $currentFileData = \file_get_contents("{$newDir}/{$newJson['id']}.json");
                    if($dataToSave === $currentFileData) {
                        continue;
                    }
                    throw new \Exception("File already exists with different data: {$newDir}/{$newJson['id']}.json\nCurrent data:{$currentFileData}\nNew data    :{$dataToSave}");
                }
                \file_put_contents("{$newDir}/{$newJson['id']}.json", $dataToSave);
            } else if($saveMode=="merged") {
                if(!\file_exists("{$newDir}/merged.json")) {
                    \file_put_contents("{$newDir}/merged.json", "[]");
                }
                $currentData = \json_decode(\file_get_contents("{$newDir}/merged.json"), true);
                foreach($currentData as $currentDataKey => $currentDataItem) {
                    if($currentDataItem['id'] === $newJson['id']) {
                        if(\json_encode($currentDataItem) === \json_encode($newJson)) {
                            continue 2;
                        }
                        throw new \Exception("File already exists with different data: {$newDir}/merged.json\nCurrent data:".\json_encode($currentDataItem)."\nNew data    :".\json_encode($newJson));
                    }
                }
                $currentData[] = $newJson;

                \usort($currentData, function(array $a, array $b): int {
                    return \strnatcasecmp($a['id'], $b['id']);
                });

                \file_put_contents("{$newDir}/merged.json", \json_encode($currentData, JSON_PRETTY_PRINT));
            } else {
                throw new \Exception("Save mode not supported: {$saveMode}");
            }
        }
    }
}

function convertJson(array $json, array $xsd, array $parents = []): array {
    $newJson = [];
    foreach($json as $jsonKey => $jsonItemMult) {
        if($jsonKey=='@attributes') continue;
        if(!isset($xsd[$jsonKey])) {
            throw new \Exception("Key not found in XSD: {$jsonKey}");
        }
        $xsdItem = $xsd[$jsonKey];
        $newJsonKey = $xsd[$jsonKey]['jsonKey'];
        if(!isset($newJson[$newJsonKey])) {
            $newJson[$newJsonKey] = [];
        }
        if($xsdItem['type'] === 'single') {
            if(!isset($jsonItemMult['@attributes'])) {
                throw new \Exception("Multiple data found in single xsd item: {$jsonKey}");
            }
            $jsonItemMult = [ $jsonItemMult ];
        } else {
            if(isset($jsonItemMult['@attributes'])) {
                $jsonItemMult = [ $jsonItemMult ];
            }
        }
        foreach($jsonItemMult as $jsonItem) {
            $newJsonItem = convertJsonFromConfig($jsonItem, $xsdItem, $parents);
            $newJson[$newJsonKey][] = $newJsonItem;

            if(isset($xsdItem['children'])) {
                $newDataAppend = convertJson($jsonItem, $xsdItem['children'], createParents($newJsonItem, $jsonItem, $parents));
                foreach($newDataAppend as $newFolder => $newData) {
                    if(!isset($newJson[$newFolder])) {
                        $newJson[$newFolder] = [];
                    }
                    $newJson[$newFolder] = \array_merge($newJson[$newFolder], $newData);
                }
            }

            if(isset($xsdItem['newChildren'])) {
                foreach($xsdItem['newChildren'] as $newChild) {
                    $newChildJsonKey = $newChild['jsonKey'];
                    if(!isset($newJson[$newChildJsonKey])) {
                        $newJson[$newChildJsonKey] = [];
                    }
                    $newJson[$newChildJsonKey][] = convertJsonFromConfig($jsonItem, $newChild, createParents($newJsonItem, $jsonItem, $parents));
                }
            }
        }
    }

    return $newJson;
}

function convertJsonFromConfig(array $jsonItem, array $xsdItem, array $parents = []): array {
    $newJsonItem = [];
    foreach($xsdItem['config'] as $key => $config) {
        if(isset($config['generated'])) {
            $newJsonItem[$key] = null;
        } else if(isset($config['from'])) {
            if(!isset($jsonItem['@attributes'][$config['from']])) {
                if(!isset($config['default'])) {
                    throw new \Exception("Attribute not found from XSD config: {$config['from']}, data: ".\json_encode($jsonItem['@attributes']));
                }
                $jsonItem['@attributes'][$config['from']] = $config['default'];
            }
            $newJsonItem[$key] = $jsonItem['@attributes'][$config['from']];
        } else if(isset($config['fromParsedParent'])) {
            if(!isset($config['parentLevel'])) {
                throw new \Exception("Parent level not found in XSD config: {$key}");
            }
            $newJsonItem[$key] = $parents[$config['parentLevel']]['parsed'][$config['fromParsedParent']];
        } else if(isset($config['fromRawParent'])) {
            if(!isset($config['parentLevel'])) {
                throw new \Exception("Parent level not found in XSD config: {$key}");
            }
            $newJsonItem[$key] = $parents[$config['parentLevel']]['raw']['@attributes'][$config['fromRawParent']];
        } else if(isset($config['defined'])) {
            $newJsonItem[$key] = $config['defined'];
        } else {
            throw new \Exception("No data provider provided in XSD config: {$key}");
        }
        if(isset($config['parseMethod'])) {
            $newJsonItem[$key] = $config['parseMethod']($newJsonItem[$key]);
        }
        if(!isset($config['type'])) {
            throw new \Exception("Type not found in XSD config: {$key}");
        }
        if($config['type'] === 'int') {
            $newJsonItem[$key] = (int)$newJsonItem[$key];
        } else if($config['type'] === 'string') {
            $newJsonItem[$key] = (string)$newJsonItem[$key];
        } else {
            throw new \Exception("Type not supported in XSD config: {$config['type']}");
        }
    }
    foreach($xsdItem['config'] as $key => $config) {
        if(isset($config['generated'])) {
            $newJsonItem[$key] = generatedIds($config['generated'], createParents($newJsonItem, $jsonItem, $parents));
        }
        if($config['type'] === 'int') {
            $newJsonItem[$key] = (int)$newJsonItem[$key];
        } else if($config['type'] === 'string') {
            $newJsonItem[$key] = (string)$newJsonItem[$key];
        } else {
            throw new \Exception("Type not supported in XSD config: {$config['type']}");
        }
    }
    return $newJsonItem;
}

function createParents(array $newJsonItem, array $jsonItem, array $parents): array {
    return \array_merge([
        [
            'parsed' => $newJsonItem,
            'raw' => $jsonItem,
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
    $color = \str_pad($color, 6, '0', STR_PAD_LEFT);
    $color = \substr($color, 0, 6); // max 6 char
    return $color;
}