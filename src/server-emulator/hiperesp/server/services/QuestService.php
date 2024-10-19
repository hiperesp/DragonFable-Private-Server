<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\models\QuestModel;
use hiperesp\server\vo\QuestVO;

class QuestService extends Service {

    private QuestModel $questModel;

    public function load(int $questId): QuestVO {
        return $this->questModel->getById($questId);
    }

}