<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\RaceVO;

class RaceModel extends Model {

    public const COLLECTION = 'race';

    public function getById(int $classId): RaceVO {
        $char = $this->storage->select(self::COLLECTION, ['id' => $classId]);
        if(isset($char[0]) && $char = $char[0]) {
            return new RaceVO($char);
        }
        throw DFException::fromCode(DFException::USER_NOT_FOUND);
    }

    public function getByCharacter(CharacterVO $character): RaceVO {
        return $this->getById($character->classId);
    }

}