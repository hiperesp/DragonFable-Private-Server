<?php
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
        throw DFException::fromCode(DFException::QUEST_NOT_FOUND);
    }

    public function getByCharacter(CharacterVO $character): QuestVO {
        return $this->getById($character->questId);
    }

}