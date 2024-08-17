<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\ClassVO;
use hiperesp\server\vo\WeaponVO;

class WeaponModel extends Model {

    const COLLECTION = 'weapon';

    public function getById(int $weaponId): WeaponVO {
        $weapon = $this->storage->select(self::COLLECTION, ['id' => $weaponId]);
        if(isset($weapon[0]) && $weapon = $weapon[0]) {
            return new WeaponVO($weapon);
        }
        throw DFException::fromCode(DFException::WEAPON_NOT_FOUND);
    }

    public function getByCharacter(ClassVO $class): WeaponVO {
        return $this->getById($class->weaponId);
    }

}