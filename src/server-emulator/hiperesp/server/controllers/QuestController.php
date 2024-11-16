<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\CharacterProjection;
use hiperesp\server\projection\QuestProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\QuestService;

class QuestController extends Controller {

    #[Inject] private CharacterService $characterService;
    #[Inject] private QuestService $questService;

    #[Request(
        endpoint: '/cf-questload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $quest = $this->questService->load((int)$input->intQuestID);

        return QuestProjection::instance()->loaded($quest);
    }

    #[Request(
        endpoint: '/cf-questcomplete-Mar2011.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function complete_mar2011(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $quest = $this->questService->load((int)$input->intQuestID);

        $char = $this->characterService->applyQuestRewards($char, $quest, [
            'waveCount' => (int)$input->intWaveCount,
            'rare' => (int)$input->intRare,
            'war' => (int)$input->intWar,
            'lootID' => (int)$input->intLootID,
            'experience' => (int)$input->intExp,
            'gold' => (int)$input->intGold,
        ]);

        return CharacterProjection::instance()->questCompletedMar2011($quest, $char, []);
    }

    #[Request(
        endpoint: '/cf-questreward.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function reward(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $charItem = $this->characterService->applyQuestItemRewards($char, (int)$input->intNewItemID);

        return CharacterProjection::instance()->questItemReward($charItem);
    }

}