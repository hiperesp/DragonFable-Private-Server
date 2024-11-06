<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\QuestVO;

class TownService extends QuestService {

    #[Inject] private CharacterModel $characterModel;

    public function changeHome(CharacterVO $char, QuestVO $town): void {
        $this->characterModel->changeHomeTown($char, $town);
    }

}