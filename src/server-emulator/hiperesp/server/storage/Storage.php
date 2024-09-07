<?php
namespace hiperesp\server\storage;

abstract class Storage {

    public abstract function select(string $collection, array $where, ?int $limit = 1): array;
    public abstract function insert(string $collection, array $document): array;
    public abstract function update(string $collection, array $document): bool;
    public abstract function delete(string $collection, array $document): bool;

    public abstract function reset(): void;
    public abstract function drop(string $collection): void;

    protected abstract function needsSetup(): bool;
    public abstract function setup(): void;

    private static Storage $instance;
    public static function getStorage(bool $verifySetup = true): Storage {
        $driver = \getenv("DB_DRIVER");
        $options = \json_decode(\getenv("DB_OPTIONS"), true);

        if(!isset(self::$instance)) {
            self::$instance = new $driver($options);
            if($verifySetup && self::$instance->needsSetup()) {
                throw new \Exception("Database needs setup");
            }
        }
        return self::$instance;
    }

    public static function getCollections(): array {
        return \array_keys(self::$collectionSetup);
    }

    private static array $collectionSetupData = [];
    protected static function getFullCollectionSetup(): array {
        $collectionSetupFilled = [];
        foreach(self::$collectionSetup as $collection => $setup) {
            $collectionSetupFilled[$collection] = $setup;
            if(\is_array($setup["data"])) continue;

            if(!isset(self::$collectionSetupData[$collection])) {
                $dataToImport = [];
                if(\preg_match('/\/$/', $setup["data"])) {
                    $files = \scandir(__DIR__ . "/data/{$setup["data"]}");
                    foreach($files as $file) {
                        if(\preg_match("/\.json$/", $file)) {
                            $dataToImport[] = "{$setup["data"]}{$file}";
                        }
                    }
                } else if(\preg_match("/\.json$/", $setup["data"])) {
                    $dataToImport[] = $setup["data"];
                }

                $data = [];
                foreach($dataToImport as $file) {
                    $data = \array_merge($data, \json_decode(\file_get_contents(__DIR__ . "/data/{$file}"), true));
                }

                self::$collectionSetupData[$collection] = $data;
            }

            $collectionSetupFilled[$collection]["data"] = self::$collectionSetupData[$collection];
        }

        return $collectionSetupFilled;
    }

    protected static function getCollectionStructure(string $collection): array {
        return self::$collectionSetup[$collection]["structure"];
    }

