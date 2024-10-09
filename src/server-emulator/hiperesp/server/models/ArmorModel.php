<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\vo\ClassVO;
use hiperesp\server\vo\ItemVO;

class ArmorModel extends ItemModel {

    public function getByClass(ClassVO $class): ItemVO {
        return $this->getById($class->armorId);
    }

}