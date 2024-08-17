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
                "gender"        => [ "CHAR" => 1 ],
                "price"         => [ "INTEGER" ],
                "frame"         => [ "INTEGER" ],
                "raceId"        => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "race", "field" => "id" ] ],
            ],
            "data" => [
                [
                    "id" => 3,
                    "name" => 'Hero',
                    "swf" => 'head/M/hair-male-hero.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 4,
                    "name" => 'Farmboy',
                    "swf" => 'head/M/hair-male-farmboy.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 5,
                    "name" => 'Spikey',
                    "swf" => 'head/M/hair-male-spikey.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 6,
                    "name" => 'Noble',
                    "swf" => 'head/M/hair-male-noble.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 7,
                    "name" => 'Care Free',
                    "swf" => 'head/M/hair-male-carefree.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 8,
                    "name" => 'Ponytail',
                    "swf" => 'head/M/hair-male-ponytail.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 9,
                    "name" => 'Rocker',
                    "swf" => 'head/M/hair-male-rocker.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 10,
                    "name" => 'Roman',
                    "swf" => 'head/M/hair-male-roman.swf',
                    "earVisible" => 1,
                    "gender" => 'M',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 11,
                    "name" => 'Braided',
                    "swf" => 'head/F/hair-female-braided.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 12,
                    "name" => 'Care Free',
                    "swf" => 'head/F/hair-female-carefree.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 13,
                    "name" => 'Cleopatra',
                    "swf" => 'head/F/hair-female-cleopatra.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 14,
                    "name" => 'Curly',
                    "swf" => 'head/F/hair-female-curly.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 15,
                    "name" => 'Heroine',
                    "swf" => 'head/F/hair-female-heroine.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 16,
                    "name" => 'Long',
                    "swf" => 'head/F/hair-female-long.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 17,
                    "name" => 'Long Braided',
                    "swf" => 'head/F/hair-female-longbraid.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 18,
                    "name" => 'Ponytail',
                    "swf" => 'head/F/hair-female-ponytail.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 19,
                    "name" => 'Punk',
                    "swf" => 'head/F/hair-female-punk.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
                [
                    "id" => 20,
                    "name" => 'Wet',
                    "swf" => 'head/F/hair-female-wet.swf',
                    "earVisible" => 1,
                    "gender" => 'F',
                    "price" => 0,
                    "frame" => '1',
                    "raceId" => '1'
                ],
            ],
        ],
        "race" => [
            "structure" => [
                "id"    => [ "INTEGER", "PRIMARY_KEY" ],
                "name"  => [ "STRING" => 20 ],
            ],
            "data" => [
                [ "id" => 1, "name" => "Human", ],
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
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
                "name"      => [ "STRING" => 255 ],
                "element"   => [ "STRING" => 255 ],
                "swf"       => [ "STRING" => 255 ],
                "armorId"   => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "armor",  "field" => "id" ] ],
                "weaponId"  => [ "INTEGER", "FOREIGN_KEY" => [ "collection" => "weapon", "field" => "id" ] ],
            ],
            "data" => [
                [
                    "id" => 2,
                    "name" => "Warrior",
                    "element" => "Metal",
                    "swf" => "class-2016warrior-r3.swf",
                    "armorId" => 2,
                    "weaponId" => 2,
                ],
                [
                    "id" => 3,
                    "name" => "Mage",
                    "element" => "Nature",
                    "swf" => "class-2016mage-r3.swf",
                    "armorId" => 3,
                    "weaponId" => 3,
                ],
                [
                    "id" => 4,
                    "name" => "Rogue",
                    "element" => "Metal",
                    "swf" => "class-2016rogue-r5.swf",
                    "armorId" => 4,
                    "weaponId" => 4,
                ],
            ],
        ],
        "armor" => [
            "structure" => [
                "id"            => [ "INTEGER", "PRIMARY_KEY" ],
                "name"          => [ "STRING" => 255 ],
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
                    "id" => 2,
                    "name" => "Plate Mail",
                    "description" => "The shiny armor of Warriors!",
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
                    "name" => "Robes",
                    "description" => "",
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
                    "name" => "Plate Mail",
                    "description" => "",
                    "resists" => "Darkness,5,Light,5",
                    "defenseMelee" => 5,
                    "defensePierce" => 5,
                    "defenseMagic" => 5,
                    "parry" => 0,
                    "dodge" => 0,
                    "block" => 0,
                ],
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
                "crit"          => [ "INTEGER" ],
                "damageMin"     => [ "INTEGER" ],
                "damageMax"     => [ "INTEGER" ],
                "bonus"         => [ "INTEGER" ],
                "equippable"    => [ "STRING" ],
                "savable"       => [ "INTEGER" ],
            ],
            "data" => [
                [
                    "id" => 2,
                    "name" => "Longsword",
                    "description" => "A two handed long sword... of justice!",
                    "designInfo" => "none",
                    "resists" => "",
                    "level" => 1,
                    "icon" => "sword",
                    "type" => "Melee",
                    "itemType" => "Sword",
                    "crit" => 0,
                    "damageMin" => 5,
                    "damageMax" => 10,
                    "bonus" => 1,
                    "equippable" => "Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket,Artifact",
                    "savable" => 2,
                ],
                [
                    "id" => 3,
                    "name" => "Staff",
                    "description" => "A sturdy staff that channels Magic energy.",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 1,
                    "icon" => "staff",
                    "type" => "Magic",
                    "itemType" => "Staff",
                    "crit" => 0,
                    "damageMin" => 5,
                    "damageMax" => 10,
                    "bonus" => 1,
                    "equippable" => "Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Helmet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket,Artifact",
                    "savable" => 2,
                ],
                [
                    "id" => 4,
                    "name" => "Dagger",
                    "description" => "A pair of sharp and deadly blades.",
                    "designInfo" => "",
                    "resists" => "",
                    "level" => 1,
                    "icon" => "dagger",
                    "type" => "pierce",
                    "itemType" => "Dagger",
                    "crit" => 0,
                    "damageMin" => 5,
                    "damageMax" => 10,
                    "bonus" => 1,
                    "equippable" => "Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket,Artifact",
                    "savable" => 2,
                ],
            ],
        ],
        "quest" => [
            "structure" => [
                "id"                    => [ "INTEGER", "PRIMARY_KEY" ],
                "name"                  => [ "STRING" => 255 ],
                "description"           => [ "STRING" => 255 ],
                "complete"              => [ "STRING" => 255 ],
                "swf"                   => [ "STRING" => 255 ],
                "swfX"                  => [ "STRING" => 255 ],
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
            "data" => [
                [
                    "id"                    => "933",
                    "name"                  => "Prologue",
                    "description"           => "",
                    "complete"              => "",
                    "swf"                   => "town-prologuechoice-r7.swf",
                    "swfX"                  => "none",
                    "maxSilver"             => "",
                    "maxGold"               => "",
                    "maxGems"               => "",
                    "maxExp"                => "",
                    "minTime"               => "",
                    "counter"               => "",
                    "extra"                 => "",
                    "monsterMinLevel"       => "",
                    "monsterMaxLevel"       => "",
                    "monsterType"           => "",
                    "monsterGroupFileName"  => "",
                    "monsterRefs"           => "",
                    "rewards"               => "",
                ],
            ],
        ],
        "quest_monster" => [
            "structure" => [
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
                "questId"   => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "quest",   "field" => "id" ] ],
                "monsterId" => [ "INTEGER", "DEFAULT" => 0, "FOREIGN_KEY" => [ "collection" => "monster", "field" => "id" ] ],
            ],
            "data" => [],
        ],
        "monster" => [
            "structure" => [
                "id"        => [ "INTEGER", "PRIMARY_KEY" ],
            ],
            "data" => [],
        ],
        "guild" => [
            "structure" => [
                "id"    => [ "INTEGER", "PRIMARY_KEY" ],
                "name"  => [ "STRING" => 255 ],
            ],
            "data" => [
                [ "id" => 1, "name" => "None" ]
            ],
        ],
    ];

}