<?php declare(strict_types=1);
namespace hiperesp\server\storage;

final class CollectionSetup {
    private static array $collectionSetup = [
        "settings" => [
            "structure" => [
                "id"                    => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],

                "createdAt"             => [ 'DATETIME', 'CREATED_DATETIME' ],
                "updatedAt"             => [ 'DATETIME', 'UPDATED_DATETIME' ],

                "gameSwf"               => [ 'STRING' => 255 ],
                "serverVersion"         => [ 'STRING' => 255 ],
                "serverLocation"        => [ 'STRING' => 255 ],
                "gamefilesPath"         => [ 'STRING' => 255 ],

                "homeUrl"               => [ 'STRING' => 255 ],
                "playUrl"               => [ 'STRING' => 255 ],
                "signUpUrl"             => [ 'STRING' => 255 ],
                "lostPasswordUrl"       => [ 'STRING' => 255 ],
                "tosUrl"                => [ 'STRING' => 255 ],
                "charDetailUrl"         => [ 'STRING' => 255 ],

                "signUpMessage"         => [ 'STRING' => 255 ],
                "news"                  => [ 'STRING' => 255 ],

                "enableAdvertising"     => [ 'BIT', 'DEFAULT' => 0 ],
                "dailyQuestCoinsReward" => [ 'INTEGER', 'DEFAULT' => 3 ],

                "revalidateClientValues" => [ 'BIT', 'DEFAULT' => 0 ],
                "banInvalidClientValues" => [ 'BIT', 'DEFAULT' => 0 ],
                "canDeleteUpgradedChar"  => [ 'BIT', 'DEFAULT' => 1 ],

                "nonUpgradedChars"              => [ 'INTEGER', 'DEFAULT' => 3 ],
                "upgradedChars"                 => [ 'INTEGER', 'DEFAULT' => 6 ],
                "nonUpgradedMaxBagSlots"        => [ 'INTEGER', 'DEFAULT' => 30 ],
                "upgradedMaxBagSlots"           => [ 'INTEGER', 'DEFAULT' => 50 ],
                "nonUpgradedMaxBankSlots"       => [ 'INTEGER', 'DEFAULT' => 0 ],
                "upgradedMaxBankSlots"          => [ 'INTEGER', 'DEFAULT' => 0 ],
                "nonUpgradedMaxHouseSlots"      => [ 'INTEGER', 'DEFAULT' => 5 ],
                "upgradedMaxHouseSlots"         => [ 'INTEGER', 'DEFAULT' => 5 ],
                "nonUpgradedMaxHouseItemSlots"  => [ 'INTEGER', 'DEFAULT' => 20 ],
                "upgradedMaxHouseItemSlots"     => [ 'INTEGER', 'DEFAULT' => 20 ],

                "experienceMultiplier"  => [ 'FLOAT', 'DEFAULT' => 1 ],
                "gemsMultiplier"        => [ 'FLOAT', 'DEFAULT' => 1 ],
                "goldMultiplier"        => [ 'FLOAT', 'DEFAULT' => 1 ],
                "silverMultiplier"      => [ 'FLOAT', 'DEFAULT' => 1 ],

                "onlineTimeout"         => [ 'INTEGER', 'DEFAULT' => 10 ],

                "detailed404ClientError"=> [ "BIT", 'DEFAULT' => 1 ],
            ],
            "data" => [// See README.md (help > settings).
                [
                    "id"                    => 1,

                    "gameSwf"               => "game15_9_14-patched.swf",
                    "serverVersion"         => "Build 15.9.14 alpha", // appears in the game client version, only display
                    //                                this is the ^ last visible char (aprox. 19 chars)
                    "serverLocation"        => "server-emulator/server.php/", // You can also use something like "http://localhost:40000/server-emulator/server.php/";
                    "gamefilesPath"         => "cdn/gamefiles/cache.php/",   // You can also use something like "http://localhost:40000/cdn/gamefiles/cache.php/";

                    "homeUrl"               => "../../../index.html",
                    "playUrl"               => "../../../play.html",
                    "signUpUrl"             => "../../../signup.html",
                    "lostPasswordUrl"       => "../../../lost-password.html",
                    "tosUrl"                => "../../../tos.html",
                    "charDetailUrl"         => "../../../char-detail.html",

                    "signUpMessage"         => "Welcome to the world of DragonFable!\n\nPlease sign up to play!",
                    "news"                  => "It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!\n\nIt's Togsday!\n\nCheck out the DNs for more info!",

                    "enableAdvertising"     => 0, // if true, the game will show ads
                    "dailyQuestCoinsReward" => 200, // coins reward for daily quests (default: 3)

                    "revalidateClientValues" => 1, // some inconsistencies in the client can occur.
                    "banInvalidClientValues" => 1, // some users can be banned injustly if inconsistencies are found.
                    "canDeleteUpgradedChar"  => 1, // if true, the user can delete an upgraded character.

                    "nonUpgradedMaxBagSlots"=> 30,
                    "upgradedMaxBagSlots"   => 90,

                    "experienceMultiplier"  => 10,
                    "gemsMultiplier"        => 10,
                    "goldMultiplier"        => 10,
                    "silverMultiplier"      => 10,

                    "onlineTimeout"         => 10, // minutes. It affects only the online status of the player
                ]
            ],
        ],
        "interface" => [
            "structure" => [
                "id"        => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"      => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "swf"       => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "loadUnder" => [ 'INTEGER', 'DEFAULT' => 0 ],
            ],
            "data" => "interface/",
        ],
        "itemCategory" => [
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255 ],
            ],
            "data" => [
                [ "id" => 1, "name" => "Weapon" ],
                [ "id" => 2, "name" => "Armor" ],
                [ "id" => 3, "name" => "Pet" ],
                [ "id" => 4, "name" => "Item" ],
            ],
        ],
        "item" => [
            "structure" => [
                "id"            => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"          => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "description"   => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "designInfo"    => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "visible"       => [ 'INTEGER', 'DEFAULT' => 1 ],
                "destroyable"   => [ 'INTEGER', 'DEFAULT' => 1 ],
                "sellable"      => [ 'INTEGER', 'DEFAULT' => 1 ],
                "dragonAmulet"  => [ 'INTEGER', 'DEFAULT' => 0 ],
                "currency"      => [ 'INTEGER', 'DEFAULT' => 1 ],
                "cost"          => [ 'INTEGER', 'DEFAULT' => 0 ],
                "maxStackSize"  => [ 'INTEGER', 'DEFAULT' => 1 ],
                "bonus"         => [ 'INTEGER', 'DEFAULT' => 0 ],
                "rarity"        => [ 'INTEGER', 'DEFAULT' => 0 ],
                "level"         => [ 'INTEGER', 'DEFAULT' => 1 ],
                "type"          => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "element"       => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "categoryId"    => [ 'INTEGER', 'DEFAULT' => 1, 'FOREIGN_KEY' => [ "collection" => "itemCategory", "field" => "id" ] ],
                "equipSpot"     => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "itemType"      => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "swf"           => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "icon"          => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "strength"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "dexterity"     => [ 'INTEGER', 'DEFAULT' => 0 ],
                "intelligence"  => [ 'INTEGER', 'DEFAULT' => 0 ],
                "luck"          => [ 'INTEGER', 'DEFAULT' => 0 ],
                "charisma"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "endurance"     => [ 'INTEGER', 'DEFAULT' => 0 ],
                "wisdom"        => [ 'INTEGER', 'DEFAULT' => 0 ],
                "damageMin"     => [ 'INTEGER', 'DEFAULT' => 0 ],
                "damageMax"     => [ 'INTEGER', 'DEFAULT' => 0 ],
                "defenseMelee"  => [ 'INTEGER', 'DEFAULT' => 0 ],
                "defensePierce" => [ 'INTEGER', 'DEFAULT' => 0 ],
                "defenseMagic"  => [ 'INTEGER', 'DEFAULT' => 0 ],
                "critical"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "parry"         => [ 'INTEGER', 'DEFAULT' => 0 ],
                "dodge"         => [ 'INTEGER', 'DEFAULT' => 0 ],
                "block"         => [ 'INTEGER', 'DEFAULT' => 0 ],
                "resists"       => [ 'STRING' => 255, 'DEFAULT' => "" ],
            ],
            "data" => "item/",
        ],
        "itemShop" => [
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255, 'DEFAULT' => ""],
                "count" => [ 'INTEGER', 'DEFAULT' => -100 ],
            ],
            "data" => "itemShop/",
        ],
        "itemShop_item" => [
            "structure" => [
                "id"     => [ 'INTEGER', 'PRIMARY_KEY' ],
                "itemShopId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "itemShop", "field" => "id" ] ],
                "itemId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
            ],
            "data" => "itemShop_item/",
        ],
        "mergeShop" => [
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255, 'DEFAULT' => "" ],
            ],
            "data" => "mergeShop/",
        ],
        "mergeShop_item" => [
            "structure" => [
                "id"      => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "mergeShopId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "mergeShop", "field" => "id" ] ],
                "itemId1" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
                "amount1" => [ 'INTEGER', 'DEFAULT' => 1 ],
                "itemId2" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
                "amount2" => [ 'INTEGER', 'DEFAULT' => 1 ],
                "itemId"  => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
                "string"  => [ 'INTEGER', 'DEFAULT' => -1 ],
                "index"   => [ 'INTEGER', 'DEFAULT' => 0 ],
                "value"   => [ 'INTEGER', 'DEFAULT' => 0 ],
                "level"   => [ 'INTEGER', 'DEFAULT' => 0 ],
            ],
            "data" => "mergeShop_item/",
        ],
        "quest" => [
            "structure" => [
                "id"                => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"              => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "description"       => [ 'STRING', 'DEFAULT' => "" ],
                "complete"          => [ 'STRING', 'DEFAULT' => "" ],
                "swf"               => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "swfX"              => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "maxSilver"         => [ 'INTEGER', 'DEFAULT' => 0 ],
                "maxGold"           => [ 'INTEGER', 'DEFAULT' => 0 ],
                "maxGems"           => [ 'INTEGER', 'DEFAULT' => 0 ],
                "maxExp"            => [ 'INTEGER', 'DEFAULT' => 0 ],
                "minTime"           => [ 'INTEGER', 'DEFAULT' => 0 ],
                "counter"           => [ 'INTEGER', 'DEFAULT' => 0 ],
                "extra"             => [ 'STRING', 'DEFAULT' => "" ],
                "dailyIndex"        => [ 'INTEGER', 'DEFAULT' => 0 ],
                "dailyReward"       => [ 'INTEGER', 'DEFAULT' => 0 ],
                "monsterMinLevel"   => [ 'INTEGER', 'DEFAULT' => 0 ],
                "monsterMaxLevel"   => [ 'INTEGER', 'DEFAULT' => 0 ],
                "monsterType"       => [ 'STRING', 'DEFAULT' => "" ],
                "monsterGroupSwf"   => [ 'STRING', 'DEFAULT' => "" ],
            ],
            "data" => "quest/",
        ],
        "quest_item" => [
            "structure" => [
                "id"      => [ 'INTEGER', 'PRIMARY_KEY' ],
                "questId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "quest", "field" => "id" ] ],
                "itemId"  => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item" , "field" => "id" ] ],
            ],
            "data" => "quest_item/",
        ],
        "user" => [
            "structure" => [
                "id"            => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],

                "createdAt"     => [ 'DATETIME', 'CREATED_DATETIME' ],
                "updatedAt"     => [ 'DATETIME', 'UPDATED_DATETIME' ],

                "username"      => [ 'STRING' => 25,  'UNIQUE' ],
                "password"      => [ 'STRING' => 64,  ],
                "email"         => [ 'STRING' => 255, 'UNIQUE' ],
                "birthdate"     => [ 'DATE' ],

                "sessionToken"  => [ 'STRING' => 32, 'UNIQUE' ],

                "upgraded"      => [ 'BIT', 'DEFAULT' => 0],
                "activated"     => [ 'BIT', 'DEFAULT' => 0],
                "optIn"         => [ 'BIT', 'DEFAULT' => 0],
                "special"       => [ 'BIT', 'DEFAULT' => 0],

                "banned"        => [ 'BIT', 'DEFAULT' => 0],
                "lastLogin"     => [ 'DATETIME', 'DEFAULT' => NULL ],
            ],
            "data" => "user.json",
        ],
        "race" => [
            "structure" => [
                "id"        => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"      => [ 'STRING' => 20 ],
                "resists"   => [ 'STRING' => 255 ],
            ],
            "data" => "race/",
        ],
        "hair" => [
            "structure" => [
                "id"         => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"       => [ 'STRING' => 255 ],
                "swf"        => [ 'STRING' => 255 ],
                "frame"      => [ 'BIT', 'DEFAULT' => 0 ],
                "price"      => [ 'INTEGER' ],
                "gender"     => [ 'CHAR' => 1 ], // M, F or B (both)
                "raceId"     => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "race", "field" => "id" ] ],
                "earVisible" => [ 'BIT', 'DEFAULT' => 0 ],
            ],
            "data" => "hair/",
        ],
        "hairShop" => [
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "swf"   => [ 'STRING' => 255, 'DEFAULT' => "" ],
            ],
            "data" => "hairShop/",
        ],
        "hairShop_hair" => [
            "structure" => [
                "id"         => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "hairShopId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "hairShop", "field" => "id" ] ],
                "hairId"     => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "hair", "field" => "id" ] ],
            ],
            "data" => "hairShop_hair/",
        ],
        "class" => [
            "structure" => [
                "id"        => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"      => [ 'STRING' => 255 ],
                "element"   => [ 'STRING' => 255 ],
                "equippable"=> [ 'STRING' => 255 ],
                "swf"       => [ 'STRING' => 255 ],
                "armorId"   => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
                "weaponId"  => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
                "savable"   => [ 'INTEGER' ],
            ],
            "data" => "class/",
        ],
        "char" => [
            "structure" => [
                "id"                => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],

                "userId"            => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "user", "field" => "id" ] ],

                "createdAt"         => [ 'DATETIME', 'CREATED_DATETIME' ],
                "updatedAt"         => [ 'DATETIME', 'UPDATED_DATETIME' ],

                "name"              => [ 'STRING' => 20 ],

                "level"             => [ 'INTEGER', 'DEFAULT' => 1 ],
                "experience"        => [ 'INTEGER', 'DEFAULT' => 0 ],

                "hitPoints"         => [ 'INTEGER', 'DEFAULT' => 100 ],
                "manaPoints"        => [ 'INTEGER', 'DEFAULT' => 100 ],

                "silver"            => [ 'INTEGER', 'DEFAULT' => 0 ],
                "gold"              => [ 'INTEGER', 'DEFAULT' => 1000 ],
                "gems"              => [ 'INTEGER', 'DEFAULT' => 0 ],
                "coins"             => [ 'INTEGER', 'DEFAULT' => 0 ],

                "maxBagSlots"       => [ 'INTEGER', 'DEFAULT' => 30 ],
                "maxBankSlots"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "maxHouseSlots"     => [ 'INTEGER', 'DEFAULT' => 5 ],
                "maxHouseItemSlots" => [ 'INTEGER', 'DEFAULT' => 20 ],

                "dragonAmulet"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "pvpStatus"         => [ 'INTEGER', 'DEFAULT' => 0 ],

                "gender"            => [ 'CHAR' => 1 ], // M or F
                "pronoun"           => [ 'CHAR' => 1 ], // M, F or O

                "hairId"            => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "hair", "field" => "id" ] ],
                "colorHair"         => [ 'CHAR' => 6 ],
                "colorSkin"         => [ 'CHAR' => 6 ],
                "colorBase"         => [ 'CHAR' => 6 ],
                "colorTrim"         => [ 'CHAR' => 6 ],

                "strength"          => [ 'INTEGER', 'DEFAULT' => 0 ],
                "dexterity"         => [ 'INTEGER', 'DEFAULT' => 0 ],
                "intelligence"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "luck"              => [ 'INTEGER', 'DEFAULT' => 0 ],
                "charisma"          => [ 'INTEGER', 'DEFAULT' => 0 ],
                "endurance"         => [ 'INTEGER', 'DEFAULT' => 0 ],
                "wisdom"            => [ 'INTEGER', 'DEFAULT' => 0 ],

                "lastDailyQuestDone"=> [ "DATE", 'DEFAULT' => NULL ],

                "armor"             => [ 'CHAR' => 100, 'DEFAULT' => "0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],
                "skills"            => [ 'CHAR' => 300, 'DEFAULT' => "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],
                "quests"            => [ 'CHAR' => 300, 'DEFAULT' => "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],

                "raceId"            => [ 'INTEGER', 'DEFAULT' => 1, 'FOREIGN_KEY' => [ "collection" => "race", "field" => "id" ] ],
                "classId"           => [ 'INTEGER', 'DEFAULT' => 0, 'FOREIGN_KEY' => [ "collection" => "class", "field" => "id" ] ],
                "baseClassId"       => [ 'INTEGER', 'DEFAULT' => 0, 'FOREIGN_KEY' => [ "collection" => "class", "field" => "id" ] ],
                "questId"           => [ 'INTEGER', 'DEFAULT' => 933, 'FOREIGN_KEY' => [ "collection" => "quest", "field" => "id" ] ],

                "lastTimeSeen"      => [ 'DATETIME', 'DEFAULT' => NULL, 'INDEX' ],
            ],
            "data" => "char.json",
        ],
        "char_item" => [
            "structure" => [
                "id"        => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],

                "charId"    => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "char", "field" => "id" ] ],

                "createdAt" => [ 'DATETIME', 'CREATED_DATETIME' ],
                "updatedAt" => [ 'DATETIME', 'UPDATED_DATETIME' ],

                "itemId"    => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
                "equipped"  => [ 'BIT', 'DEFAULT' => 0 ],
                "count"     => [ 'INTEGER', 'DEFAULT' => 1 ],
            ],
            "data" => [],
        ],
        "monster" => [
            "structure" => [
                "id"            => [ 'INTEGER', 'PRIMARY_KEY' ],

                "name"          => [ 'STRING' => 255 ],

                "level"         => [ 'INTEGER', 'DEFAULT' => 1 ],
                "experience"    => [ 'INTEGER', 'DEFAULT' => 0 ],

                "hitPoints"     => [ 'INTEGER', 'DEFAULT' => 100 ],
                "manaPoints"    => [ 'INTEGER', 'DEFAULT' => 100 ],

                "silver"        => [ 'INTEGER', 'DEFAULT' => 0 ],
                "gold"          => [ 'INTEGER', 'DEFAULT' => 1000 ],
                "gems"          => [ 'INTEGER', 'DEFAULT' => 0 ],
                "coins"         => [ 'INTEGER', 'DEFAULT' => 0 ],

                "gender"        => [ 'CHAR' => 1 ], // M or F

                "hairStyle"     => [ 'INTEGER' ],
                "colorHair"     => [ 'CHAR' => 6 ],
                "colorSkin"     => [ 'CHAR' => 6 ],
                "colorBase"     => [ 'CHAR' => 6 ],
                "colorTrim"     => [ 'CHAR' => 6 ],

                "strength"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "dexterity"     => [ 'INTEGER', 'DEFAULT' => 0 ],
                "intelligence"  => [ 'INTEGER', 'DEFAULT' => 0 ],
                "luck"          => [ 'INTEGER', 'DEFAULT' => 0 ],
                "charisma"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "endurance"     => [ 'INTEGER', 'DEFAULT' => 0 ],
                "wisdom"        => [ 'INTEGER', 'DEFAULT' => 0 ],

                "element"       => [ 'STRING' => 255 ],

                "raceId"        => [ 'INTEGER', 'DEFAULT' => 1, 'FOREIGN_KEY' => [ "collection" => "race", "field" => "id" ] ],
                "armorId"       => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
                "weaponId"      => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],

                "movName"       => [ 'STRING' => 255 ],
                "swf"           => [ 'STRING' => 255 ],
            ],
            "data" => "monster/",
        ],
        "quest_monster" => [
            "structure" => [
                "id"        => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "questId"   => [ 'INTEGER', 'DEFAULT' => 0, 'FOREIGN_KEY' => [ "collection" => "quest",   "field" => "id" ] ],
                "monsterId" => [ 'INTEGER', 'DEFAULT' => 0, 'FOREIGN_KEY' => [ "collection" => "monster", "field" => "id" ] ],
            ],
            "data" => "quest_monster/",
        ],
        "house" => [
            "structure" => [
                "id"            => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"          => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "description"   => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "visible"       => [ 'INTEGER', 'DEFAULT' => 1 ],
                "destroyable"   => [ 'BIT', 'DEFAULT' => 1 ],
                "equippable"    => [ 'BIT', 'DEFAULT' => 1 ],
                "randomDrop"    => [ 'BIT', 'DEFAULT' => 1 ],
                "sellable"      => [ 'BIT', 'DEFAULT' => 1 ],
                "dragonAmulet"  => [ 'BIT', 'DEFAULT' => 1 ],
                "enc"           => [ 'BIT', 'DEFAULT' => 1 ],
                "cost"          => [ 'INTEGER', 'DEFAULT' => 0 ],
                "currency"      => [ 'INTEGER', 'DEFAULT' => 2 ],
                "rarity"        => [ 'BIT', 'DEFAULT' => 1 ],
                "level"         => [ 'INTEGER', 'DEFAULT' => 1 ],
                "category"      => [ 'INTEGER', 'DEFAULT' => 1 ],
                "equipSpot"     => [ 'INTEGER', 'DEFAULT' => 1 ],
                "type"          => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "random"        => [ 'INTEGER', 'DEFAULT' => 0 ],
                "element"       => [ 'INTEGER', 'DEFAULT' => 1 ],
                "icon"          => [ 'STRING' => 255, 'DEFAULT' => "resource" ],
                "designInfo"    => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "swf"           => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "region"        => [ 'INTEGER', 'DEFAULT' => 1 ],
                "theme"         => [ 'INTEGER', 'DEFAULT' => 1 ],
                "size"          => [ 'INTEGER', 'DEFAULT' => 1 ],
                "baseHP"        => [ 'INTEGER', 'DEFAULT' => 100 ],
                "storageSize"   => [ 'INTEGER', 'DEFAULT' => 0 ],
                "maxGuards"     => [ 'INTEGER', 'DEFAULT' => 1 ],
                "maxRooms"      => [ 'INTEGER', 'DEFAULT' => 0 ],
                "maxExtItems"   => [ 'INTEGER', 'DEFAULT' => 1 ]
            ],
            "data" => "house/",
        ],
        "houseShop" => [
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255, 'DEFAULT' => "" ],
            ],
            "data" => "houseShop/",
        ],
        "houseShop_house" => [
            "structure" => [
                "id"            => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "houseShopId"   => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "houseShop", "field" => "id" ] ],
                "houseId"       => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "house", "field" => "id" ] ],
            ],
            "data" => "houseShop_house/",
        ],
        "logs" => [
            "structure" => [
                "id"            => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "createdAt"     => [ 'DATETIME', 'CREATED_DATETIME' ],

                "userId"        => [ 'INTEGER', 'DEFAULT' => NULL, 'FOREIGN_KEY' => [ "collection" => "user", "field" => "id" ] ],
                "charId"        => [ 'INTEGER', 'DEFAULT' => NULL, 'FOREIGN_KEY' => [ "collection" => "char", "field" => "id" ] ],

                "service"       => [ 'STRING' => 255 ],
                "method"        => [ 'STRING' => 255 ],

                "action"        => [ 'STRING' => 255 ],
                "description"   => [ 'STRING' => 255 ],

                "referenceClass"=> [ 'STRING' => 255 ],
                "referenceId"   => [ 'INTEGER' ],
                "additionalData"=> [ 'STRING' ],

                "severity"      => [ 'STRING' => 255 ],

                "ip"            => [ 'STRING' => 255 ],
                "userAgent"     => [ 'STRING' => 255 ],
            ],
            "data" => [],
        ],
    ];

    private static array $structures = [];
    private static array $data = [];

    public static function getCollections(): array {
        return \array_keys(self::$collectionSetup);
    }

    public static function getStructure(string $collection): array {
        if(!isset(self::$structures[$collection])) {
            self::$structures[$collection] = self::$collectionSetup[$collection]["structure"];
        }
        return self::$structures[$collection];
    }

    public static function getData(string $collection): array {
        if(!isset(self::$data[$collection])) {
            $data = self::$collectionSetup[$collection]["data"];

            if(\is_string($data)) {
                $fileOrDirectory = __DIR__ . "/data/{$data}";
                $filesToParse = [];

                if(\is_file($fileOrDirectory)) {
                    $filesToParse[] = $fileOrDirectory;
                } else if(\is_dir($fileOrDirectory)) {
                    $files = \scandir($fileOrDirectory);
                    foreach($files as $file) {
                        if($file == "." || $file == "..") continue;
                        $filesToParse[] = "{$fileOrDirectory}{$file}";
                    }
                } else {
                    throw new \Exception("Invalid data source for collection '{$collection}'");
                }

                $data = [];
                foreach($filesToParse as $file) {
                    $data = \array_merge($data, \json_decode(\file_get_contents($file), true));
                }
            }

            self::$data[$collection] = $data;
        }
        return self::$data[$collection];
    }

}
