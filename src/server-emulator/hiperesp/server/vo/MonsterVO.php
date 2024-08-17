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
        $this->id = $monster['id'];

        $this->name = $monster['name'];

        $this->level = $monster['level'];
        $this->experience = $monster['experience'];

        $this->hitPoints = $monster['hitPoints'];
        $this->manaPoints = $monster['manaPoints'];

        $this->silver = $monster['silver'];
        $this->gold = $monster['gold'];
        $this->gems = $monster['gems'];
        $this->coins = $monster['coins'];

        $this->gender = $monster['gender'];

        $this->hairStyle = $monster['hairStyle'];
        $this->colorHair = \hexdec($monster['colorHair']);
        $this->colorSkin = \hexdec($monster['colorSkin']);
        $this->colorBase = \hexdec($monster['colorBase']);
        $this->colorTrim = \hexdec($monster['colorTrim']);

        $this->strength = $monster['strength'];
        $this->dexterity = $monster['dexterity'];
        $this->intelligence = $monster['intelligence'];
        $this->luck = $monster['luck'];
        $this->charisma = $monster['charisma'];
        $this->endurance = $monster['endurance'];
        $this->wisdom = $monster['wisdom'];

        $this->element = $monster['element'];

        $this->raceId = $monster['raceId'];
        $this->armorId = $monster['armorId'];
        $this->weaponId = $monster['weaponId'];

        $this->movName = $monster['movName'];
        $this->swf = $monster['swf'];
    }

}
