<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\HairVO;
use hiperesp\server\vo\CharacterVO;

class HairModel extends Model {

    const COLLECTION = 'hair';

    public function getById(int $hairId): HairVO {
        $hair = $this->storage->select(self::COLLECTION, ['id' => $hairId]);
        if(isset($hair[0]) && $hair = $hair[0]) {
            return new HairVO($hair);
        }
        throw new DFException(DFException::HAIR_NOT_FOUND);
    }

    public function getByChar(CharacterVO $char): HairVO {
        return $this->getById($char->hairId);
    }

}