<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\RaceVO;

class RaceModel extends Model {

    const COLLECTION = 'race';

    public function getById(int $raceId): RaceVO {
        $race = $this->storage->select(self::COLLECTION, ['id' => $raceId]);
        if(isset($race[0]) && $race = $race[0]) {
            return new RaceVO($race);
        }
        throw DFException::fromCode(DFException::RACE_NOT_FOUND);
    }

    public function getByCharacter(CharacterVO $character): RaceVO {
        return $this->getById($character->raceId);
    }

}