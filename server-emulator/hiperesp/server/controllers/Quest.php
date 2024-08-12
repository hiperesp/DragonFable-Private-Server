<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class Quest extends Controller {

    #[Request(
        method: '/cf-questload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $token = (string)$input->strToken;
        $charID = (int)$input->intCharID;
        $questID = (int)$input->intQuestID;

        return \simplexml_load_string(<<<XML
<quest xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <quest QuestID="54" strName="A Hero is Bored" strDescription="Your origins are a mystery, but your legend begins here in the forest of Oaklore!" strComplete="Your origins are a mystery, but your legend begins here in the forest of Oaklore!" strFileName="towns/Oaklore/quest-oaklore-intro2020-r1.swf" strXFileName="none" intMaxSilver="0" intMaxGold="100" intMaxGems="0" intMaxExp="50000" intMinTime="0" intCounter="500000" strExtra="" intDailyIndex="0" intDailyReward="1" intMonsterMinLevel="0" intMonsterMaxLevel="5" strMonsterType="Forest" strMonsterGroupFileName="mset-forest-r1.swf">
        <monsters MonsterID="5" intMonsterRef="0" strCharacterName="Sneevil" intLevel="1" intExp="5" intHP="5" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Rags" strArmorDescription="Stinky Sneevil Garb" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Blades" strWeaponDescription="Stabbies!" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="5" intDmgMin="2" intDmgMax="10" intBonus="0" strElement="Metal" strWeaponFile="" strMovName="sneevil" strMonsterFileName="none" RaceID="10" strRaceName="Goblinkind"/>
        <monsters MonsterID="6" intMonsterRef="1" strCharacterName="Gorillaphant" intLevel="5" intExp="38" intHP="104" intMP="0" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Think Skin" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="0" intDmgMin="3" intDmgMax="8" intBonus="0" strElement="Nature" strWeaponFile="" strMovName="gorillaphant" strMonsterFileName="none" RaceID="6" strRaceName="Beast"/>
        <monsters MonsterID="7" intMonsterRef="2" strCharacterName="Seed Spitter" intLevel="3" intExp="15" intHP="37" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Leaves" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="0" intDmgMin="5" intDmgMax="11" intBonus="0" strElement="Nature" strWeaponFile="" strMovName="seedspitter" strMonsterFileName="none" RaceID="9" strRaceName="Plant"/>
        <monsters MonsterID="687" intMonsterRef="3" strCharacterName="Pip" intLevel="7" intExp="35" intHP="43" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="F" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Thick Scales" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="1" intBlock="0" strWeaponName="Claws and Teeth" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Magic" intCrit="2" intDmgMin="8" intDmgMax="16" intBonus="0" strElement="Light" strWeaponFile="" strMovName="pip" strMonsterFileName="none" RaceID="18" strRaceName="Golem" strRaceResists=""/>
    </quest>
</quest>
XML);
    }

    #[Request(
        method: '/cf-expsave.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function expSave(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intExp>20</intExp><intGems>0</intGems><intGold>21</intGold><intSilver>0</intSilver><intQuestID>54</intQuestID><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        $xml = \simplexml_load_string(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql"><questreward intLevel="2" intExp="0" intHP="120" intMP="105" intSilver="0" intGold="1021" intGems="0" intSkillPoints="0" intStatPoints="3" intExpToLevel="40"/></questreward>
XML);
        return $xml;
    }

    #[Request(
        method: '/cf-questcomplete-Mar2011.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function cf_questComplete_mar2011(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intWaveCount>1</intWaveCount><intRare>0</intRare><intWar>0</intWar><intLootID>-1</intLootID><intExp>undefined</intExp><intGold>undefined</intGold><intQuestID>54</intQuestID><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        return \simplexml_load_string(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <questreward intExp="20" intSilver="0" intGold="1021" intGems="0" intCoins="3">
        <items ItemID="20387" strItemName="Forgotten Spear" strItemDescription="Your first loot! Lucky for you, unlucky for whoever lost it.&#10;(Scythe-type weapons can be used with any stat type, STR, DEX, or INT.)" bitVisible="1" bitDestroyable="1" bitSellable="1" bitDragonAmulet="0" intCurrency="2" intCost="50" intMaxStackSize="1" intBonus="0" intRarity="0" intLevel="3" strType="Melee" strElement="Metal" strCategory="Weapon" strEquipSpot="Weapon" strItemType="Scythe" strFileName="items/scythes/scythe-pointystick.swf" strIcon="scythe" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" intMin="10" intMax="12" intDefMelee="0" intDefPierce="0" intDefMagic="0" intCrit="0" intParry="0" intDodge="0" intBlock="0" strResists=""/>
    </questreward>
</questreward>
XML);
    }

    #[Request(
        method: '/cf-questreward.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function cf_questReward(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intNewItemID>20387</intNewItemID><strToken>DFMMoj18TjPEqzw</strToken><intCharID>12345678</intCharID></flash>
        return \simplexml_load_string(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <CharItemID>783072142</CharItemID>
</questreward>
XML);
    }

}