<?php
namespace hiperesp\server\storage;

abstract class Storage {

    public abstract function select(string $collection, array $where, ?int $limit = 1): array;
    public abstract function insert(string $collection, array $document): array;
    public abstract function update(string $collection, array $document): bool;
    public abstract function delete(string $collection, array $document): bool;

    public abstract function reset(): void;

    protected abstract function setup(): void;

    private static Storage $instance;
    public static function getStorage(): Storage {
        $storageSettings = $GLOBALS['storage'];
        if(!isset(self::$instance)) {
            self::$instance = new $storageSettings["driver"]($storageSettings["options"]);
            self::$instance->setup();
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
        "user" => [
            "structure" => [
                "id"            => [ "INTEGER", "GENERATED", "PRIMARY_KEY" ],

                "createdAt"     => [ "DATE_TIME", "CREATED_DATETIME" ],
                "updatedAt"     => [ "DATE_TIME", "UPDATED_DATETIME" ],

                "username"      => [ "STRING" => 20,  "UNIQUE" ],
                "password"      => [ "STRING" => 64,  ],
                "email"         => [ "STRING" => 255, "UNIQUE" ],
                "birthdate"     => [ "DATE" ],

                "sessionToken"  => [ "STRING" => 20, "UNIQUE" ],
                "charsAllowed"  => [ "INTEGER", "DEFAULT" => 3 ],
                "accessLevel"   => [ "INTEGER", "DEFAULT" => 10 ],
                "upgrade"       => [ "BIT", "DEFAULT" => 0],
                "activationFlag"=> [ "BIT", "DEFAULT" => 5],
                "optIn"         => [ "BIT", "DEFAULT" => 0],
                "adFlag"        => [ "BIT", "DEFAULT" => 0],

                "lastLogin"     => [ "DATE_TIME", "DEFAULT" => NULL ],
            ],
            "data" => "user.json",
        ],
        "char" => [
            "structure" => [
                "id"                => [ "INTEGER", "GENERATED", "PRIMARY_KEY" ],

                "userId"            => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "user", "field" => "id" ] ],

                "createdAt"         => [ "DATE_TIME", "CREATED_DATETIME" ],
                "updatedAt"         => [ "DATE_TIME", "UPDATED_DATETIME" ],

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

                "hasDragonAmulet"   => [ "INTEGER", "DEFAULT" => 0 ],
                "accessLevel"       => [ "INTEGER", "DEFAULT" => 1 ],

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

                "status"            => [ "INTEGER", "DEFAULT" => 0 ], // intCharStatus, unknown meaning
                "daily"             => [ "INTEGER", "DEFAULT" => 0 ], // daily login? unknown meaning

                "armor"             => [ "CHAR" => 100, "DEFAULT" => "0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],
                "skills"            => [ "CHAR" => 300, "DEFAULT" => "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],
                "quests"            => [ "CHAR" => 300, "DEFAULT" => "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],

                "raceId"            => [ "INTEGER", "DEFAULT" => 1, "FOREIGN_KEY" => [ "collection" => "race", "field" => "id" ] ],
                "classId"           => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "class", "field" => "id" ] ],
                "baseClassId"       => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "class", "field" => "id" ] ],
                "guildId"           => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "guild", "field" => "id" ] ],
                "questId"           => [ "INTEGER", "DEFAULT" => 933, "FOREIGN_KEY" => [ "collection" => "quest", "field" => "id" ] ],

                "lastTimeSeen"      => [ "DATE_TIME", "DEFAULT" => NULL, "INDEX" ],
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
            "data" => "race.json",
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
        "armor" => [
            "structure" => [
                "id"            => [ "INTEGER", "PRIMARY_KEY" ],
                "name"          => [ "STRING" => 255 ],
                "designInfo"    => [ "STRING" => 255 ],
                "description"   => [ "STRING" => 255 ],
                "resists"       => [ "STRING" => 255 ],
                "defenseMelee"  => [ "INTEGER" ],
                "defensePierce" => [ "INTEGER" ],
                "defenseMagic"  => [ "INTEGER" ],
                "parry"         => [ "INTEGER" ],
                "dodge"         => [ "INTEGER" ],
                "block"         => [ "INTEGER" ],
            ],
            "data" => "armor.json",
        ],
        "weapon" => [
            "structure" => [
                "id"            => [ "INTEGER", "PRIMARY_KEY" ],
                "name"          => [ "STRING" => 255 ],
                "description"   => [ "STRING" => 255 ],
                "designInfo"    => [ "STRING" => 255 ],
                "resists"       => [ "STRING" => 255 ],
                "level"         => [ "INTEGER" ],
                "icon"          => [ "STRING" => 255 ],
                "type"          => [ "STRING" => 255 ],
                "itemType"      => [ "STRING" => 255 ],
                "critical"      => [ "INTEGER" ],
                "damageMin"     => [ "INTEGER" ],
                "damageMax"     => [ "INTEGER" ],
                "bonus"         => [ "INTEGER" ],
                "swf"           => [ "STRING" => 255 ],
            ],
            "data" => "weapon.json",
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
            "data" => "quest.json",
        ],
        "quest_monster" => [
            "structure" => [
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
                "questId"   => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "quest",   "field" => "id" ] ],
                "monsterId" => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "monster", "field" => "id" ] ],
            ],
            "data" => "quest_monster/",
        ],
        "monster" => [
            "structure" => [
                "id"            => [ "INTEGER", "GENERATED", "PRIMARY_KEY" ],

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
            "data" => "monster.json",
        ],
        "guild" => [
            "structure" => [
                "id"    => [ "INTEGER", "PRIMARY_KEY" ],
                "name"  => [ "STRING" => 255 ],
            ],
            "data" => "guild.json",
        ]
    ];

}