<?php
namespace hiperesp\server\models;

use hiperesp\server\vo\ClassVO;
use hiperesp\server\vo\ItemVO;

class WeaponModel extends ItemModel {

    public function getByClass(ClassVO $class): ItemVO {
        return $this->getById($class->weaponId);
    }

}