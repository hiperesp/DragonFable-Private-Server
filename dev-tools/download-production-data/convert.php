#!/usr/bin/env php
<?php
// saveMode can be "merged" or "individual".
// - "merged" will save all data in a single file.
// - "individual" will save each data in a separate file.
// both modes will check if the data already exists and will not save it if it's the same.
// if the data is different, it will throw an exception.
$saveMode = 'merged';
$maxMemoryUsageMB = '384';


$xsd = [
    "quest" => [
        "quest" => [
            "jsonKey" => "quest",
            "type" => "single",
            "ignoreParams" => [
                "intCounter", // not convert counter because it has some random values every time, idk where it is used and if it is important
                "intDailyReward", // not convert dailyReward because it has some random values every time (0 and 1), idk where it is used and if it is important
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
                "counter"           => [ "type" => "int"   , "defined" => "0"                    , ],
                "extra"             => [ "type" => "string", "from" => "strExtra"                , ],
                "dailyIndex"        => [ "type" => "int"   , "from" => "intDailyIndex"           , ],
                "dailyReward"       => [ "type" => "int"   , "defined" => "0"                    , ],
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
                        "id"            => [ "type" => "int"   , "from" => "MonsterID"          , ],
                        "name"          => [ "type" => "string", "from" => "strCharacterName"   , ],
                        "level"         => [ "type" => "int"   , "from" => "intLevel"           , ],
                        "experience"    => [ "type" => "int"   , "from" => "intExp"             , ],
                        "hitPoints"     => [ "type" => "int"   , "from" => "intHP"              , ],
                        "manaPoints"    => [ "type" => "int"   , "from" => "intMP"              , ],
                        "silver"        => [ "type" => "int"   , "from" => "intSilver"          , ],
                        "gold"          => [ "type" => "int"   , "from" => "intGold"            , ],
                        "gems"          => [ "type" => "int"   , "from" => "intGems"            , ],
                        "coins"         => [ "type" => "int"   , "from" => "intDragonCoins"     , ],
                        "gender"        => [ "type" => "string", "from" => "strGender"          , ],
                        "hairStyle"     => [ "type" => "int"   , "from" => "intHairStyle"       , ],
                        "colorHair"     => [ "type" => "string", "from" => "intColorHair"       , "parseMethod" => "parseColor", ],
                        "colorSkin"     => [ "type" => "string", "from" => "intColorSkin"       , "parseMethod" => "parseColor", ],
                        "colorBase"     => [ "type" => "string", "from" => "intColorBase"       , "parseMethod" => "parseColor", ],
                        "colorTrim"     => [ "type" => "string", "from" => "intColorTrim"       , "parseMethod" => "parseColor", ],
                        "strength"      => [ "type" => "int"   , "from" => "intStr"             , ],
                        "dexterity"     => [ "type" => "int"   , "from" => "intDex"             , ],
                        "intelligence"  => [ "type" => "int"   , "from" => "intInt"             , ],
                        "luck"          => [ "type" => "int"   , "from" => "intLuk"             , ],
                        "charisma"      => [ "type" => "int"   , "from" => "intCha"             , ],
                        "endurance"     => [ "type" => "int"   , "from" => "intEnd"             , ],
                        "wisdom"        => [ "type" => "int"   , "from" => "intWis"             , ],
                        "element"       => [ "type" => "string", "from" => "strElement"         , ],
                        "raceId"        => [ "type" => "int"   , "from" => "RaceID"             , ],
                        "movName"       => [ "type" => "string", "from" => "strMovName"         , ],
                        "swf"           => [ "type" => "string", "from" => "strMonsterFileName" , ],
                        "armorId"       => [ "type" => "int"   , "generated" => "monster_armor" , ],
                        "weaponId"      => [ "type" => "int"   , "generated" => "monster_weapon", ],
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
                                "monsterId"    => [ "type" => "int", "from" => "MonsterID"          , ],
                            ],
                        ],
                        [
                            "jsonKey" => "race",
                            "type" => "single",
                            "config" => [
                                "id"      => [ "type" => "int"   , "from" => "RaceID"        , ],
                                "name"    => [ "type" => "string", "from" => "strRaceName"   , ],
                                "resists" => [ "type" => "string", "from" => "strRaceResists", "default" => "" ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    "town" => [
        "newTown" => [
            "jsonKey" => "quest",
            "type" => "single",
            "config" => [
                "id"                => [ "type" => "int"   , "fromSpecial" => "idFromFileName"   , ],
                "swf"               => [ "type" => "string", "from"        => "strQuestFileName" , ],
                "swfX"              => [ "type" => "string", "from"        => "strQuestXFileName", ],
                "extra"             => [ "type" => "string", "from"        => "strExtra"         , ],
            ],
        ],
    ],
    "questRewards" => [
        "questreward" => [
            "jsonKey" => "QUEST_REWARD_CONTAINER",
            "type" => "single",
            "ignoreParams" => [ "intExp", "intSilver", "intGold", "intGems", "intCoins" ],
            "config" => [],
            "children" => [
                "items" => [
                    "jsonKey" => "item",
                    "type" => "single",
                    "ignoreParams" => [ "strCategory" ],
                    "config" => [
                        "id"            => [ "type" => "int"   , "from" => "ItemID"            , ],
                        "name"          => [ "type" => "string", "from" => "strItemName"       , ],
                        "description"   => [ "type" => "string", "from" => "strItemDescription", ],
                        "visible"       => [ "type" => "int"   , "from" => "bitVisible"        , ],
                        "destroyable"   => [ "type" => "int"   , "from" => "bitDestroyable"    , ],
                        "sellable"      => [ "type" => "int"   , "from" => "bitSellable"       , ],
                        "dragonAmulet"  => [ "type" => "int"   , "from" => "bitDragonAmulet"   , ],
                        "currency"      => [ "type" => "int"   , "from" => "intCurrency"       , ],
                        "cost"          => [ "type" => "int"   , "from" => "intCost"           , ],
                        "maxStackSize"  => [ "type" => "int"   , "from" => "intMaxStackSize"   , ],
                        "bonus"         => [ "type" => "int"   , "from" => "intBonus"          , ],
                        "rarity"        => [ "type" => "int"   , "from" => "intRarity"         , ],
                        "level"         => [ "type" => "int"   , "from" => "intLevel"          , ],
                        "type"          => [ "type" => "string", "from" => "strType"           , ],
                        "element"       => [ "type" => "string", "from" => "strElement"        , ],
                        "categoryId"    => [ "type" => "string", "generated" => "item_category", ],
                        "equipSpot"     => [ "type" => "string", "from" => "strEquipSpot"      , ],
                        "itemType"      => [ "type" => "string", "from" => "strItemType"       , ],
                        "swf"           => [ "type" => "string", "from" => "strFileName"       , ],
                        "icon"          => [ "type" => "string", "from" => "strIcon"           , ],
                        "strength"      => [ "type" => "int"   , "from" => "intStr"            , ],
                        "dexterity"     => [ "type" => "int"   , "from" => "intDex"            , ],
                        "intelligence"  => [ "type" => "int"   , "from" => "intInt"            , ],
                        "luck"          => [ "type" => "int"   , "from" => "intLuk"            , ],
                        "charisma"      => [ "type" => "int"   , "from" => "intCha"            , ],
                        "endurance"     => [ "type" => "int"   , "from" => "intEnd"            , ],
                        "wisdom"        => [ "type" => "int"   , "from" => "intWis"            , "default" => "0" ],
                        "damageMin"     => [ "type" => "int"   , "from" => "intMin"            , ],
                        "damageMax"     => [ "type" => "int"   , "from" => "intMax"            , ],
                        "defenseMelee"  => [ "type" => "int"   , "from" => "intDefMelee"       , ],
                        "defensePierce" => [ "type" => "int"   , "from" => "intDefPierce"      , ],
                        "defenseMagic"  => [ "type" => "int"   , "from" => "intDefMagic"       , ],
                        "critical"      => [ "type" => "int"   , "from" => "intCrit"           , ],
                        "parry"         => [ "type" => "int"   , "from" => "intParry"          , ],
                        "dodge"         => [ "type" => "int"   , "from" => "intDodge"          , ],
                        "block"         => [ "type" => "int"   , "from" => "intBlock"          , ],
                        "resists"       => [ "type" => "string", "from" => "strResists"        , ],
                    ],
                    "newChildren" => [
                        [
                            "jsonKey" => "quest_item",
                            "type" => "single",
                            "config" => [
                                "id"           => [ "type" => "int", "generated" => "quest_item"      , ],
                                "questId"      => [ "type" => "int", "fromSpecial" => "idFromDirName", ],
                                "itemId"       => [ "type" => "int", "from" => "ItemID"               , ],
                            ]
                        ]
                    ]
                ],
                "bank" => [
                    "jsonKey" => "bank",
                    "type" => "multiple",
                    "ignoreParams" => [ "intBankCount" ],
                    "config" => [],
                ],
            ]
        ],
    ],
    "class" => [
        "character" => [
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
            ],
        ],
    ],
    "interface" => [
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
    ],
    "shop" => [
        "shop" => [
            "jsonKey" => "itemShop",
            "type" => "single",
            "config" => [
                "id"    => [ "type" => "int"   , "from" => "ShopID"          , ],
                "name"  => [ "type" => "string", "from" => "strCharacterName", ],
                "count" => [ "type" => "int"   , "from" => "intCount"        , ],
            ],
            "children" => [
                "items" => [
                    "jsonKey" => "itemShop_item",
                    "type" => "multiple",
                    "ignoreParams" => [ "strCategory" ],
                    "config" => [
                        "id"         => [ "type" => "int", "from" => "ShopItemID"                          ],
                        "itemShopId" => [ "type" => "int", "fromParsedParent" => "id", "parentLevel" => 0, ],
                        "itemId"     => [ "type" => "int", "from" => "ItemID"                              ],
                    ],
                    "newChildren" => [
                        [
                            "jsonKey" => "item",
                            "type" => "single",
                            "config" => [
                                "id"            => [ "type" => "int"   , "from" => "ItemID"            , ],
                                "name"          => [ "type" => "string", "from" => "strItemName"       , ],
                                "description"   => [ "type" => "string", "from" => "strItemDescription", ],
                                "visible"       => [ "type" => "int"   , "from" => "bitVisible"        , ],
                                "destroyable"   => [ "type" => "int"   , "from" => "bitDestroyable"    , ],
                                "sellable"      => [ "type" => "int"   , "from" => "bitSellable"       , ],
                                "dragonAmulet"  => [ "type" => "int"   , "from" => "bitDragonAmulet"   , ],
                                "currency"      => [ "type" => "int"   , "from" => "intCurrency"       , ],
                                "cost"          => [ "type" => "int"   , "from" => "intCost"           , ],
                                "maxStackSize"  => [ "type" => "int"   , "from" => "intMaxStackSize"   , ],
                                "bonus"         => [ "type" => "int"   , "from" => "intBonus"          , ],
                                "rarity"        => [ "type" => "int"   , "from" => "intRarity"         , ],
                                "level"         => [ "type" => "int"   , "from" => "intLevel"          , ],
                                "type"          => [ "type" => "string", "from" => "strType"           , ],
                                "element"       => [ "type" => "string", "from" => "strElement"        , ],
                                "categoryId"    => [ "type" => "string", "generated" => "item_category", ],
                                "equipSpot"     => [ "type" => "string", "from" => "strEquipSpot"      , ],
                                "itemType"      => [ "type" => "string", "from" => "strItemType"       , ],
                                "swf"           => [ "type" => "string", "from" => "strFileName"       , ],
                                "icon"          => [ "type" => "string", "from" => "strIcon"           , ],
                                "strength"      => [ "type" => "int"   , "from" => "intStr"            , ],
                                "dexterity"     => [ "type" => "int"   , "from" => "intDex"            , ],
                                "intelligence"  => [ "type" => "int"   , "from" => "intInt"            , ],
                                "luck"          => [ "type" => "int"   , "from" => "intLuk"            , ],
                                "charisma"      => [ "type" => "int"   , "from" => "intCha"            , ],
                                "endurance"     => [ "type" => "int"   , "from" => "intEnd"            , ],
                                "wisdom"        => [ "type" => "int"   , "from" => "intWis"            , ],
                                "damageMin"     => [ "type" => "int"   , "from" => "intMin"            , ],
                                "damageMax"     => [ "type" => "int"   , "from" => "intMax"            , ],
                                "defenseMelee"  => [ "type" => "int"   , "from" => "intDefMelee"       , ],
                                "defensePierce" => [ "type" => "int"   , "from" => "intDefPierce"      , ],
                                "defenseMagic"  => [ "type" => "int"   , "from" => "intDefMagic"       , ],
                                "critical"      => [ "type" => "int"   , "from" => "intCrit"           , ],
                                "parry"         => [ "type" => "int"   , "from" => "intParry"          , ],
                                "dodge"         => [ "type" => "int"   , "from" => "intDodge"          , ],
                                "block"         => [ "type" => "int"   , "from" => "intBlock"          , ],
                                "resists"       => [ "type" => "string", "from" => "strResists"        , ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    "mergeShop" => [
        "mergeshop" => [
            "jsonKey" => "mergeShop",
            "type" => "single",
            "config" => [
                "id"    => [ "type" => "int"   , "from" => "MSID"   , ],
                "name"  => [ "type" => "string", "from" => "strName", ],
            ],
            "children" => [
                "items" => [
                    "jsonKey" => "mergeShop_merge",
                    "type" => "multiple",
                    "ignoreParams" => [ "strCategory" ],
                    "config" => [
                        "id"            => [ "type" => "int"   , "generated" => "mergeShop_merge"               , ],
                        "mergeShopId"   => [ "type" => "int"   , "fromParsedParent" => "id", "parentLevel" => 0, ],
                        "mergeId"       => [ "type" => "int"   , "from" => "ID"                                , ],
                    ],
                    "newChildren" => [
                        [
                            "jsonKey" => "merge",
                            "type" => "single",
                            "config" => [
                                "id"            => [ "type" => "int"   , "from" => "ID"            , ],
                                "itemId1"       => [ "type" => "int"   , "from" => "ItemID1"       , ],
                                "amount1"       => [ "type" => "int"   , "from" => "Qty1"          , ],
                                "itemId2"       => [ "type" => "int"   , "from" => "ItemID2"       , ],
                                "amount2"       => [ "type" => "int"   , "from" => "Qty2"          , ],
                                "itemId"        => [ "type" => "int"   , "from" => "NewItemID"     , ],
                                "string"        => [ "type" => "int"   , "from" => "intString"     , ], // 0: character.quests, 1: character.skills, 2: character.armor
                                "index"         => [ "type" => "int"   , "from" => "intIndex"      , ], // is param for string, like string[index]
                                "value"         => [ "type" => "int"   , "from" => "intValue"      , ], // is value for string[index], like string[index] = value
                                "level"         => [ "type" => "int"   , "from" => "intReqdLevel"  , ],
                            ],
                        ],
                        [
                            "jsonKey" => "item",
                            "type" => "single",
                            "config" => [
                                "id"            => [ "type" => "int"   , "from" => "ItemID1"                   , ],
                                "name"          => [ "type" => "string", "from" => "Item1"                     , ],
                            ],
                        ],
                        [
                            "jsonKey" => "item",
                            "type" => "single",
                            "config" => [
                                "id"            => [ "type" => "int"   , "from" => "ItemID2", "default" => "-1", ],
                                "name"          => [ "type" => "string", "from" => "Item2"  , "default" => ""  , ],
                            ],
                        ],
                        [
                            "jsonKey" => "item",
                            "type" => "single",
                            "config" => [
                                "id"            => [ "type" => "int"   , "from" => "NewItemID"         , ],
                                "name"          => [ "type" => "string", "from" => "strItemName"       , ],
                                "description"   => [ "type" => "string", "from" => "strItemDescription", ],
                                "dragonAmulet"  => [ "type" => "int"   , "from" => "bitDragonAmulet"   , ],
                                "currency"      => [ "type" => "int"   , "from" => "intCurrency"       , ],
                                "maxStackSize"  => [ "type" => "int"   , "from" => "intMaxStackSize"   , ],
                                "bonus"         => [ "type" => "int"   , "from" => "intBonus"          , ],
                                "rarity"        => [ "type" => "int"   , "from" => "intRarity"         , ],
                                "level"         => [ "type" => "int"   , "from" => "intLevel"          , ],
                                "element"       => [ "type" => "string", "from" => "strElement"        , ],
                                "categoryId"    => [ "type" => "string", "generated" => "item_category", ],
                                "equipSpot"     => [ "type" => "string", "from" => "strEquipSpot"      , ],
                                "itemType"      => [ "type" => "string", "from" => "strItemType"       , ],
                                "swf"           => [ "type" => "string", "from" => "strFileName"       , ],
                                "icon"          => [ "type" => "string", "from" => "strIcon"           , ],
                                "strength"      => [ "type" => "int"   , "from" => "intStr"            , ],
                                "dexterity"     => [ "type" => "int"   , "from" => "intDex"            , ],
                                "intelligence"  => [ "type" => "int"   , "from" => "intInt"            , ],
                                "luck"          => [ "type" => "int"   , "from" => "intLuk"            , ],
                                "charisma"      => [ "type" => "int"   , "from" => "intCha"            , ],
                                "endurance"     => [ "type" => "int"   , "from" => "intEnd"            , ],
                                "wisdom"        => [ "type" => "int"   , "from" => "intWis"            , ],
                                "damageMin"     => [ "type" => "int"   , "from" => "intMin"            , ],
                                "damageMax"     => [ "type" => "int"   , "from" => "intMax"            , ],
                                "defenseMelee"  => [ "type" => "int"   , "from" => "intDefMelee"       , ],
                                "defensePierce" => [ "type" => "int"   , "from" => "intDefPierce"      , ],
                                "defenseMagic"  => [ "type" => "int"   , "from" => "intDefMagic"       , ],
                                "critical"      => [ "type" => "int"   , "from" => "intCrit"           , ],
                                "parry"         => [ "type" => "int"   , "from" => "intParry"          , ],
                                "dodge"         => [ "type" => "int"   , "from" => "intDodge"          , ],
                                "block"         => [ "type" => "int"   , "from" => "intBlock"          , ],
                                "resists"       => [ "type" => "string", "from" => "strResists"        , ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    "hairShopF" => $hairShop = [
        "HairShop" => [
            "jsonKey" => "hairShop",
            "type" => "single",
            "config" => [
                "id"        => [ "type" => "int"   , "from" => "HairShopID"     , ],
                "name"      => [ "type" => "string", "from" => "strHairShopName", ],
                "swf"       => [ "type" => "string", "from" => "strFileName"    , "default" => "" ],
            ],
            "children" => [
                "hair" => [
                    "jsonKey" => "hair",
                    "type" => "multiple",
                    "config" => [
                        "id"        => [ "type" => "int"   , "from" => "HairID"        , ],
                        "name"      => [ "type" => "string", "from" => "strName"       , ],
                        "swf"       => [ "type" => "string", "from" => "strFileName"   , ],
                        "frame"     => [ "type" => "int"   , "from" => "intFrame"      , ],
                        "price"     => [ "type" => "int"   , "from" => "intPrice"      , ],
                        "gender"    => [ "type" => "string", "from" => "strGender"     , ],
                        "raceId"    => [ "type" => "int"   , "from" => "RaceID"        , ],
                        "earVisible"=> [ "type" => "int"   , "from" => "bitEarVisible" , ],
                    ],
                    "newChildren" => [
                        [
                            "jsonKey" => "hairShop_hair",
                            "type" => "single",
                            "config" => [
                                "id"           => [ "type" => "int", "generated" => "hairShop_hair"                , ],
                                "hairShopId"   => [ "type" => "int", "fromParsedParent" => "id", "parentLevel" => 1, ],
                                "hairId"       => [ "type" => "int", "fromParsedParent" => "id", "parentLevel" => 0, ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    "hairShopM" => $hairShop,
];

$resetFiles = [
    "hairShop_hair", // some hairs are removed from old hair shops
    "itemShop_item", // some items are removed from old shops
    "mergeShop_merge", // some merges are removed from old merge shops
    "quest_monster", // some monsters are removed from old quests, changed order or added new ones in specific order
    "quest_item", // some rewards are removed from old quests, changed order or added new ones in specific order
];

$merges = [
    "quest" => function(array $quest1, array $quest2): array {
        $keys = \array_keys($quest1);
        if($keys !== \array_keys($quest2)) {
            list($keys, $quest1, $quest2) = addMissingKeys($quest1, $quest2);
        }

        $quest3 = \array_combine($keys, \array_map(function(string $keyName, $value1, $value2) {
            return match($keyName) {
                "counter" => 0,
                default => $value2,
            };
        }, $keys, $quest1, $quest2));

        return $quest3;
    },
    "itemShop" => function(array $itemShop1, array $itemShop2): array {
        $itemShop1["count"] = $itemShop2["count"]; // always mantain the new count (idk what is this for)

        $keys = \array_keys($itemShop1);
        if($keys !== \array_keys($itemShop2)) {
            throw new \Exception("File already exists with different KEYS");
        }

        return \array_combine($keys, \array_map(function(string $keyName, mixed $value1, mixed $value2) {
            if($value1===$value2) return $value1;
            throw new \Exception("File already exists with different VALUES");
        }, $keys, $itemShop1, $itemShop2));


    },
    "item" => function(array $item1, array $item2): array {
        $keys = \array_keys($item1);
        if($keys !== \array_keys($item2)) {
            list($keys, $item1, $item2) = addMissingKeys($item1, $item2);
        }

        $item3 = \array_combine($keys, \array_map(function(string $keyName, $value1, $value2) {
            return match($keyName) {
                default => $value2, // always use the new result
            };
        }, $keys, $item1, $item2));

        return $item3;
    },
    "interface" => function(array $interface1, array $interface2): array {
        $keys = \array_keys($interface1);
        if($keys !== \array_keys($interface2)) {
            throw new \Exception("Interface already exists with different KEYS");
        }

        $interface3 = \array_combine($keys, \array_map(function(string $keyName, $value1, $value2) {
            return match($keyName) {
                default => $value2, // always use the new result
            };
        }, $keys, $interface1, $interface2));

        return $interface3;
    },
    "default" => function(array $data1, array $data2): array {
        $keys = \array_keys($data1);
        if($keys !== \array_keys($data2)) {
            throw new \Exception("File already exists with different KEYS");
        }

        return \array_combine($keys, \array_map(function(string $keyName, mixed $value1, mixed $value2) {
            if($value1===$value2) return $value1;
            throw new \Exception("File already exists with different VALUES");
        }, $keys, $data1, $data2));
    },
];

\ini_set('memory_limit', "{$maxMemoryUsageMB}M");
$startTime = (int)\microtime(true);

convertAll([
    "town",
    "quest",
    "questRewards",
    "class",
    "interface",
    "mergeShop",
    "shop",
    "hairShopF",
    "hairShopM",
    // "houseShop",
    // "houseItemShop",
]);

function convertAll(array $folders) {

    $totalFiles = (function() use ($folders): int {
        return \array_reduce($folders, function(int $totalFiles, string $folder): int {
            return $totalFiles + \count(\scandir("downloaded/{$folder}")) - 2; // ignoring . and ..
        }, 0);
    })();

    $dataToSave = [];

    $currentFile = 0;
    foreach ($folders as $folder) {

        $files = \scandir("downloaded/{$folder}");
        \usort($files, function(string $a, string $b) {
            return \strnatcasecmp($a, $b);
        });

        foreach ($files as $file) {
            if ($file === "." || $file === "..") {
                continue;
            }

            $percentStr = getPercentString($currentFile, $totalFiles);
            echo "[0] Converting {$folder}/{$file} {$percentStr}\n";

            if(\is_dir("downloaded/{$folder}/{$file}")) {
                $filesFromDir = \array_map(function(string $newFileName) use($file) {
                    return "{$file}/{$newFileName}";
                }, \array_filter(\scandir("downloaded/{$folder}/{$file}"), function(string $newFileName) {
                    return $newFileName !== "." && $newFileName !== "..";
                }));
            } else {
                $filesFromDir = [$file];
            }
            foreach($filesFromDir as $file) {
                $newFile = convertFile($folder, $file);
                foreach($newFile as $key => $value) {
                    $value = \array_filter($value, function($v) {
                        return !!$v;
                    });
                    if(!$value) {
                        continue;
                    }
                    if(!isset($dataToSave[$key])) {
                        $dataToSave[$key] = [];
                    }
                    $dataToSave[$key] = \array_merge($dataToSave[$key], $value);
                }
            }
            $currentFile++;
        }
    }
    echo "[0] Conversion done! Saving data...\n";
    saveData($dataToSave);

    echo "[0] All done!\n";
}

function convertFile(string $folder, string $fileName): array {
    global $xsd;

    $xmlStr = \file_get_contents("downloaded/{$folder}/{$fileName}");
    $xmlStr = \preg_replace('/\r?\n/', "HIPERESP-NEWLINE", \trim($xmlStr));
    $xmlStr = \str_replace(">HIPERESP-NEWLINE<", ">\n<", $xmlStr);
    $xml = \simplexml_load_string($xmlStr);
    $json = \json_decode(\json_encode($xml), true);
    $json = normalizeJsonNewLine($json);

    if(!isset($xsd[$folder])) {
        throw new \Exception("XSD not found for folder: {$folder}");
    }

    $json = convertToJson($fileName, $json, $xsd[$folder]);

    return $json;
}

function saveData(array &$json): void {
    global $saveMode, $merges, $resetFiles;

    $total = (function() use ($json): int {
        return \array_reduce($json, function(int $total, array $data): int {
            return $total + \count($data);
        }, 0);
    })();

    $current = 0;
    foreach($json as $newFolder => $newData) {
        echo "[1] Saving {$newFolder}...\n";

        $newDir = "converted/{$newFolder}";
        if(\in_array($newFolder, $resetFiles)) {
            if(\is_dir($newDir)) {
                \array_map('unlink', \glob("{$newDir}/*"));
            }
        }

        if (!\is_dir($newDir)) {
            \mkdir($newDir, 0777, true);
        }

        if($saveMode=="individual") {
            foreach($newData as $newJson) {
                $current++;
                if(!isset($newJson["id"])) {
                    throw new \Exception("ID not found");
                }

                $percentStr = getPercentString($current - 1, $total);
                echo "[1] Saving {$newJson["id"]} to {$newFolder}/{$newJson["id"]}.json {$percentStr}\n";

                if(\file_exists("{$newDir}/{$newJson["id"]}.json")) {
                    $currentFileData = \file_get_contents("{$newDir}/{$newJson["id"]}.json");
                    if($currentFileData === \json_encode([$newJson], JSON_PRETTY_PRINT)) {
                        continue;
                    }
                    try {
                        $newJson = $merges["default"](\json_decode($currentFileData, true)[0], $newJson);
                    } catch(\Exception $e) {
                        try {
                            if(!isset($merges[$newFolder])) {
                                throw $e;
                            }
                            $newJson = $merges[$newFolder](\json_decode($currentFileData, true)[0], $newJson);
                        } catch(\Exception $e) {
                            throw new \Exception("{$e->getMessage()}: {$newDir}/{$newJson["id"]}.json\nCurrent data:{$currentFileData}\nNew data    :".\json_encode($newJson, JSON_PRETTY_PRINT));
                        }
                    }
                }
                $dataToSave = \json_encode([$newJson], JSON_PRETTY_PRINT);
                \file_put_contents("{$newDir}/{$newJson["id"]}.json", $dataToSave);
            }
        } else if($saveMode=="merged") {
            if(!\file_exists("{$newDir}/merged.json")) {
                \file_put_contents("{$newDir}/merged.json", "[]");
            }

            $currentData = \json_decode(\file_get_contents("{$newDir}/merged.json"), true);

            $percentStr = getPercentString($current, $total);
            echo "[1] Saving {$newFolder}/merged.json {$percentStr}\n";

            foreach($newData as $newJson) {
                $current++;
                if(!isset($newJson["id"])) {
                    throw new \Exception("ID not found");
                }

                foreach($currentData as $currentDataKey => $currentDataItem) {
                    if($currentDataItem===$newJson) {
                        continue 2;
                    }
                    if($currentDataItem["id"] === $newJson["id"]) {
                        try {
                            $newJson = $merges["default"]($currentDataItem, $newJson);
                        } catch(\Exception $e) {
                            try {
                                if(!isset($merges[$newFolder])) {
                                    throw $e;
                                }
                                $newJson = $merges[$newFolder]($currentDataItem, $newJson);
                            } catch(\Exception $e) {
                                throw new \Exception("{$e->getMessage()}: {$newDir}/merged.json\nCurrent data:".\json_encode($currentDataItem)."\nNew data    :".\json_encode($newJson));
                            }
                        }
                        unset($currentData[$currentDataKey]);
                        break;
                    }
                }
                $currentData[] = $newJson;
            }

            \usort($currentData, function(array $a, array $b): int {
                return \strnatcasecmp($a["id"], $b["id"]);
            });

            \file_put_contents("{$newDir}/merged.json", \json_encode($currentData, JSON_PRETTY_PRINT));
        } else {
            throw new \Exception("Save mode not supported: {$saveMode}");
        }
    }
    echo "[1] Done!\n";
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
                    throw new \Exception("Attribute key not found in XSD config: {$key2}. Data (to speed the development): ".\json_encode($value, JSON_PRETTY_PRINT));
                }
                continue;
            }
            if(!isset($xsdItem["children"][$key])) {
                throw new \Exception("Child key not found in XSD config: {$key}.");
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
        } else if(isset($config["fromSpecial"])) {
            if($config["fromSpecial"]=="idFromFileName") {
                $newJsonItem[$key] = (int)\str_replace(".xml", "", $fileName);
            } else if($config["fromSpecial"]=="idFromDirName") {
                $newJsonItem[$key] = (int)\str_replace(".xml", "", \explode("/", $fileName)[0]);
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

function generatedIds(string $type, array $parents): int {
    if(\in_array($type, $itemLogic = [ "monster_weapon", "monster_armor", "monster_pet", "monster_item" ])) {
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
    if(\in_array($type, $itemLogic = [ "item_category" ])) {
        return match(\trim($parents[0]['raw']['@attributes']['strCategory'])) {
            "Weapon" => 1,
            "Armor" => 2,
            "Pet" => 3,
            "Item" => 4,
            default => throw new \Exception("Item category not found: {$parents[0]['raw']['@attributes']['strCategory']}"),
        };
    }
    if($type === "quest_monster") {
        static $questMonsterIds = [];
        $questId = $parents[0]["parsed"]["id"];
        if(!isset($questMonsterIds[$questId])) {
            $questMonsterIds[$questId] = 0;
        }
        return $questId * 10_000 + ++$questMonsterIds[$questId];
    }
    if($type === "quest_item") {
        return $parents[0]["parsed"]["questId"] * 100_000 + $parents[0]["parsed"]["itemId"];
    }
    if($type === "hairShop_hair") {
        return $parents[2]["parsed"]["id"] * 1_000 + $parents[1]["parsed"]["id"];
    }
    if($type === "mergeShop_merge") {
        static $mergeShop_mergeId = 0;
        return ++$mergeShop_mergeId;
    }
    throw new \Exception("Generated ID not found: {$type}");
};

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

function getPercentString(int $current, int $total): string {
    global $maxMemoryUsageMB, $startTime;

    $percent = (\number_format($current / $total, 5) * 100)."%";
    $memoryUsageMB = \memory_get_usage(true) / 1024 / 1024;
    $memoryUsagePercent = (\number_format($memoryUsageMB / $maxMemoryUsageMB, 5) * 100)."%";
    $memoryUsageStr = \number_format($memoryUsageMB)."M";

    $elapsedTime = (int)\microtime(true) - $startTime;
    $estimatedTotalTime = ($elapsedTime / ($current + 1)) * $total;
    $remainingTime = $estimatedTotalTime - $elapsedTime;
    $eta = \gmdate("H:i:s", (int)$remainingTime);

    return "({$percent}) - MEM: {$memoryUsageStr} ({$memoryUsagePercent}) - ETA: {$eta}";
}

function addMissingKeys(array $array1, array $array2): array {
    $keys1 = \array_keys($array1);
    $keys2 = \array_keys($array2);
    if($keys1 !== $keys2) {
        $allKeys = \array_unique(\array_merge($keys1, $keys2));

        $newArray1 = [];
        foreach($allKeys as $key) {
            if(\array_key_exists($key, $array1)) {
                $newArray1[$key] = $array1[$key];
            } else {
                $newArray1[$key] = $array2[$key];
            }
        }

        $newArray2 = [];
        foreach($allKeys as $key) {
            if(\array_key_exists($key, $array2)) {
                $newArray2[$key] = $array2[$key];
            } else {
                $newArray2[$key] = $array1[$key];
            }
        }

        $array1 = $newArray1;
        $array2 = $newArray2;
    }

    return [\array_keys($array1), $array1, $array2];
}