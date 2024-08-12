<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class Character extends Controller {

    #[Request(
        method: '/cf-characterload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $token = (string)$input->strToken;
        $charID = (int)$input->intCharID;
        if($token=="LOGINTOKENSTRNG" && $charID==12345678) {
            return \simplexml_load_string(<<<XML
<character xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <character CharID="12345678" strCharacterName="hiperesp" dateCreated="2024-08-10T18:46:00" isBirthday="0" intLevel="1" intExp="0" intHP="100" intMP="100" intSilver="0" intGold="1000" intGems="0" intCoins="0" intMaxBagSlots="30" intMaxBankSlots="0" intMaxHouseSlots="5" intMaxHouseItemSlots="20" intDragonAmulet="0" intAccesslevel="1" strGender="M" strPronoun="M" intColorHair="7027237" intColorSkin="15388042" intColorBase="12766664" intColorTrim="7570056" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" intSkillPoints="0" intStatPoints="0" intCharStatus="0" intDaily="0" strArmor="0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" strSkills="000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" strQuests="000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" intExpToLevel="20" RaceID="1" strRaceName="Human" GuildID="1" strGuildName="None" QuestID="933" strQuestName="Prologue" strQuestFileName="town-prologuechoice-r7.swf" strXQuestFileName="none" strExtra="" BaseClassID="2" ClassID="2" strClassName="Warrior" strClassFileName="class-2016warrior-r3.swf" strArmorName="Plate Mail" strArmorDescription="The shiny armor of Warriors!" strArmorResists="Darkness,5,Light,5" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Longsword" strWeaponDescription="A two handed long sword... of justice!" strWeaponDesignInfo="none" strWeaponResists="" intWeaponLevel="1" strWeaponIcon="sword" strType="Melee     " strItemType="Sword" intCrit="0" intDmgMin="5" intDmgMax="10" intBonus="1" strEquippable="Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket,Artifact" intSavable="2" strHairFileName="head/M/hair-male-hero.swf" intHairFrame="1" strElement="Metal" gemReward="0" intDailyRoll="1"/>
</character>
XML);
        }
    }

    #[Request(
        method: '/cf-characterdelete.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function delete(): \SimpleXMLElement {
        // <flash><strToken>LOGINTOKENSTRING</strToken><strPassword>admin</strPassword><strUsername>admin</strUsername><intCharID>12345678</intCharID></flash>
        return \simplexml_load_string(<<<XML
<charDelete>
    <charDelete message='Character Deleteion Successful!!'/>
</charDelete>
XML);
    }

    #[Request(
        method: '/cf-characternew.asp',
        inputType: Input::FORM,
        outputType: Output::FORM
    )]
    public function new(array $input): array {
        $input['intUserID'];         // 12345678
        $input['strUsername'];       // admin
        $input['strPassword'];       // admin
        $input['strToken'];          // LOGINTOKENSTRNG
        $input['strCharacterName'];  // hiperesp
        $input['strGender'];         // M
        $input['strPronoun'];        // M
        $input['intHairID'];         // 3
        $input['intColorHair'];      // 7027237
        $input['intColorSkin'];      // 15388042
        $input['intColorBase'];      // 12766664
        $input['intColorTrim'];      // 7570056
        $input['intClassID'];        // 2
        $input['intRaceID'];         // 1
        $input['strClass'];          // Warrior

        // create user with this details
        return [
            "code" => 0,
            "reason" => "Character created Successfully!",
            "message" => "none",
            "action" => "none"
        ];
    }

    #[Request(
        method: '/cf-dacheck.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonAmuletCheck(\SimpleXMLElement $input): \SimpleXMLElement {
        $hasDragonAmulet = false;

        $token = (string)$input->strToken;
        $charID = (int)$input->intCharID;

        if($token=="LOGINTOKENSTRNG" && $charID==12345678) {
            $intDragonAmulet = (int)$hasDragonAmulet;
            return \simplexml_load_string(<<<XML
<character xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <character intDragonAmulet="{$intDragonAmulet}"/>
</character>
XML);
        } else {
            return \simplexml_load_string(<<<XML
<character xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <info code="500.71" reason="Character doesn't exist!" message="Character doesn't exist!" action="none"/>
</character>
XML);
        }
    }

    #[Request(
        method: '/cf-changehometown.asp',
        inputType: Input::RAW,
        outputType: Output::NINJA2STR
    )]
    public function changeHomeTown($input): string {
        // <flash><intTownID>51</intTownID><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        return <<<XML
<changeHomeTown xmlns:sql="urn:schemas-microsoft-com:xml-sql"><newTown strQuestFileName="towns/oaklore/town-oaklore-2019.swf" strQuestXFileName="none" strExtra="oakloretown=towns/oaklore/town-oaklore-2019.swf
oaklore=towns/Oaklore/zone-oaklore-forest.swf
map=maps/map-oaklore.swf
Sirvey=towns/Oaklore/town-sirvey.swf
Maya=towns/Oaklore/shop-maya-new.swf"/></changeHomeTown>
XML;
    }

    #[Request(
        method: '/cf-loadtowninfo.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function cf_loadTownInfo(\SimpleXMLElement $input): \SimpleXMLElement {
        $token = (string)$input->strToken;
        $charID = (int)$input->intCharID;
        $townID = (int)$input->intTownID;

        if($token=="LOGINTOKENSTRNG" && $charID==12345678 && $townID==3) {
            return \simplexml_load_string(<<<XML
<LoadTown xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <newTown strQuestFileName="towns/Oaklore/town-oaklore-loader-r1.swf" strQuestXFileName="none" strExtra=""/>
</LoadTown>
XML);
        }

        return null;
    }

}