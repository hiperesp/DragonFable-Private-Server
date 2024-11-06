<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\WeaponModel;

class MonsterVO extends ValueObject {

    #[Inject] private ArmorModel $armorModel;
    #[Inject] private WeaponModel $weaponModel;
    #[Inject] private RaceModel $raceModel;
    #[Inject] private SettingsVO $settings;

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

    #[\Override]
    protected function patch(array $monster): array {
        $monster['experience'] = $monster['experience'] * $this->settings->experienceMultiplier;
        $monster['silver'] = $monster['silver'] * $this->settings->silverMultiplier;
        $monster['gold'] = $monster['gold'] * $this->settings->goldMultiplier;
        $monster['gems'] = $monster['gems'] * $this->settings->gemsMultiplier;

        return $monster;
    }

    public function getArmor(): ItemVO {
        return $this->armorModel->getByMonster($this);
    }

    public function getWeapon(): ItemVO {
        return $this->weaponModel->getByMonster($this);
    }

    public function getRace(): RaceVO {
        return $this->raceModel->getByMonster($this);
    }

}
