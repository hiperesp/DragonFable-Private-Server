<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\vo\ClassVO;
use hiperesp\server\vo\ItemVO;
use hiperesp\server\vo\MonsterVO;

class WeaponModel extends ItemModel {

    public function getByClass(ClassVO $class): ItemVO {
        return $this->getById($class->weaponId);
    }

    public function getByMonster(MonsterVO $monster): ItemVO {
        return $this->getById($monster->weaponId);
    }

}