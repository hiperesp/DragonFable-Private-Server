<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ClassVO;

class ClassModel extends Model {

    public const COLLECTION = 'class';

    public function getById(int $classId): ClassVO {
        $char = $this->storage->select(self::COLLECTION, ['id' => $classId]);
        if(isset($char[0]) && $char = $char[0]) {
            return new ClassVO($char);
        }
        throw DFException::fromCode(DFException::USER_NOT_FOUND);
    }

    public function getByCharacter(CharacterVO $character): ClassVO {
        return $this->getById($character->classId);
    }

}