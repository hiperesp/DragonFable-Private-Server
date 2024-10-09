<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\MonsterVO;
use hiperesp\server\vo\QuestVO;

class MonsterModel extends Model {

    const COLLECTION = 'monster';

    const QUEST_ASSOCIATION = 'quest_monster';

    /** @return array<MonsterVO> */
    public function getByQuest(QuestVO $quest): array {
        $monsterIds = \array_map(function(array $monster): int {
            return $monster['monsterId'];
        }, $this->storage->select(self::QUEST_ASSOCIATION, ['questId' => $quest->id], null));

        return \array_map(function(array $monster): MonsterVO {
            return new MonsterVO($monster);
        }, $this->storage->select(self::COLLECTION, ['id' => $monsterIds], null));
    }

    public function getById(int $monsterId): MonsterVO {
        $monster = $this->storage->select(self::COLLECTION, ['id' => $monsterId]);
        if(isset($monster[0]) && $monster = $monster[0]) {
            return new MonsterVO($monster);
        }
        throw new DFException(DFException::MONSTER_NOT_FOUND);
    }

}