<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ClassVO;

class ClassModel extends Model {

    const COLLECTION = 'class';

    public function getById(int $classId): ClassVO {
        $char = $this->storage->select(self::COLLECTION, ['id' => $classId]);
        if(isset($char[0]) && $char = $char[0]) {
            return new ClassVO($char);
        }
        throw new DFException(DFException::CLASS_NOT_FOUND);
    }

    public function getByChar(CharacterVO $char): ClassVO {
        return $this->getById($char->classId);
    }

}