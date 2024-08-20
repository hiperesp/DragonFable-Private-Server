<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\MonsterModel;
use hiperesp\server\models\QuestModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\SettingsModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\models\WeaponModel;

class Quest extends Controller {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private QuestModel $questModel;
    private MonsterModel $monsterModel;
    private ArmorModel $armorModel;
    private WeaponModel $weaponModel;
    private RaceModel $raceModel;
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

        return $quest->asLoadQuestResponse($this->monsterModel, $this->armorModel, $this->weaponModel, $this->raceModel);
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

        return $char->asExpSaveResponse();
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

        $questID = (int)$input->intQuestID;

        if($questID==54) {
            return \simplexml_load_string(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <questreward intExp="20" intSilver="0" intGold="1021" intGems="0" intCoins="3">
        <items ItemID="20387" strItemName="Forgotten Spear" strItemDescription="Your first loot! Lucky for you, unlucky for whoever lost it.&#10;(Scythe-type weapons can be used with any stat type, STR, DEX, or INT.)" bitVisible="1" bitDestroyable="1" bitSellable="1" bitDragonAmulet="0" intCurrency="2" intCost="50" intMaxStackSize="1" intBonus="0" intRarity="0" intLevel="3" strType="Melee" strElement="Metal" strCategory="Weapon" strEquipSpot="Weapon" strItemType="Scythe" strFileName="items/scythes/scythe-pointystick.swf" strIcon="scythe" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" intMin="10" intMax="12" intDefMelee="0" intDefPierce="0" intDefMagic="0" intCrit="0" intParry="0" intDodge="0" intBlock="0" strResists=""/>
    </questreward>
</questreward>
XML);
        }
        if($questID==103) {
            return \simplexml_load_string(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <questreward intExp="121" intSilver="0" intGold="1049" intGems="0" intCoins="3">
        <items ItemID="733" strItemName="Dusty Old Tome" strItemDescription="Return this book and other books to Loremaster Maya in Oaklore Keep. " bitVisible="1" bitDestroyable="1" bitSellable="1" bitDragonAmulet="0" intCurrency="2" intCost="100" intMaxStackSize="1" intBonus="0" intRarity="3" intLevel="0" strType="Melee" strElement="None" strCategory="Item" strEquipSpot="Not Equipable" strItemType="Quest Item" strFileName="" strIcon="note" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intMin="0" intMax="0" intDefMelee="0" intDefPierce="0" intDefMagic="0" intCrit="0" intParry="0" intDodge="0" intBlock="0" strResists=""/>
    </questreward>
</questreward>
XML);
        }

        return \simplexml_load_string(<<<XML
<error>
    <info code="538.07" reason="Invalid Input!" message="Message" action="None"/>
</error>
XML);
    }

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

        return $char->asSaveQuestStringResponse();
    }

}