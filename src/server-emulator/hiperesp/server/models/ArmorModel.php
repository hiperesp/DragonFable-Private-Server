<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\ArmorVO;
use hiperesp\server\vo\ClassVO;

class ArmorModel extends Model {

    public const COLLECTION = 'armor';

    public function getById(int $armorId): ArmorVO {
        $armor = $this->storage->select(self::COLLECTION, ['id' => $armorId]);
        if(isset($armor[0]) && $armor = $armor[0]) {
            return new ArmorVO($armor);
        }
        throw DFException::fromCode(DFException::ARMOR_NOT_FOUND);
    }

    public function getByClass(ClassVO $class): ArmorVO {
        return $this->getById($class->armorId);
    }

}