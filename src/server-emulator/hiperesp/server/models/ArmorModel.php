<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\vo\ClassVO;
use hiperesp\server\vo\ItemVO;
use hiperesp\server\vo\MonsterVO;

class ArmorModel extends ItemModel {

    public function getByClass(ClassVO $class): ItemVO {
        return $this->getById($class->armorId);
    }

    public function getByMonster(MonsterVO $monster): ItemVO {
        return $this->getById($monster->armorId);
    }

}