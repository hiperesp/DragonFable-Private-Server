<?php
namespace hiperesp\server\vo;

class MonsterVO extends ValueObject {

    private SettingsVO $settings;

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
        parent::__construct($monster);

        $this->experience = $this->experience * $this->settings->experienceMultiplier;
        $this->silver = $this->silver * $this->settings->silverMultiplier;
        $this->gold = $this->gold * $this->settings->goldMultiplier;
        $this->gems = $this->gems * $this->settings->gemsMultiplier;
    }

}
