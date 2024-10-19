<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\CharacterProjection;
use hiperesp\server\projection\QuestProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\QuestService;

class QuestController extends Controller {

    private CharacterService $characterService;
    private QuestService $questService;

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

//     #[Request(
//         endpoint: '/cf-questreward.asp',
//         inputType: Input::NINJA2,
//         outputType: Output::XML
//     )]
//     public function reward(\SimpleXMLElement $input): \SimpleXMLElement {
//         // <flash><intNewItemID>20387</intNewItemID><strToken>TOKEN HERE</strToken><intCharID>12345678</intCharID></flash>

//         $newItemID = (int)$input->intNewItemID;

//         // find the item by id and add to the inventory

//         return \simplexml_load_string(<<<XML
// <questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql">
//     <CharItemID>783072142</CharItemID>
// </questreward>
// XML);

//     }

    #[Request(
        endpoint: '/cf-savequeststring.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function saveQuestString(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterService->setQuestString($char, (int)$input->intIndex, (int)$input->intValue);

        return CharacterProjection::instance()->questStringSaved();
    }

}