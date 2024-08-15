<?php
namespace hiperesp\server\storage;

abstract class Storage {

    public abstract function select(string $collection, array $where, ?int $limit = 1): array;
    public abstract function insert(string $collection, array $document): array;
    public abstract function update(string $collection, array $where, array $newFields, ?int $limit = 1): bool;
    public abstract function delete(string $collection, array $where, ?int $limit = 1): bool;

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

    protected static $collectionSetup = [
        "user" => [
            "structure" => [
                "id"            => [ "UUID", "GENERATED", "PRIMARY_KEY" ],

                "createdAt"     => [ "DATE_TIME" ],
                "updatedAt"     => [ "DATE_TIME" ],

                "username"      => [ "STRING" => 20,  "UNIQUE" ],
                "password"      => [ "STRING" => 64,  ],
                "email"         => [ "STRING" => 255, "UNIQUE" ],
                "birthdate"     => [ "DATE" ],

                "sessionToken"  => [ "STRING" => 20, "UNIQUE" ],
                "charsAllowed"  => [ "INTEGER", "DEFAULT" => 3 ],
                "accessLevel"   => [ "INTEGER", "DEFAULT" => 10 ],
                "upgrade"       => [ "BIT", "DEFAULT" => 0],
                "activationFlag"=> [ "BIT", "DEFAULT" => 1],
                "optIn"         => [ "BIT", "DEFAULT" => 0],
                "adFlag"        => [ "BIT", "DEFAULT" => 0],

                "lastLogin"     => [ "DATE_TIME", "DEFAULT" => NULL ],
            ],
            "data" => [
                [
                    'id' => null,
                    'createdAt' => '2024-08-15 00:00:00',
                    'updatedAt' => '2024-08-15 00:00:00',
                    'username' => 'admin',
                    'password' => '$2a$12$lsPjrmvxJQ44aCh/bG5sFua2M8IVRakjxdcc4XhL1W8sJYhTZ8smO', // password "admin"
                    'email' => 'gabriel@gabstep.com.br',
                    'birthdate' => '1999-12-19',
                    'sessionToken' => 'cd730b2bdd63e60eb5b2',
                    'charsAllowed' => 3,
                    'accessLevel' => 10,
                    'upgrade' => 0,
                    'activationFlag' => 1,
                    'optIn' => 0,
                    'adFlag' => 0,
                    'lastLogin' => null,
                ],
                [
                    'id' => null,
                    'createdAt' => '2024-08-15 01:00:00',
                    'updatedAt' => '2024-08-15 01:00:00',
                    'username' => 'user',
                    'password' => '$2a$12$uHEQBEb3K80TOSlaIoCDJ.LLUV1pz4OJMz5q3CjfjEIRra2RzjBQW', // password "user"
                    'email' => 'user@user.com',
                    'birthdate' => '1999-12-19',
                    'sessionToken' => '1efc17048970f10118e2',
                    'charsAllowed' => 3,
                    'accessLevel' => 0,
                    'upgrade' => 0,
                    'activationFlag' => 1,
                    'optIn' => 0,
                    'adFlag' => 0,
                    'lastLogin' => null,
                ]
            ],
        ],
        "char" => [
            "structure" => [
                "id"                => [ "UUID", "GENERATED", "PRIMARY_KEY" ],

                "userId"            => [ "UUID", "FOREIGN_KEY" => [ "collection" => "user", "field" => "id" ] ],

                "createdAt"         => [ "DATE_TIME" ],
                "updatedAt"         => [ "DATE_TIME" ],

                "name"              => [ "STRING" => 20 ],

                "level"             => [ "INTEGER", "DEFAULT" => 0 ],
                "experience"        => [ "INTEGER", "DEFAULT" => 0 ],
                "experienceToLevel" => [ "INTEGER", "DEFAULT" => 0 ],

                "hitPoints"         => [ "INTEGER", "DEFAULT" => 0 ],
                "manaPoints"        => [ "INTEGER", "DEFAULT" => 0 ],

                "silver"            => [ "INTEGER", "DEFAULT" => 0 ],
                "gold"              => [ "INTEGER", "DEFAULT" => 0 ],
                "gems"              => [ "INTEGER", "DEFAULT" => 0 ],
                "coins"             => [ "INTEGER", "DEFAULT" => 0 ],

                "maxBagSlots"       => [ "INTEGER", "DEFAULT" => 0 ],
                "maxBankSlots"      => [ "INTEGER", "DEFAULT" => 0 ],
                "maxHouseSlots"     => [ "INTEGER", "DEFAULT" => 0 ],
                "maxHouseItemSlots" => [ "INTEGER", "DEFAULT" => 0 ],

                "hasDragonAmulet"   => [ "INTEGER", "DEFAULT" => 0 ],
                "accessLevel"       => [ "INTEGER", "DEFAULT" => 0 ],

                "gender"            => [ "CHAR" => 1 ],
                "pronoun"           => [ "CHAR" => 1 ],

                "hairId"            => [ "UUID", "FOREIGN_KEY" => [ "collection" => "hair", "field" => "id" ] ],
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

                "status"            => [ "INTEGER", "DEFAULT" => 0 ],
                "daily"             => [ "INTEGER", "DEFAULT" => 0 ],

                "armor"             => [ "CHAR" => 100, "DEFAULT" => "0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],
                "skills"            => [ "CHAR" => 300, "DEFAULT" => "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],
                "quests"            => [ "CHAR" => 300, "DEFAULT" => "000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" ],

                "raceId"            => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "race", "field" => "id" ] ],
                "classId"           => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "class", "field" => "id" ] ],
                "baseClassId"       => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "class", "field" => "id" ] ],

                "guildId"           => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "guild", "field" => "id" ] ],
                "questId"           => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "quest", "field" => "id" ] ],

                "defenseMelee"      => [ "INTEGER", "DEFAULT" => 0 ],
                "defensePierce"     => [ "INTEGER", "DEFAULT" => 0 ],
                "defenseMagic"      => [ "INTEGER", "DEFAULT" => 0 ],
                "parry"             => [ "INTEGER", "DEFAULT" => 0 ],
                "dodge"             => [ "INTEGER", "DEFAULT" => 0 ],
                "block"             => [ "INTEGER", "DEFAULT" => 0 ],
            ],
            "data" => [

            ],
        ],
        "hair" => [
            "structure" => [
                "id"            => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
                "name"          => [ "STRING" => 255 ],
                "swf"           => [ "STRING" => 255 ],
                "earVisible"    => [ "BIT" ],
                "gender"        => [ "CHAR" => 1 ],
                "price"         => [ "INTEGER" ],
                "frame"         => [ "INTEGER" ],
                "raceId"        => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "race", "field" => "id" ] ],
            ],
            "data" => [],
        ],
        "race" => [
            "structure" => [
                "id"    => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
                "name"  => [ "STRING" => 20 ],
            ],
            "data" => [
                [ "name" => "Human", ],
                [ "name" => "Elemental", ],
                [ "name" => "Fairy", ],
                [ "name" => "Undead", ],
                [ "name" => "Golem", ],
                [ "name" => "Fungus", ],
                [ "name" => "Reptilian", ],
                [ "name" => "Goblinkind", ],
                [ "name" => "Beast", ],
                [ "name" => "Plant", ],
                [ "name" => "Bug", ],
                [ "name" => "Dragon", ],
                [ "name" => "Avian", ],
                [ "name" => "Clockwork", ],
                [ "name" => "Food", ],
                [ "name" => "???", ],
                [ "name" => "Clockwork", ],
            ],
        ],
        "class" => [
            "structure" => [
                "id"        => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
                "name"      => [ "STRING" => 255 ],
                "swf"       => [ "STRING" => 255 ],
                "armorId"   => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "armor",  "field" => "id" ] ],
                "weaponId"  => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "weapon", "field" => "id" ] ],
            ],
            "data" => [],
        ],
        "quest" => [
            "structure" => [
                "id"                    => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
                "name"                  => [ "STRING" => 255 ],
                "description"           => [ "STRING" => 255 ],
                "complete"              => [ "STRING" => 255 ],
                "swf"                   => [ "STRING" ],
                "swfX"                  => [ "STRING" ],
                "maxSilver"             => [ "INTEGER" ],
                "maxGold"               => [ "INTEGER" ],
                "maxGems"               => [ "INTEGER" ],
                "maxExp"                => [ "INTEGER" ],
                "minTime"               => [ "INTEGER" ],
                "counter"               => [ "INTEGER" ],
                "extra"                 => [ "STRING" => 4096  ],
                "monsterMinLevel"       => [ "INTEGER" ],
                "monsterMaxLevel"       => [ "INTEGER" ],
                "monsterType"           => [ "STRING" ],
                "monsterGroupFileName"  => [ "STRING" ],
                "monsterRefs"           => [ "INTEGER" ],
                "rewards"               => [ "STRING" ],
            ],
            "data" => [],
        ],
        "quest_monster" => [
            "structure" => [
                "id"        => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
                "questId"   => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "quest",   "field" => "id" ] ],
                "monsterId" => [ "UUID", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "monster", "field" => "id" ] ],
            ],
            "data" => [],
        ],
        "monster" => [
            "structure" => [
                "id"        => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
            ],
            "data" => [],
        ],
        "armor" => [
            "structure" => [
                "id"        => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
            ],
            "data" => [],
        ],
        "weapon" => [
            "structure" => [
                "id"        => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
            ],
            "data" => [],
        ],
        "guild" => [
            "structure" => [
                "id"        => [ "UUID", "GENERATED", "PRIMARY_KEY" ],
            ],
            "data" => [],
        ],
    ];

}