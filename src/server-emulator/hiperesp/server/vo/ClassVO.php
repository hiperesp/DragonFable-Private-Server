<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\WeaponModel;

class ClassVO extends ValueObject {
    public readonly int $id;

    #[Inject] private ArmorModel $armorModel;
    #[Inject] private WeaponModel $weaponModel;

    public readonly string $name;
    public readonly string $element;
    public readonly string $swf;

    public readonly int $armorId;
    public readonly int $weaponId;
    public readonly int $savable;

    public readonly string $equippable;

    public function getArmor(): ItemVO {
        return $this->armorModel->getByClass($this);
    }

    public function getWeapon(): ItemVO {
        return $this->weaponModel->getByClass($this);
    }

}