    private static $collectionSetup = [
        "settings" => [
            "structure" => [
                "id"                    => [ "INTEGER", "GENERATED", "PRIMARY_KEY" ],

                "createdAt"             => [ "DATETIME", "CREATED_DATETIME" ],
                "updatedAt"             => [ "DATETIME", "UPDATED_DATETIME" ],

                "gameSwf"               => [ "STRING" => 255 ],
                "serverVersion"         => [ "STRING" => 255 ],
                "serverLocation"        => [ "STRING" => 255 ],
                "gamefilesPath"         => [ "STRING" => 255 ],

                "homeUrl"               => [ "STRING" => 255 ],
                "playUrl"               => [ "STRING" => 255 ],
                "signUpUrl"             => [ "STRING" => 255 ],
                "lostPasswordUrl"       => [ "STRING" => 255 ],
                "tosUrl"                => [ "STRING" => 255 ],

                "signUpMessage"         => [ "STRING" => 255 ],
                "news"                  => [ "STRING" => 255 ],

                "enableAdvertising"     => [ "BIT", "DEFAULT" => 0 ],
                "dailyQuestCoinsReward" => [ "INTEGER", "DEFAULT" => 3 ],

                "nonUpgradedChars"              => [ "INTEGER", "DEFAULT" => 3 ],
                "upgradedChars"                 => [ "INTEGER", "DEFAULT" => 6 ],
                "nonUpgradedMaxBagSlots"        => [ "INTEGER", "DEFAULT" => 30 ],
                "upgradedMaxBagSlots"           => [ "INTEGER", "DEFAULT" => 50 ],
                "nonUpgradedMaxBankSlots"       => [ "INTEGER", "DEFAULT" => 0 ],
                "upgradedMaxBankSlots"          => [ "INTEGER", "DEFAULT" => 0 ],
                "nonUpgradedMaxHouseSlots"      => [ "INTEGER", "DEFAULT" => 5 ],
                "upgradedMaxHouseSlots"         => [ "INTEGER", "DEFAULT" => 5 ],
                "nonUpgradedMaxHouseItemSlots"  => [ "INTEGER", "DEFAULT" => 20 ],
                "upgradedMaxHouseItemSlots"     => [ "INTEGER", "DEFAULT" => 20 ],

                "experienceMultiplier"  => [ "FLOAT", "DEFAULT" => 1 ],
                "gemsMultiplier"        => [ "FLOAT", "DEFAULT" => 1 ],
                "goldMultiplier"        => [ "FLOAT", "DEFAULT" => 1 ],
                "silverMultiplier"      => [ "FLOAT", "DEFAULT" => 1 ],

                "levelUpMultipleTimes"  => [ "BIT", "DEFAULT" => 0 ],

                "onlineTimeout"         => [ "INTEGER", "DEFAULT" => 10 ],

                "detailed404ClientError"=> [ "BIT", "DEFAULT" => 1 ],
            ],
            "data" => [
                [
                    "id"                    => 1,

                    "gameSwf"               => "game15_9_00-patched.swf",
                    "serverVersion"         => "Build 15.9.00 alpha", // appears in the game client version, only display
                                            //                    ^ last visible char (aprox. 19 chars)
                    "serverLocation"        => "server-emulator/server.php/", // "http://localhost:40000/server-emulator/server.php/";
                    "gamefilesPath"         => "cdn/gamefiles/", // "http://localhost:40000/cdn/gamefiles/";

                    "homeUrl"               => "../../../index.html",
                    "playUrl"               => "../../../play.html",
                    "signUpUrl"             => "../../../signup.html",
                    "lostPasswordUrl"       => "../../../lost-password.html",
                    "tosUrl"                => "../../../tos.html",

                    "signUpMessage"         => "Welcome to the world of DragonFable!\n\nPlease sign up to play!",
                    "news"                  => "It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!\n\nIt's Togsday!\n\nCheck out the DNs for more info!",

                    "enableAdvertising"     => 0, // if true, the game will show ads
                    "nonUpgradedMaxBagSlots"=> 30,
                    "upgradedMaxBagSlots"   => 90,
                    "dailyQuestCoinsReward" => 200, // coins reward for daily quests (default: 3)

                    "experienceMultiplier"  => 10,
                    "gemsMultiplier"        => 10,
                    "goldMultiplier"        => 10,
                    "silverMultiplier"      => 10,

                    "levelUpMultipleTimes"  => 0, // if true, player can level up multiple times according to the experience gained

                    "onlineTimeout"         => 10, // minutes. It affects only the online status of the player
                ]
            ],
        ],
        "user" => [
            "structure" => [
                "id"            => [ "INTEGER", "GENERATED", "PRIMARY_KEY" ],

                "createdAt"     => [ "DATETIME", "CREATED_DATETIME" ],
                "updatedAt"     => [ "DATETIME", "UPDATED_DATETIME" ],

                "username"      => [ "STRING" => 20,  "UNIQUE" ],
                "password"      => [ "STRING" => 64,  ],
                "email"         => [ "STRING" => 255, "UNIQUE" ],
                "birthdate"     => [ "DATE" ],

                "sessionToken"  => [ "STRING" => 20, "UNIQUE" ],

                "upgraded"      => [ "BIT", "DEFAULT" => 0],
                "activated"     => [ "BIT", "DEFAULT" => 0],
                "optIn"         => [ "BIT", "DEFAULT" => 0],
                "special"       => [ "BIT", "DEFAULT" => 0],

                "banned"        => [ "BIT", "DEFAULT" => 0],
                "lastLogin"     => [ "DATETIME", "DEFAULT" => NULL ],
            ],
            "data" => "user.json",
        ],
        "char" => [
            "structure" => [
                "id"                => [ "INTEGER", "GENERATED", "PRIMARY_KEY" ],

                "userId"            => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "user", "field" => "id" ] ],

                "createdAt"         => [ "DATETIME", "CREATED_DATETIME" ],
                "updatedAt"         => [ "DATETIME", "UPDATED_DATETIME" ],

                "name"              => [ "STRING" => 20 ],

                "level"             => [ "INTEGER", "DEFAULT" => 1 ],
                "experience"        => [ "INTEGER", "DEFAULT" => 0 ],
                "experienceToLevel" => [ "INTEGER", "DEFAULT" => 20 ],

                "hitPoints"         => [ "INTEGER", "DEFAULT" => 100 ],
                "manaPoints"        => [ "INTEGER", "DEFAULT" => 100 ],

                "silver"            => [ "INTEGER", "DEFAULT" => 0 ],
                "gold"              => [ "INTEGER", "DEFAULT" => 1000 ],
                "gems"              => [ "INTEGER", "DEFAULT" => 0 ],
                "coins"             => [ "INTEGER", "DEFAULT" => 0 ],

                "maxBagSlots"       => [ "INTEGER", "DEFAULT" => 30 ],
                "maxBankSlots"      => [ "INTEGER", "DEFAULT" => 0 ],
                "maxHouseSlots"     => [ "INTEGER", "DEFAULT" => 5 ],
                "maxHouseItemSlots" => [ "INTEGER", "DEFAULT" => 20 ],

                "dragonAmulet"      => [ "INTEGER", "DEFAULT" => 0 ],

                "gender"            => [ "CHAR" => 1 ], // M or F
                "pronoun"           => [ "CHAR" => 1 ], // M, F or O

                "hairId"            => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "hair", "field" => "id" ] ],
                "colorHair"         => [ "CHAR" => 6 ],
                "colorSkin"         => [ "CHAR" => 6 ],
                "colorBase"         => [ "CHAR" => 6 ],
                "colorTrim"         => [ "CHAR" => 6 ],

                "strength"          => [ "INTEGER", "DEFAULT" => 0 ],
                "dexterity"         => [ "INTEGER", "DEFAULT" => 0 ],
                "intelligence"      => [ "INTEGER", "DEFAULT" => 0 ],
                "luck"              => [ "INTEGER", "DEFAULT" => 0 ],
                "charisma"          => [ "INTEGER", "DEFAULT" => 0 ],
                "endurance"         => [ "INTEGER", "DEFAULT" => 0 ],
                "wisdom"            => [ "INTEGER", "DEFAULT" => 0 ],

                "skillPoints"       => [ "INTEGER", "DEFAULT" => 0 ],
                "statPoints"        => [ "INTEGER", "DEFAULT" => 0 ],

                "lastDailyQuestDone"=> [ "DATE", "DEFAULT" => NULL ],

                "armor"             => [ "CHAR" => 100, "DEFAULT" => "0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],
                "skills"            => [ "CHAR" => 300, "DEFAULT" => "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],
                "quests"            => [ "CHAR" => 300, "DEFAULT" => "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],

                "raceId"            => [ "INTEGER", "DEFAULT" => 1, "FOREIGN_KEY" => [ "collection" => "race", "field" => "id" ] ],
                "classId"           => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "class", "field" => "id" ] ],
                "baseClassId"       => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "class", "field" => "id" ] ],
                "guildId"           => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "guild", "field" => "id" ] ],
                "questId"           => [ "INTEGER", "DEFAULT" => 933, "FOREIGN_KEY" => [ "collection" => "quest", "field" => "id" ] ],

                "lastTimeSeen"      => [ "DATETIME", "DEFAULT" => NULL, "INDEX" ],
            ],
            "data" => "char.json",
        ],
        "hair" => [
            "structure" => [
                "id"            => [ "INTEGER", "PRIMARY_KEY" ],
                "name"          => [ "STRING" => 255 ],
                "swf"           => [ "STRING" => 255 ],
                "gender"        => [ "CHAR" => 1 ], // M or F
            ],
            "data" => "hair/",
        ],
        "race" => [
            "structure" => [
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
                "name"      => [ "STRING" => 20 ],
                "resists"   => [ "STRING" => 255 ],
            ],
            "data" => "race/",
        ],
        "class" => [
            "structure" => [
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
                "name"      => [ "STRING" => 20 ],
                "element"   => [ "STRING" => 255 ],
                "swf"       => [ "STRING" => 255 ],
                "armorId"   => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "armor",  "field" => "id" ] ],
                "weaponId"  => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "weapon", "field" => "id" ] ],
                "savable"   => [ "INTEGER" ],
            ],
            "data" => "class.json",
        ],
        "quest" => [
            "structure" => [
                "id"                => [ "INTEGER", "PRIMARY_KEY" ],
                "name"              => [ "STRING" => 255, "DEFAULT" => "" ],
                "description"       => [ "STRING" => 255, "DEFAULT" => "" ],
                "complete"          => [ "STRING" => 255, "DEFAULT" => "" ],
                "swf"               => [ "STRING" => 255, "DEFAULT" => "" ],
                "swfX"              => [ "STRING" => 255, "DEFAULT" => "" ],
                "maxSilver"         => [ "INTEGER", "DEFAULT" => 0 ],
                "maxGold"           => [ "INTEGER", "DEFAULT" => 0 ],
                "maxGems"           => [ "INTEGER", "DEFAULT" => 0 ],
                "maxExp"            => [ "INTEGER", "DEFAULT" => 0 ],
                "minTime"           => [ "INTEGER", "DEFAULT" => 0 ],
                "counter"           => [ "INTEGER", "DEFAULT" => 0 ],
                "extra"             => [ "STRING", "DEFAULT" => "" ],
                "dailyIndex"        => [ "INTEGER", "DEFAULT" => 0 ],
                "dailyReward"       => [ "INTEGER", "DEFAULT" => 0 ],
                "monsterMinLevel"   => [ "INTEGER", "DEFAULT" => 0 ],
                "monsterMaxLevel"   => [ "INTEGER", "DEFAULT" => 0 ],
                "monsterType"       => [ "STRING", "DEFAULT" => "" ],
                "monsterGroupSwf"   => [ "STRING", "DEFAULT" => "" ],
            ],
            "data" => "quest/",
        ],
        "quest_monster" => [
            "structure" => [
                "id"        => [ "INTEGER", "GENERATED", "PRIMARY_KEY" ],
                "questId"   => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "quest",   "field" => "id" ] ],
                "monsterId" => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "monster", "field" => "id" ] ],
            ],
            "data" => "quest_monster/",
        ],
        "monster" => [
            "structure" => [
                "id"            => [ "INTEGER", "PRIMARY_KEY" ],

                "name"          => [ "STRING" => 20 ],

                "level"         => [ "INTEGER", "DEFAULT" => 1 ],
                "experience"    => [ "INTEGER", "DEFAULT" => 0 ],

                "hitPoints"     => [ "INTEGER", "DEFAULT" => 100 ],
                "manaPoints"    => [ "INTEGER", "DEFAULT" => 100 ],

                "silver"        => [ "INTEGER", "DEFAULT" => 0 ],
                "gold"          => [ "INTEGER", "DEFAULT" => 1000 ],
                "gems"          => [ "INTEGER", "DEFAULT" => 0 ],
                "coins"         => [ "INTEGER", "DEFAULT" => 0 ],

                "gender"        => [ "CHAR" => 1 ], // M or F

                "hairStyle"     => [ "INTEGER" ],
                "colorHair"     => [ "CHAR" => 6 ],
                "colorSkin"     => [ "CHAR" => 6 ],
                "colorBase"     => [ "CHAR" => 6 ],
                "colorTrim"     => [ "CHAR" => 6 ],

                "strength"      => [ "INTEGER", "DEFAULT" => 0 ],
                "dexterity"     => [ "INTEGER", "DEFAULT" => 0 ],
                "intelligence"  => [ "INTEGER", "DEFAULT" => 0 ],
                "luck"          => [ "INTEGER", "DEFAULT" => 0 ],
                "charisma"      => [ "INTEGER", "DEFAULT" => 0 ],
                "endurance"     => [ "INTEGER", "DEFAULT" => 0 ],
                "wisdom"        => [ "INTEGER", "DEFAULT" => 0 ],

                "element"       => [ "STRING" => 255 ],

                "raceId"        => [ "INTEGER", "DEFAULT" => 1, "FOREIGN_KEY" => [ "collection" => "race", "field" => "id" ] ],
                "armorId"       => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "armor",  "field" => "id" ] ],
                "weaponId"      => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "weapon", "field" => "id" ] ],

                "movName"       => [ "STRING" => 255 ],
                "swf"           => [ "STRING" => 255 ],
            ],
            "data" => "monster/",
        ],
        "guild" => [
            "structure" => [
                "id"    => [ "INTEGER", "PRIMARY_KEY" ],
                "name"  => [ "STRING" => 255 ],
            ],
            "data" => "guild.json",
        ],
        "interface" => [
            "structure" => [
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
                "name"      => [ "STRING" => 255, "DEFAULT" => "" ],
                "swf"       => [ "STRING" => 255, "DEFAULT" => "" ],
                "loadUnder" => [ "INTEGER", "DEFAULT" => 0 ],
            ],
            "data" => "interface/",
        ],
        "item" => [
            "structure" => [
                "id"            => [ "INTEGER", "PRIMARY_KEY" ],
                "name"          => [ "STRING" => 255, "DEFAULT" => "" ],
                "description"   => [ "STRING" => 255, "DEFAULT" => "" ],
                "designInfo"    => [ "STRING" => 255, "DEFAULT" => "" ],
                "visible"       => [ "INTEGER", "DEFAULT" => 1 ],
                "destroyable"   => [ "INTEGER", "DEFAULT" => 1 ],
                "sellable"      => [ "INTEGER", "DEFAULT" => 1 ],
                "dragonAmulet"  => [ "INTEGER", "DEFAULT" => 0 ],
                "currency"      => [ "INTEGER", "DEFAULT" => 1 ],
                "cost"          => [ "INTEGER", "DEFAULT" => 0 ],
                "maxStackSize"  => [ "INTEGER", "DEFAULT" => 1 ],
                "bonus"         => [ "INTEGER", "DEFAULT" => 0 ],
                "rarity"        => [ "INTEGER", "DEFAULT" => 0 ],
                "level"         => [ "INTEGER", "DEFAULT" => 1 ],
                "type"          => [ "STRING" => 255, "DEFAULT" => "" ],
                "element"       => [ "STRING" => 255, "DEFAULT" => "" ],
                "category"      => [ "STRING" => 255, "DEFAULT" => "" ],
                "equipSpot"     => [ "STRING" => 255, "DEFAULT" => "" ],
                "itemType"      => [ "STRING" => 255, "DEFAULT" => "" ],
                "swf"           => [ "STRING" => 255, "DEFAULT" => "" ],
                "icon"          => [ "STRING" => 255, "DEFAULT" => "" ],
                "strength"      => [ "INTEGER", "DEFAULT" => 0 ],
                "dexterity"     => [ "INTEGER", "DEFAULT" => 0 ],
                "intelligence"  => [ "INTEGER", "DEFAULT" => 0 ],
                "luck"          => [ "INTEGER", "DEFAULT" => 0 ],
                "charisma"      => [ "INTEGER", "DEFAULT" => 0 ],
                "endurance"     => [ "INTEGER", "DEFAULT" => 0 ],
                "wisdom"        => [ "INTEGER", "DEFAULT" => 0 ],
                "damageMin"     => [ "INTEGER", "DEFAULT" => 0 ],
                "damageMax"     => [ "INTEGER", "DEFAULT" => 0 ],
                "defenseMelee"  => [ "INTEGER", "DEFAULT" => 0 ],
                "defensePierce" => [ "INTEGER", "DEFAULT" => 0 ],
                "defenseMagic"  => [ "INTEGER", "DEFAULT" => 0 ],
                "critical"      => [ "INTEGER", "DEFAULT" => 0 ],
                "parry"         => [ "INTEGER", "DEFAULT" => 0 ],
                "dodge"         => [ "INTEGER", "DEFAULT" => 0 ],
                "block"         => [ "INTEGER", "DEFAULT" => 0 ],
                "resists"       => [ "STRING" => 255, "DEFAULT" => "" ],
            ],
            "data" => "item/",
        ],
        "itemShop" => [
            "structure" => [
                "id"    => [ "INTEGER", "PRIMARY_KEY" ],
                "name"  => [ "STRING" => 255, "DEFAULT" => ""],
                "count" => [ "INTEGER", "DEFAULT" => -100 ], // not sure what means
            ],
            "data" => "itemShop/",
        ],
        "itemShop_item" => [
            "structure" => [
                "id"     => [ "INTEGER", "PRIMARY_KEY" ],
                "shopId" => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "shop", "field" => "id" ] ],
                "itemId" => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "item", "field" => "id" ] ],
            ],
            "data" => "itemShop_item/",
        ],
        "house" => [
            "structure" => [
                "id"            => [ "INTEGER", "PRIMARY_KEY" ],
                "name"          => [ "STRING" => 255, "DEFAULT" => "" ],
                "description"   => [ "STRING" => 255, "DEFAULT" => "" ],
                "visible"       => [ "INTEGER", "DEFAULT" => 1 ],
                "destroyable"   => [ "BIT", "DEFAULT" => 1 ],
                "equippable"    => [ "BIT", "DEFAULT" => 1 ],
                "randomDrop"    => [ "BIT", "DEFAULT" => 1 ],
                "sellable"      => [ "BIT", "DEFAULT" => 1 ],
                "dragonAmulet"  => [ "BIT", "DEFAULT" => 1 ],
                "enc"           => [ "BIT", "DEFAULT" => 1 ],
                "cost"          => [ "INTEGER", "DEFAULT" => 0 ],
                "currency"      => [ "INTEGER", "DEFAULT" => 2 ],
                "rarity"        => [ "BIT", "DEFAULT" => 1 ],
                "level"         => [ "INTEGER", "DEFAULT" => 1 ],
                "category"      => [ "INTEGER", "DEFAULT" => 1 ],
                "equipSpot"     => [ "INTEGER", "DEFAULT" => 1 ],
                "type"          => [ "STRING" => 255, "DEFAULT" => "" ],
                "random"        => [ "INTEGER", "DEFAULT" => 0 ],
                "element"       => [ "INTEGER", "DEFAULT" => 1 ],
                "icon"          => [ "STRING" => 255, "DEFAULT" => "resource" ],
                "designInfo"    => [ "STRING" => 255, "DEFAULT" => "" ],
                "swf"           => [ "STRING" => 255, "DEFAULT" => "" ],
                "region"        => [ "INTEGER", "DEFAULT" => 1 ],
                "theme"         => [ "INTEGER", "DEFAULT" => 1 ],
                "size"          => [ "INTEGER", "DEFAULT" => 1 ],
                "baseHP"        => [ "INTEGER", "DEFAULT" => 100 ],
                "storageSize"   => [ "INTEGER", "DEFAULT" => 0 ],
                "maxGuards"     => [ "INTEGER", "DEFAULT" => 1 ],
                "maxRooms"      => [ "INTEGER", "DEFAULT" => 0 ],
                "maxExtItems"   => [ "INTEGER", "DEFAULT" => 1 ]
            ],
            "data" => "house/",
        ],
        "houseShop" => [
            "structure" => [
                "id"    => [ "INTEGER", "PRIMARY_KEY" ],
                "name"  => [ "STRING" => 255, "DEFAULT" => "" ],
            ],
            "data" => "houseShop/",
        ],
        "houseShop_house" => [
            "structure" => [
                "id"            => [ "INTEGER", "GENERATED", "PRIMARY_KEY" ],
                "houseShopId"   => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "houseShop", "field" => "id" ] ],
                "houseId"       => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "house", "field" => "id" ] ],
            ],
            "data" => "houseShop_house/",
        ],
    ];

}