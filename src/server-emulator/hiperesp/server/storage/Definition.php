<?php declare(strict_types=1);
namespace hiperesp\server\storage;

final class Definition {
    public static array $definition = [
        "settings" => [
            "migrateOldData" => true,
            "replaceFieldsWithNewData" => [ "gameSwf", "serverVersion", ],
            "structure" => [
                "id"                    => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],

                "createdAt"             => [ 'DATETIME', 'CREATED_DATETIME' ],
                "updatedAt"             => [ 'DATETIME', 'UPDATED_DATETIME' ],

                "serverName"            => [ 'STRING' => 255, "DEFAULT" => "DragonFable Private Server" ],

                "gameSwf"               => [ 'STRING' => 255, "DEFAULT" => "" ],
                "serverVersion"         => [ 'STRING' => 255, "DEFAULT" => "" ],
                "serverLocation"        => [ 'STRING' => 255, "DEFAULT" => "" ],
                "gamefilesPath"         => [ 'STRING' => 255, "DEFAULT" => "" ],

                "homeUrl"               => [ 'STRING' => 255, "DEFAULT" => "../../../index.html" ],
                "playUrl"               => [ 'STRING' => 255, "DEFAULT" => "../../../play.html" ],
                "signUpUrl"             => [ 'STRING' => 255, "DEFAULT" => "../../../signup.html" ],
                "lostPasswordUrl"       => [ 'STRING' => 255, "DEFAULT" => "../../../lost-password.html" ],
                "tosUrl"                => [ 'STRING' => 255, "DEFAULT" => "../../../tos.html" ],
                "charDetailUrl"         => [ 'STRING' => 255, "DEFAULT" => "../../../char-detail.html" ],

                "signUpMessage"         => [ 'STRING' => 255, "DEFAULT" => "Welcome to the world of DragonFable!\n\nPlease sign up to play!" ],
                "news"                  => [ 'STRING' => 255, "DEFAULT" => "It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!\n\nIt's Togsday!\n\nCheck out the DNs for more info!" ],

                "enableAdvertising"     => [ 'BIT', 'DEFAULT' => 0 ],
                "dailyQuestCoinsReward" => [ 'INTEGER', 'DEFAULT' => 3 ],

                "dragonAmuletForAll"    => [ 'BIT', 'DEFAULT' => 0 ],

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

                "onlineThreshold"       => [ 'INTEGER', 'DEFAULT' => 10 ],

                "detailed404ClientError"=> [ "BIT", 'DEFAULT' => 1 ],

                "sendEmails"            => [ "BIT", 'DEFAULT' => 0 ],
                "emailApiUrl"           => [ "STRING" => 255, "DEFAULT" => "https://send.api.mailtrap.io/api/send" ],
                "emailApiToken"         => [ "STRING" => 255, "DEFAULT" => "" ],
                "emailAddress"          => [ "STRING" => 255, "DEFAULT" => "" ],
            ],
            "data" => [// See README.md (help > settings).
                [
                    "id"                    => 1,

                    "serverName"            => "DragonFable Private Server",

                    "gameSwf"               => "game15_9_45-patched.swf",
                    "serverVersion"         => "Build 15.9.45 alpha", // appears in the game client version, only display
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

                    "dragonAmuletForAll" => 0, // if true, all players will have dragon amulet (DA) for free

                    "revalidateClientValues" => 1, // some inconsistencies in the client can occur.
                    "banInvalidClientValues" => 1, // some users can be banned injustly if inconsistencies are found.
                    "canDeleteUpgradedChar"  => 1, // if true, the user can delete an upgraded character.

                    "nonUpgradedMaxBagSlots"=> 30,
                    "upgradedMaxBagSlots"   => 90,

                    "experienceMultiplier"  => 10,
                    "gemsMultiplier"        => 10,
                    "goldMultiplier"        => 10,
                    "silverMultiplier"      => 10,

                    "onlineThreshold"       => 10, // minutes. It affects only the online status of the player

                    "sendEmails"            => 0,
                    "emailApiUrl"           => "https://send.api.mailtrap.io/api/send",
                    "emailApiToken"         => "280061d0e211c57de35f897d8bf0ad6c",
                    "emailAddress"          => "yourserver@yourdomain.com",
                ]
            ],
        ],
        "interface" => [
            "migrateOldData" => false,
            "structure" => [
                "id"        => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"      => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "swf"       => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "loadUnder" => [ 'INTEGER', 'DEFAULT' => 0 ],
            ],
            "data" => "interface/",
        ],
        "itemCategory" => [
            "migrateOldData" => false,
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
            "migrateOldData" => false,
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
            "migrateOldData" => false,
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255, 'DEFAULT' => ""],
                "count" => [ 'INTEGER', 'DEFAULT' => -100 ],
            ],
            "data" => "itemShop/",
        ],
        "itemShop_item" => [
            "migrateOldData" => false,
            "structure" => [
                "id"     => [ 'INTEGER', 'PRIMARY_KEY' ],
                "itemShopId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "itemShop", "field" => "id" ] ],
                "itemId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item", "field" => "id" ] ],
            ],
            "data" => "itemShop_item/",
        ],
        "mergeShop" => [
            "migrateOldData" => false,
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255, 'DEFAULT' => "" ],
            ],
            "data" => "mergeShop/",
        ],
        "merge" => [
            "migrateOldData" => false,
            "structure" => [
                "id"      => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
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
            "data" => "merge/",
        ],
        "mergeShop_merge" => [
            "migrateOldData" => false,
            "structure" => [
                "id"          => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "mergeShopId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "mergeShop", "field" => "id" ] ],
                "mergeId"     => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "merge", "field" => "id" ] ],
            ],
            "data" => "mergeShop_merge/",
        ],
        "quest" => [
            "migrateOldData" => false,
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
            "migrateOldData" => false,
            "structure" => [
                "id"      => [ 'INTEGER', 'PRIMARY_KEY' ],
                "questId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "quest", "field" => "id" ] ],
                "itemId"  => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "item" , "field" => "id" ] ],
            ],
            "data" => "quest_item/",
        ],
        "user" => [
            "migrateOldData" => true,
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

                "recoveryCode"      => [ 'STRING' => 6, 'DEFAULT' => NULL ],
                "recoveryExpires"   => [ 'DATETIME', 'DEFAULT' => NULL ],
            ],
            "data" => "user.json",
        ],
        "race" => [
            "migrateOldData" => false,
            "structure" => [
                "id"        => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"      => [ 'STRING' => 20 ],
                "resists"   => [ 'STRING' => 255 ],
            ],
            "data" => "race/",
        ],
        "hair" => [
            "migrateOldData" => false,
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
            "migrateOldData" => false,
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255, 'DEFAULT' => "" ],
                "swf"   => [ 'STRING' => 255, 'DEFAULT' => "" ],
            ],
            "data" => "hairShop/",
        ],
        "hairShop_hair" => [
            "migrateOldData" => false,
            "structure" => [
                "id"         => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "hairShopId" => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "hairShop", "field" => "id" ] ],
                "hairId"     => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "hair", "field" => "id" ] ],
            ],
            "data" => "hairShop_hair/",
        ],
        "class" => [
            "migrateOldData" => false,
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
            "migrateOldData" => true,
            "structure" => [
                "id"                => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],

                "userId"            => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "user", "field" => "id" ] ],

                "createdAt"         => [ 'DATETIME', 'CREATED_DATETIME' ],
                "updatedAt"         => [ 'DATETIME', 'UPDATED_DATETIME' ],

                "name"              => [ 'STRING' => 20 ],

                "level"             => [ 'INTEGER', 'DEFAULT' => 1 ],
                "experience"        => [ 'INTEGER', 'DEFAULT' => 0 ],

                "silver"            => [ 'INTEGER', 'DEFAULT' => 0 ],
                "gold"              => [ 'INTEGER', 'DEFAULT' => 1000 ],
                "gems"              => [ 'INTEGER', 'DEFAULT' => 0 ],
                "coins"             => [ 'INTEGER', 'DEFAULT' => 0 ],

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
		"char_dragon" => [
			"migrateOldData" => true,
			"structure" => [
				"id"            => ['INTEGER', 'GENERATED', 'PRIMARY_KEY'],
				"charId"        => ['INTEGER', 'FOREIGN_KEY' => [ "collection" => "char", "field" => "id" ] ],
				"name"          => ['STRING' => 20, 'DEFAULT' => "Draco"],
				"lastFed"       => ['DATETIME', 'CREATED_DATETIME'],
				"growthLevel"	=> ['INTEGER', 'DEFAULT' => 0],
				"totalStats"    => ['INTEGER', 'DEFAULT' => 0],
				"heal"          => ['INTEGER', 'DEFAULT' => 0],
				"magic"         => ['INTEGER', 'DEFAULT' => 0],
				"melee"         => ['INTEGER', 'DEFAULT' => 0],
				"buff"          => ['INTEGER', 'DEFAULT' => 0],
				"debuff"        => ['INTEGER', 'DEFAULT' => 0],
				"colorDSkin"    => ['INTEGER', 'DEFAULT' => 4424742],
				"colorDEye"     => ['INTEGER', 'DEFAULT' => 13369344],
				"colorDHorn"    => ['INTEGER', 'DEFAULT' => 8675904],
				"colorDWing"    => ['INTEGER', 'DEFAULT' => 11050037],
				"headId"        => ['INTEGER', 'DEFAULT' => 1],
				"headFileName"  => ['STRING' => 255, 'DEFAULT' => "none"],
				"wingId"        => ['INTEGER', 'DEFAULT' => 1],
				"wingFileName"  => ['STRING' => 255, 'DEFAULT' => "none"],
				"tailId"        => ['INTEGER', 'DEFAULT' => 1],
				"tailFileName"  => ['STRING' => 255, 'DEFAULT' => "none"],
				"filename"      => ['STRING' => 255, 'DEFAULT' => "pets/pet-babydragon.swf"],
				"min"           => ['INTEGER', 'DEFAULT' => 1],
				"max"           => ['INTEGER', 'DEFAULT' => 1],
				"type"          => ['STRING' => 255, 'DEFAULT' => "Melee"],
				"element"       => ['STRING' => 255, 'DEFAULT' => "Fire"],
				"colorDElement" => ['INTEGER', 'DEFAULT' => 16292121]
			],
			"data" => "char_dragon.json",
		],
        "char_item" => [
            "migrateOldData" => true,
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
            "migrateOldData" => false,
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
            "migrateOldData" => false,
            "structure" => [
                "id"        => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "questId"   => [ 'INTEGER', 'DEFAULT' => 0, 'FOREIGN_KEY' => [ "collection" => "quest",   "field" => "id" ] ],
                "monsterId" => [ 'INTEGER', 'DEFAULT' => 0, 'FOREIGN_KEY' => [ "collection" => "monster", "field" => "id" ] ],
            ],
            "data" => "quest_monster/",
        ],
        "house" => [
            "migrateOldData" => false,
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
            "migrateOldData" => false,
            "structure" => [
                "id"    => [ 'INTEGER', 'PRIMARY_KEY' ],
                "name"  => [ 'STRING' => 255, 'DEFAULT' => "" ],
            ],
            "data" => "houseShop/",
        ],
        "houseShop_house" => [
            "migrateOldData" => false,
            "structure" => [
                "id"            => [ 'INTEGER', 'GENERATED', 'PRIMARY_KEY' ],
                "houseShopId"   => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "houseShop", "field" => "id" ] ],
                "houseId"       => [ 'INTEGER', 'FOREIGN_KEY' => [ "collection" => "house", "field" => "id" ] ],
            ],
            "data" => "houseShop_house/",
        ],
        "logs" => [
            "migrateOldData" => true,
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
}