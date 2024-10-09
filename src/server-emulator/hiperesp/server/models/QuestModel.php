<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\QuestVO;

class QuestModel extends Model {

    const COLLECTION = 'quest';

    public function getById(int $questId): QuestVO {
        $quest = $this->storage->select(self::COLLECTION, ['id' => $questId]);
        if(isset($quest[0]) && $quest = $quest[0]) {
            return new QuestVO($quest);
        }
        throw new DFException(DFException::QUEST_NOT_FOUND);
    }

    public function getByChar(CharacterVO $char): QuestVO {
        return $this->getById($char->questId);
    }

}