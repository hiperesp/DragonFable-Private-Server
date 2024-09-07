<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\QuestModel;
use hiperesp\server\models\SettingsModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\projection\CharacterProjection;
use hiperesp\server\projection\QuestProjection;

class QuestController extends Controller {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private QuestModel $questModel;
    private SettingsModel $settingsModel;

    #[Request(
        endpoint: '/cf-questload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $quest = $this->questModel->getById((int)$input->intQuestID);

        return QuestProjection::instance()->loaded($quest);
    }

    // NEED ATTENTION
    #[Request(
        endpoint: '/cf-expsave.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function expSave(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $quest = $this->questModel->getById((int)$input->intQuestID);

        $this->characterModel->applyExpSave($this->settingsModel->getSettings(), $char, $quest, [
            'experience' => (int)$input->intExp,
            'gems' => (int)$input->intGems,
            'gold' => (int)$input->intGold,
            'silver' => (int)$input->intSilver
        ]);

        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID); // reload the character

        return CharacterProjection::instance()->expSaved($char);
    }

    #[Request(
        endpoint: '/cf-questcomplete-Mar2011.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function complete_mar2011(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $quest = $this->questModel->getById((int)$input->intQuestID);

        $this->characterModel->applyQuestRewards($this->settingsModel->getSettings(), $char, $quest, [
            'waveCount' => (int)$input->intWaveCount,
            'rare' => (int)$input->intRare,
            'war' => (int)$input->intWar,
            'lootID' => (int)$input->intLootID,
            'experience' => (int)$input->intExp,
            'gold' => (int)$input->intGold,
        ]);

        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID); // reload the character

        return CharacterProjection::instance()->questCompletedMar2011($quest, $char, []);
    }

    // [WIP]
    #[Request(
        endpoint: '/cf-questreward.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function reward(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intNewItemID>20387</intNewItemID><strToken>TOKEN HERE</strToken><intCharID>12345678</intCharID></flash>

        $newItemID = (int)$input->intNewItemID;

        // find the item by id and add to the inventory

        return \simplexml_load_string(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <CharItemID>783072142</CharItemID>
</questreward>
XML);

    }

    #[Request(
        endpoint: '/cf-savequeststring.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function saveQuestString(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $this->characterModel->setQuestString($char, (int)$input->intIndex, (int)$input->intValue);

        return CharacterProjection::instance()->questStringSaved($char);
    }

}