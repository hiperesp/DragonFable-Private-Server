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

    protected static $collectionSetup = [
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
            "data" => [
                [
                    'username' => 'admin',
                    'password' => '$2a$12$lsPjrmvxJQ44aCh/bG5sFua2M8IVRakjxdcc4XhL1W8sJYhTZ8smO', // password "admin"
                    'email' => 'gabriel@gabstep.com.br',
                    'birthdate' => '1999-12-19',
                    'sessionToken' => NULL,
                    'charsAllowed' => 3,
                    'accessLevel' => 10,
                    'upgrade' => 0,
                    'activationFlag' => 5,
                    'optIn' => 0,
                    'adFlag' => 0,
                    'lastLogin' => null,
                ],
                [
                    'username' => 'user',
                    'password' => '$2a$12$uHEQBEb3K80TOSlaIoCDJ.LLUV1pz4OJMz5q3CjfjEIRra2RzjBQW', // password "user"
                    'email' => 'user@user.com',
                    'birthdate' => '1999-12-19',
                    'sessionToken' => NULL,
                    'charsAllowed' => 3,
                    'accessLevel' => 0,
                    'upgrade' => 0,
                    'activationFlag' => 5,
                    'optIn' => 0,
                    'adFlag' => 0,
                    'lastLogin' => null,
                ]
            ],
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
            ],
            "data" => [

            ],
        ],
        "hair" => [
            "structure" => [
                "id"            => [ "INTEGER", "PRIMARY_KEY" ],
                "name"          => [ "STRING" => 255 ],
                "swf"           => [ "STRING" => 255 ],
                "earVisible"    => [ "BIT" ],
                "gender"        => [ "CHAR" => 1 ], // M or F
            ],
            "data" => [
                [
                    "id" => 3,
                    "name" => 'Hero',
                    "swf" => 'head/M/hair-male-hero.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                ],
                [
                    "id" => 4,
                    "name" => 'Farmboy',
                    "swf" => 'head/M/hair-male-farmboy.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                ],
                [
                    "id" => 5,
                    "name" => 'Spikey',
                    "swf" => 'head/M/hair-male-spikey.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                ],
                [
                    "id" => 6,
                    "name" => 'Noble',
                    "swf" => 'head/M/hair-male-noble.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                ],
                [
                    "id" => 7,
                    "name" => 'Care Free',
                    "swf" => 'head/M/hair-male-carefree.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                ],
                [
                    "id" => 8,
                    "name" => 'Ponytail',
                    "swf" => 'head/M/hair-male-ponytail.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                ],
                [
                    "id" => 9,
                    "name" => 'Rocker',
                    "swf" => 'head/M/hair-male-rocker.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                ],
                [
                    "id" => 10,
                    "name" => 'Roman',
                    "swf" => 'head/M/hair-male-roman.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                ],
                [
                    "id" => 11,
                    "name" => 'Braided',
                    "swf" => 'head/F/hair-female-braided.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 12,
                    "name" => 'Care Free',
                    "swf" => 'head/F/hair-female-carefree.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 13,
                    "name" => 'Cleopatra',
                    "swf" => 'head/F/hair-female-cleopatra.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 14,
                    "name" => 'Curly',
                    "swf" => 'head/F/hair-female-curly.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 15,
                    "name" => 'Heroine',
                    "swf" => 'head/F/hair-female-heroine.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 16,
                    "name" => 'Long',
                    "swf" => 'head/F/hair-female-long.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 17,
                    "name" => 'Long Braided',
                    "swf" => 'head/F/hair-female-longbraid.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 18,
                    "name" => 'Ponytail',
                    "swf" => 'head/F/hair-female-ponytail.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 19,
                    "name" => 'Punk',
                    "swf" => 'head/F/hair-female-punk.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
                [
                    "id" => 20,
                    "name" => 'Wet',
                    "swf" => 'head/F/hair-female-wet.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                ],
            ],
        ],
        "race" => [
            "structure" => [
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
                "name"      => [ "STRING" => 20 ],
                "resists"   => [ "STRING" => 255 ],
            ],
            "data" => [
                [
                    "id" => 1,
                    "name" => "Human",
                    "resists" => "",
                ],
                [
                    "id" => 5,
                    "name" => "Elemental",
                    "resists" => "",
                ],
                [
                    "id" => 6,
                    "name" => "Beast",
                    "resists" => "",
                ],
                [
                    "id" => 9,
                    "name" => "Plant",
                    "resists" => "",
                ],
                [
                    "id" => 10,
                    "name" => "Goblinkind",
                    "resists" => "",
                ],
                [
                    "id" => 14,
                    "name" => "Bug",
                    "resists" => "",
                ],
                [
                    "id" => 18,
                    "name" => "Golem",
                    "resists" => "",
                ],
            ],
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
            "data" => [
                [
                    "id" => 2,
                    "name" => "Warrior",
                    "element" => "Metal",
                    "swf" => "class-2016warrior-r3.swf",
                    "armorId" => 1,
                    "weaponId" => 1,
                    "savable" => 2,
                ],
                [
                    "id" => 3,
                    "name" => "Mage",
                    "element" => "Nature",
                    "swf" => "class-2016mage-r3.swf",
                    "armorId" => 2,
                    "weaponId" => 2,
                    "savable" => 2,
                ],
                [
                    "id" => 4,
                    "name" => "Rogue",
                    "element" => "Metal",
                    "swf" => "class-2016rogue-r5.swf",
                    "armorId" => 3,
                    "weaponId" => 3,
                    "savable" => 2,
                ],
            ],
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
            "data" => [
                [
                    "id" => 1,
                    "name" => "Plate Mail",
                    "description" => "The shiny armor of Warriors!",
                    "designInfo"  => "",
                    "resists" => "Darkness,5,Light,5",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 2,
                    "name" => "Robes",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "Darkness,5,Light,5",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 3,
                    "name" => "Plate Mail",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "Darkness,5,Light,5",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 4,
                    "name" => "Rags",
                    "description" => "Stinky Sneevil Garb",
                    "designInfo"  => "",
                    "resists" => "",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 5,
                    "name" => "Think Skin",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 6,
                    "name" => "Leaves",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 7,
                    "name" => "Thick Scales",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 1,
                    "block" => 0,
                ],
                [
                    "id" => 8,
                    "name" => "None",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 9,
                    "name" => "Thick Hide",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 1,
                    "block" => 0,
                ],
                [
                    "id" => 10,
                    "name" => "None",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "Water,200,Ice,25,Fire,-50,Energy,-50",
                    "defenseMelee" => 5,
                    "defensePierce" => 0,
                    "defenseMagic" => 0,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 11,
                    "name" => "None",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "Energy,200,Ice,-50,Water,-50",
                    "defenseMelee" => 5,
                    "defensePierce" => 0,
                    "defenseMagic" => 0,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 12,
                    "name" => "None",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "Wind,200,Stone,-50",
                    "defenseMelee" => 5,
                    "defensePierce" => 0,
                    "defenseMagic" => 0,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 13,
                    "name" => "Water Skin",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "Water,200,Ice,25,Fire,-50,Energy,-50",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 14,
                    "name" => "Wind Skin",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "Wind,200,Stone,-50",
                    "defenseMelee" => 2,
                    "defensePierce" => 0,
                    "defenseMagic" => 0,
                    "parry" => 0,
                    "dodge" => 5,
                    "block" => 0,
                ],
                [
                    "id" => 15,
                    "name" => "Electric Skin",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "Energy,200,Ice,-50,Water,-50",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 16,
                    "name" => "Chiten",
                    "description" => "hard and shiney",
                    "designInfo"  => "",
                    "resists" => "",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
                [
                    "id" => 17,
                    "name" => "None",
                    "description" => "",
                    "designInfo"  => "",
                    "resists" => "",
                    "defenseMelee" => 0,
                    "defensePierce" => 0,
                    "defenseMagic" => 0,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ]
            ],
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
            "data" => [
                [
                    "id" => 1,
                    "name" => "Longsword",
                    "description" => "A two handed long sword... of justice!",
                    "designInfo" => "none",
                    "resists" => "",
                    "level" => 1,
                    "icon" => "sword",
                    "type" => "Melee",
                    "itemType" => "Sword",
                    "critical" => 0,
                    "damageMin" => 5,
                    "damageMax" => 10,
                    "bonus" => 1,
                    "swf" => "",
                ],
                [
                    "id" => 2,
                    "name" => "Staff",
                    "description" => "A sturdy staff that channels Magic energy.",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 1,
                    "icon" => "staff",
                    "type" => "Magic",
                    "itemType" => "Staff",
                    "critical" => 0,
                    "damageMin" => 5,
                    "damageMax" => 10,
                    "bonus" => 1,
                    "swf" => "",
                ],
                [
                    "id" => 3,
                    "name" => "Dagger",
                    "description" => "A pair of sharp and deadly blades.",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 1,
                    "icon" => "dagger",
                    "type" => "pierce",
                    "itemType" => "Dagger",
                    "critical" => 0,
                    "damageMin" => 5,
                    "damageMax" => 10,
                    "bonus" => 1,
                    "swf" => "",
                ],
                [
                    "id" => 4,
                    "name" => "Blades",
                    "description" => "Stabbies!",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "Blades",
                    "critical" => 5,
                    "damageMin" => 2,
                    "damageMax" => 10,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 5,
                    "name" => "none",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "none",
                    "critical" => 0,
                    "damageMin" => 3,
                    "damageMax" => 8,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 6,
                    "name" => "none",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "none",
                    "critical" => 0,
                    "damageMin" => 5,
                    "damageMax" => 11,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 7,
                    "name" => "Claws and Teeth",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Magic",
                    "itemType" => "",
                    "critical" => 2,
                    "damageMin" => 8,
                    "damageMax" => 16,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 8,
                    "name" => "",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 2,
                    "damageMin" => 10,
                    "damageMax" => 28,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 9,
                    "name" => "Claws and Teeth",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 2,
                    "damageMin" => 1,
                    "damageMax" => 4,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 10,
                    "name" => "",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 2,
                    "damageMin" => 15,
                    "damageMax" => 32,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 11,
                    "name" => "None",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 1,
                    "damageMin" => 6,
                    "damageMax" => 9,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 12,
                    "name" => "None",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Magic",
                    "itemType" => "",
                    "critical" => 1,
                    "damageMin" => 6,
                    "damageMax" => 9,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 13,
                    "name" => "Water Fists",
                    "description" => "wet",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 2,
                    "damageMin" => 7,
                    "damageMax" => 12,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 14,
                    "name" => "Wind Fists",
                    "description" => "windy",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 2,
                    "damageMin" => 7,
                    "damageMax" => 12,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 15,
                    "name" => "Stinger",
                    "description" => "Allergic!",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 5,
                    "damageMin" => 2,
                    "damageMax" => 6,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 16,
                    "name" => "Stinger",
                    "description" => "Allergic!",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 1,
                    "damageMin" => 2,
                    "damageMax" => 8,
                    "bonus" => 0,
                    "swf" => "",
                ],
                [
                    "id" => 17,
                    "name" => "None",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "Bacon,50",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 3,
                    "damageMin" => 15,
                    "damageMax" => 23,
                    "bonus" => 5,
                    "swf" => "",
                ],
                [
                    "id" => 18,
                    "name" => "None",
                    "description" => "",
                    "designInfo" => "",
                    "resists" => "Bacon,50",
                    "level" => 0,
                    "icon" => "",
                    "type" => "Melee",
                    "itemType" => "",
                    "critical" => 3,
                    "damageMin" => 15,
                    "damageMax" => 23,
                    "bonus" => 0,
                    "swf" => "",
                ],
            ],
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
            "data" => [
                [
                    "id"    => 3,
                    "swf"  => "towns/Oaklore/town-oaklore-loader-r1.swf",
                    "swfX"  => "none",
                    "extra" => "",
                ],
                [
                    "id"    => 40,
                    "swf"  => "towns/Oaklore/town-oaklore-2019.swf",
                    "swfX"  => "none",
                    "extra" => "oakloretown=towns/Oaklore/town-oaklore-2019.swf;oaklore=towns/Oaklore/zone-oaklore-forest.swf;map=maps/map-oaklore.swf;Sirvey=towns/Oaklore/town-sirvey.swf;Maya=towns/Oaklore/shop-maya-new.swf",
                ],
                [
                    "id"               => 54,
                    "name"             => "A Hero is Bored",
                    "description"      => "Your origins are a mystery, but your legend begins here in the forest of Oaklore!",
                    "complete"         => "Your origins are a mystery, but your legend begins here in the forest of Oaklore!",
                    "swf"              => "towns/Oaklore/quest-oaklore-intro2020-r1.swf",
                    "swfX"             => "none",
                    "maxSilver"        => 0,
                    "maxGold"          => 100,
                    "maxGems"          => 0,
                    "maxExp"           => 50000,
                    "minTime"          => 0,
                    "counter"          => 500000,
                    "extra"            => "",
                    "dailyIndex"       => 0,
                    "dailyReward"      => 1,
                    "monsterMinLevel"  => 0,
                    "monsterMaxLevel"  => 5,
                    "monsterType"      => "Forest",
                    "monsterGroupSwf"  => "mset-forest-r1.swf",
                ],
                [
                    "id"               => 59,
                    "name"             => "The VurrMen Ruins",
                    "description"      => "These ruins once stood tall and proud, but all things change. Hundreds of years have passed and the ruins of the great city are now infested with VurrMen and Tuskmongers, drawn by the many mysterious objects that can still be found here.",
                    "complete"         => "These ruins once stood tall and proud, but all things change. Hundreds of years have passed and the ruins of the great city are now infested with VurrMen and Tuskmongers, drawn by the many mysterious objects that can still be found here.",
                    "swf"              => "random/random-ruins-r1.swf",
                    "swfX"             => "none",
                    "maxSilver"        => 0,
                    "maxGold"          => 2000,
                    "maxGems"          => 0,
                    "maxExp"           => 50000,
                    "minTime"          => 0,
                    "counter"          => 500000,
                    "extra"            => "",
                    "dailyIndex"       => 0,
                    "dailyReward"      => 1,
                    "monsterMinLevel"  => 1,
                    "monsterMaxLevel"  => 99,
                    "monsterType"      => "Rats",
                    "monsterGroupSwf"  => "mset-oaklore-r1.swf",
                ],
                [
                    "id"               => 64,
                    "name"             => "Sir Jing's Weapons",
                    "description"      => "You find yourself at the base of a mountain surrounded by storm elementals. They have gathered to use the power of Sir Jing's weapons to create a never-ending storm!",
                    "complete"         => "You have fought your way up the mountain and stopped the storm elementals from summoning a never-ending storm! For the sake of the world, you must take Sir Jing's weapon for safe keeping.",
                    "swf"              => "quests/quest-sirjing-new-r2.swf",
                    "swfX"             => "quests/quest-sirjing-new-x.swf",
                    "maxSilver"        => 4,
                    "maxGold"          => 2000,
                    "maxGems"          => 0,
                    "maxExp"           => 50000,
                    "minTime"          => 0,
                    "counter"          => 500000,
                    "extra"            => "",
                    "dailyIndex"       => 0,
                    "dailyReward"      => 1,
                    "monsterMinLevel"  => 2,
                    "monsterMaxLevel"  => 5,
                    "monsterType"      => "Wind, Water, Energy",
                    "monsterGroupSwf"  => "mset-storm-r2.swf",
                ],
                [
                    "id"               => 101,
                    "name"             => "Return To The Intro",
                    "description"      => "We are not sure why you want to go back to that cliff. I guess the view is pretty nice.",
                    "complete"         => "You have done pretty much everything that you can do here.",
                    "swf"              => "towns/Oaklore/quest-oaklore-return.swf",
                    "swfX"             => "none",
                    "maxSilver"        => 1,
                    "maxGold"          => 500,
                    "maxGems"          => 0,
                    "maxExp"           => 50000,
                    "minTime"          => 0,
                    "counter"          => 500000,
                    "extra"            => "",
                    "dailyIndex"       => 0,
                    "dailyReward"      => 1,
                    "monsterMinLevel"  => 0,
                    "monsterMaxLevel"  => 5,
                    "monsterType"      => "Forest",
                    "monsterGroupSwf"  => "mset-forest-r1.swf",
                ],
                [
                    "id"               => 103,
                    "name"             => "The Sweetest Thing",
                    "description"      => "Sir Junn in Oaklore Keep has asked you to head to the hive of the Oaklore Buzzers and recover a small jar of Royal Honey, the sweetest substance in the realm.",
                    "complete"         => "You defeated the Royal Buzzer and with the honey sample in hand you head back to Oaklore for a well deserved bath. You're very very very sticky.",
                    "swf"              => "quests/quest-beehive-r3.swf",
                    "swfX"             => "none",
                    "maxSilver"        => 0,
                    "maxGold"          => 2000,
                    "maxGems"          => 0,
                    "maxExp"           => 50000,
                    "minTime"          => 0,
                    "counter"          => 500000,
                    "extra"            => "",
                    "dailyIndex"       => 0,
                    "dailyReward"      => 1,
                    "monsterMinLevel"  => 1,
                    "monsterMaxLevel"  => 5,
                    "monsterType"      => "Bugs",
                    "monsterGroupSwf"  => "mset-beehive-r1.swf",
                ],
                [
                    "id"    => 933,
                    "name"  => "Prologue",
                    "swf"   => "town-prologuechoice-r7.swf",
                    "swfX"  => "none",
                    "extra" => "",
                ],
                [
                    "id"    => 934,
                    "swf"   => "towns/3Oaklore/town-oaklore-loader-r1.swf",
                    "swfX"  => "none",
                    "extra" => "",
                ],
                [
                    "id"    => 935,
                    "swf"   => "towns/3Oaklore/town-3oaklore-r1.swf",
                    "swfX"  => "none",
                    "extra" => "oakloretown=towns/3Oaklore/town-3oaklore-r1.swf;oaklore=towns/3Oaklore/zone-oaklore-forest.swf;map=maps/map-oaklore.swf;Sirvey=towns/Oaklore/town-sirvey.swf;Maya=towns/3Oaklore/shop-maya.swf",
                ],
                [
                    "id"              => 938,
                    "name"            => "A Hero Is Thawed",
                    "description"     => "Your origins are frosty, but your legend begins here in the forest of Oaklore!",
                    "complete"        => "Your origins are frosty, but your legend begins here in the forest of Oaklore!",
                    "swf"             => "towns/3Oaklore/quest-3oaklore-intro-r5.swf",
                    "swfX"            => "none",
                    "maxSilver"       => 0,
                    "maxGold"         => 100,
                    "maxGems"         => 0,
                    "maxExp"          => 50000,
                    "minTime"         => 0,
                    "counter"         => 500000,
                    "extra"           => "",
                    "dailyIndex"      => 0,
                    "dailyReward"     => 1,
                    "monsterMinLevel" => 0,
                    "monsterMaxLevel" => 100,
                    "monsterType"     => "manahunter",
                    "monsterGroupSwf" => "mset-3Oak-manahunter-r3.swf",
                ]
            ],
        ],
        "quest_monster" => [
            "structure" => [
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
                "questId"   => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "quest",   "field" => "id" ] ],
                "monsterId" => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "monster", "field" => "id" ] ],
            ],
            "data" => [
                [
                    "questId" => 54,
                    "monsterId" => 5,
                ],
                [
                    "questId" => 54,
                    "monsterId" => 6,
                ],
                [
                    "questId" => 54,
                    "monsterId" => 7,
                ],
                [
                    "questId" => 54,
                    "monsterId" => 687,
                ],
                [
                    "questId" => 59,
                    "monsterId" => 69,
                ],
                [
                    "questId" => 59,
                    "monsterId" => 73,
                ],
                [
                    "questId" => 59,
                    "monsterId" => 76,
                ],
                [
                    "questId" => 59,
                    "monsterId" => 77,
                ],
                [
                    "questId" => 64,
                    "monsterId" => 78,
                ],
                [
                    "questId" => 64,
                    "monsterId" => 79,
                ],
                [
                    "questId" => 64,
                    "monsterId" => 80,
                ],
                [
                    "questId" => 64,
                    "monsterId" => 81,
                ],
                [
                    "questId" => 64,
                    "monsterId" => 82,
                ],
                [
                    "questId" => 64,
                    "monsterId" => 83,
                ],
                [
                    "questId" => 64,
                    "monsterId" => 676,
                ],
                [
                    "questId" => 64,
                    "monsterId" => 336,
                ],
                [
                    "questId" => 103,
                    "monsterId" => 151,
                ],
                [
                    "questId" => 103,
                    "monsterId" => 152,
                ],
                [
                    "questId" => 103,
                    "monsterId" => 687,
                ],
                [
                    "questId" => 938,
                    "monsterId" => 714,
                ],
                [
                    "questId" => 103,
                    "monsterId" => 714,
                ],
                [
                    "questId" => 103,
                    "monsterId" => 714,
                ],
                [
                    "questId" => 103,
                    "monsterId" => 714,
                ],
                [
                    "questId" => 103,
                    "monsterId" => 824,
                ],
            ],
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
            "data" => [
                [
                    "id" => 5,

                    "name" => "Sneevil",

                    "level" => 1,
                    "experience" => 5,

                    "hitPoints" => 5,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 1,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Metal",

                    "raceId" => 10,
                    "armorId" => 4,
                    "weaponId" => 4,

                    "movName" => "sneevil",
                    "swf" => "none",
                ],
                [
                    "id" => 6,

                    "name" => "Gorillaphant",

                    "level" => 5,
                    "experience" => 38,

                    "hitPoints" => 104,
                    "manaPoints" => 0,
                    
                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Nature",

                    "raceId" => 6,
                    "armorId" => 5,
                    "weaponId" => 5,

                    "movName" => "gorillaphant",
                    "swf" => "none",
                ],
                [
                    "id" => 7,

                    "name" => "Seed Spitter",

                    "level" => 3,
                    "experience" => 15,

                    "hitPoints" => 37,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 1,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Nature",

                    "raceId" => 9,
                    "armorId" => 6,
                    "weaponId" => 6,

                    "movName" => "seedspitter",
                    "swf" => "none",
                ],
                [
                    "id" => 69,

                    "name" => "VurrMan",

                    "level" => 4,
                    "experience" => 21,

                    "hitPoints" => 26,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 5,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Metal",

                    "raceId" => 6,
                    "armorId" => 8,
                    "weaponId" => 8,

                    "movName" => "verman",
                    "swf" => "none",
                ],
                [
                    "id" => 73,

                    "name" => "Tuskmonger",

                    "level" => 7,
                    "experience" => 35,

                    "hitPoints" => 43,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 1,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "F",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Nature",

                    "raceId" => 6,
                    "armorId" => 9,
                    "weaponId" => 9,

                    "movName" => "boar",
                    "swf" => "none",
                ],
                [
                    "id" => 76,

                    "name" => "Vurrman Hoarder",

                    "level" => 5,
                    "experience" => 32,

                    "hitPoints" => 32,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 5,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Metal",

                    "raceId" => 6,
                    "armorId" => 8,
                    "weaponId" => 8,

                    "movName" => "vurrman2",
                    "swf" => "none",
                ],
                [
                    "id" => 77,

                    "name" => "Vurrman Plaguebringer",

                    "level" => 6,
                    "experience" => 45,

                    "hitPoints" => 40,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 5,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Metal",

                    "raceId" => 18,
                    "armorId" => 8,
                    "weaponId" => 10,

                    "movName" => "vurrman3",
                    "swf" => "none",
                ],
                [
                    "id" => 78,

                    "name" => "Small Puddle",

                    "level" => 3,
                    "experience" => 20,

                    "hitPoints" => 25,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "O",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Water",

                    "raceId" => 5,
                    "armorId" => 10,
                    "weaponId" => 11,

                    "movName" => "puddle",
                    "swf" => "none",
                ],
                [
                    "id" => 79,

                    "name" => "Shockwisp",

                    "level" => 3,
                    "experience" => 20,

                    "hitPoints" => 25,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "O",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Energy",

                    "raceId" => 5,
                    "armorId" => 11,
                    "weaponId" => 11,

                    "movName" => "energywisp",
                    "swf" => "none",
                ],
                [
                    "id" => 80,

                    "name" => "Thunderhead",

                    "level" => 3,
                    "experience" => 20,

                    "hitPoints" => 25,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "O",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Wind",

                    "raceId" => 5,
                    "armorId" => 12,
                    "weaponId" => 12,

                    "movName" => "thunderhead",
                    "swf" => "none",
                ],
                [
                    "id" => 81,

                    "name" => "Flood",

                    "level" => 4,
                    "experience" => 40,

                    "hitPoints" => 40,
                    "manaPoints" => 50,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "O",

                    "hairStyle" => -1,
                    "colorHair" => -1,
                    "colorSkin" => -1,
                    "colorBase" => -1,
                    "colorTrim" => -1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Water",

                    "raceId" => 5,
                    "armorId" => 13,
                    "weaponId" => 13,

                    "movName" => "waterelemental",
                    "swf" => "none",
                ],
                [
                    "id" => 82,

                    "name" => "Tempest",

                    "level" => 4,
                    "experience" => 40,

                    "hitPoints" => 40,
                    "manaPoints" => 50,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => -1,
                    "colorHair" => -1,
                    "colorSkin" => -1,
                    "colorBase" => -1,
                    "colorTrim" => -1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Wind",

                    "raceId" => 5,
                    "armorId" => 14,
                    "weaponId" => 14,

                    "movName" => "windelemental",
                    "swf" => "none",
                ],
                [
                    "id" => 83,

                    "name" => "Positros",

                    "level" => 8,
                    "experience" => 40,

                    "hitPoints" => 40,
                    "manaPoints" => 50,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "O",

                    "hairStyle" => -1,
                    "colorHair" => -1,
                    "colorSkin" => -1,
                    "colorBase" => -1,
                    "colorTrim" => -1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Energy",

                    "raceId" => 5,
                    "armorId" => 15,
                    "weaponId" => 13,

                    "movName" => "energyelemental",
                    "swf" => "none",
                ],
                [
                    "id" => 151,

                    "name" => "Oaklore Buzzer",

                    "level" => 2,
                    "experience" => 9,

                    "hitPoints" => 15,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 1,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "None",

                    "raceId" => 14,
                    "armorId" => 16,
                    "weaponId" => 15,

                    "movName" => "bee2",
                    "swf" => "none",
                ],
                [
                    "id" => 152,

                    "name" => "Honey GuardiAnt",

                    "level" => 3,
                    "experience" => 11,

                    "hitPoints" => 20,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "None",

                    "raceId" => 14,
                    "armorId" => 16,
                    "weaponId" => 16,

                    "movName" => "metalant",
                    "swf" => "none",
                ],
                [
                    "id" => 336,

                    "name" => "Energy Elemental",

                    "level" => 8,
                    "experience" => 40,

                    "hitPoints" => 40,
                    "manaPoints" => 50,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "O",

                    "hairStyle" => -1,
                    "colorHair" => -1,
                    "colorSkin" => -1,
                    "colorBase" => -1,
                    "colorTrim" => -1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Energy",

                    "raceId" => 5,
                    "armorId" => 15,
                    "weaponId" => 13,

                    "movName" => "energyelemental",
                    "swf" => "none",
                ],
                [
                    "id" => 676,

                    "name" => "Lovey Bear",

                    "level" => 5,
                    "experience" => 25,

                    "hitPoints" => 104,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 2,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "None",

                    "raceId" => 6,
                    "armorId" => 5,
                    "weaponId" => 5,

                    "movName" => "hhdbear2",
                    "swf" => "none",
                ],
                [
                    "id" => 687,

                    "name" => "Pip",

                    "level" => 7,
                    "experience" => 35,

                    "hitPoints" => 43,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 1,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "F",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Light",

                    "raceId" => 18,
                    "armorId" => 7,
                    "weaponId" => 7,

                    "movName" => "pip",
                    "swf" => "none",
                ],
                [
                    "id" => 714,

                    "name" => "ManaHunter",

                    "level" => 15,
                    "experience" => 100,

                    "hitPoints" => 58,
                    "manaPoints" => 0,

                    "silver" => 0,
                    "gold" => 10,
                    "gems" => 0,
                    "coins" => 0,

                    "gender" => "M",

                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,

                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,

                    "element" => "Metal",

                    "raceId" => 1,
                    "armorId" => 17,
                    "weaponId" => 17,

                    "movName" => "manahunter",
                    "swf" => "none",
                ],
                [
                    "id" => 824,
                    "name" => "ManaHunter",
                    "level" => 15,
                    "experience" => 100,
                    "hitPoints" => 58,
                    "manaPoints" => 0,
                    "silver" => 0,
                    "gold" => 10,
                    "gems" => 0,
                    "coins" => 0,
                    "gender" => "M",
                    "hairStyle" => 1,
                    "colorHair" => 1,
                    "colorSkin" => 1,
                    "colorBase" => 1,
                    "colorTrim" => 1,
                    "strength" => 0,
                    "dexterity" => 0,
                    "intelligence" => 0,
                    "luck" => 0,
                    "charisma" => 0,
                    "endurance" => 0,
                    "wisdom" => 0,
                    "element" => "Metal",
                    "raceId" => 1,
                    "armorId" => 17,
                    "weaponId" => 18,
                    "movName" => "manahunter2",
                    "swf" => "none",
                ],
            ],
        ],
        "guild" => [
            "structure" => [
                "id"    => [ "INTEGER", "PRIMARY_KEY" ],
                "name"  => [ "STRING" => 255 ],
            ],
            "data" => [
                [ "id" => 1, "name" => "None" ]
            ],
        ]
    ];

}