<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\HairVO;
use hiperesp\server\vo\ClassVO;

class HairModel extends Model {

    public const COLLECTION = 'hair';

    public function getById(int $hairId): HairVO {
        $hair = $this->storage->select(self::COLLECTION, ['id' => $hairId]);
        if(isset($hair[0]) && $hair = $hair[0]) {
            return new HairVO($hair);
        }
        throw DFException::fromCode(DFException::HAIR_NOT_FOUND);
    }

    public function getByClass(ClassVO $class): HairVO {
        return $this->getById($class->hairId);
    }

}