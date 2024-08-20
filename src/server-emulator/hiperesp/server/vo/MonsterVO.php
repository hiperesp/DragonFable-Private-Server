<?php
namespace hiperesp\server\vo;

class MonsterVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;

    public readonly int $level;
    public readonly int $experience;

    public readonly int $hitPoints;
    public readonly int $manaPoints;

    public readonly int $silver;
    public readonly int $gold;
    public readonly int $gems;
    public readonly int $coins;

    public readonly string $gender;

    public readonly int $hairStyle;
    public readonly string $colorHair;
    public readonly string $colorSkin;
    public readonly string $colorBase;
    public readonly string $colorTrim;

    public readonly int $strength;
    public readonly int $dexterity;
    public readonly int $intelligence;
    public readonly int $luck;
    public readonly int $charisma;
    public readonly int $endurance;
    public readonly int $wisdom;

    public readonly string $element;

    public readonly int $raceId;
    public readonly int $armorId;
    public readonly int $weaponId;

    public readonly string $movName;
    public readonly string $swf;

    public function __construct(array $monster) {
        $monster['colorHair'] = \hexdec($monster['colorHair']);
        $monster['colorSkin'] = \hexdec($monster['colorSkin']);
        $monster['colorBase'] = \hexdec($monster['colorBase']);
        $monster['colorTrim'] = \hexdec($monster['colorTrim']);
        parent::__construct($monster);
    }

}
