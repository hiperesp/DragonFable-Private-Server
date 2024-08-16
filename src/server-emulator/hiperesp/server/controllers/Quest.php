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
        // <flash><strToken>STR TOKEN HERE</strToken><intCharID>12345678</intCharID><intQuestID>64</intQuestID></flash>

        $token = (string)$input->strToken;
        $charID = (int)$input->intCharID;
        $questID = (int)$input->intQuestID;

        if($questID==54) {
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
        if($questID==59) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<quest xmlns:sql="urn:schemas-microsoft-com:xml-sql"><quest QuestID="59" strName="The VurrMen Ruins" strDescription="These ruins once stood tall and proud, but all things change. Hundreds of years have passed and the ruins of the great city are now infested with VurrMen and Tuskmongers, drawn by the many mysterious objects that can still be found here." strComplete="These ruins once stood tall and proud, but all things change. Hundreds of years have passed and the ruins of the great city are now infested with VurrMen and Tuskmongers, drawn by the many mysterious objects that can still be found here." strFileName="random/random-ruins-r1.swf" strXFileName="none" intMaxSilver="0" intMaxGold="2000" intMaxGems="0" intMaxExp="50000" intMinTime="0" intCounter="500000" strExtra="0" intDailyIndex="0" intDailyReward="1" intMonsterMinLevel="1" intMonsterMaxLevel="99" strMonsterType="Rats" strMonsterGroupFileName="mset-oaklore-r1.swf"><monsters MonsterID="69" intMonsterRef="0" strCharacterName="VurrMan" intLevel="4" intExp="21" intHP="26" intMP="0" intSilver="0" intGold="5" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="2" intDmgMin="10" intDmgMax="28" intBonus="0" strElement="Metal" strWeaponFile="" strMovName="verman" strMonsterFileName="none" RaceID="6" strRaceName="Beast"/><monsters MonsterID="73" intMonsterRef="1" strCharacterName="Tuskmonger" intLevel="7" intExp="35" intHP="43" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="F" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Thick Hide" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="1" intBlock="0" strWeaponName="Claws and Teeth" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="2" intDmgMin="1" intDmgMax="4" intBonus="0" strElement="Nature" strWeaponFile="" strMovName="boar" strMonsterFileName="none" RaceID="6" strRaceName="Beast"/><monsters MonsterID="76" intMonsterRef="2" strCharacterName="Vurrman Hoarder" intLevel="5" intExp="32" intHP="32" intMP="0" intSilver="0" intGold="5" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="2" intDmgMin="10" intDmgMax="28" intBonus="0" strElement="Metal" strWeaponFile="" strMovName="vurrman2" strMonsterFileName="none" RaceID="6" strRaceName="Beast"/><monsters MonsterID="77" intMonsterRef="3" strCharacterName="Vurrman Plaguebringer" intLevel="6" intExp="45" intHP="40" intMP="0" intSilver="0" intGold="5" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="2" intDmgMin="15" intDmgMax="32" intBonus="0" strElement="Metal" strWeaponFile="" strMovName="vurrman3" strMonsterFileName="none" RaceID="6" strRaceName="Beast"/><monsters MonsterID="687" intMonsterRef="4" strCharacterName="Pip" intLevel="7" intExp="35" intHP="43" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="F" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Thick Scales" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="1" intBlock="0" strWeaponName="Claws and Teeth" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Magic" intCrit="2" intDmgMin="8" intDmgMax="16" intBonus="0" strElement="Light" strWeaponFile="" strMovName="pip" strMonsterFileName="none" RaceID="18" strRaceName="Golem" strRaceResists=""/></quest></quest>
XML);
        }
        if($questID==64) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<quest xmlns:sql="urn:schemas-microsoft-com:xml-sql"><quest QuestID="64" strName="Sir Jing's Weapons" strDescription="You find yourself at the base of a mountain surrounded by storm elementals. They have gathered to use the power of Sir Jing's weapons to create a never-ending storm!" strComplete="You have fought your way up the mountain and stopped the storm elementals from summoning a never-ending storm! For the sake of the world, you must take Sir Jing's weapon for safe keeping." strFileName="quests/quest-sirjing-new-r2.swf?ver=1" strXFileName="quests/quest-sirjing-new-x.swf?ver=1" intMaxSilver="4" intMaxGold="2000" intMaxGems="0" intMaxExp="50000" intMinTime="0" intCounter="500000" strExtra="" intDailyIndex="0" intDailyReward="1" intMonsterMinLevel="2" intMonsterMaxLevel="5" strMonsterType="Wind, Water, Energy" strMonsterGroupFileName="mset-storm-r2.swf"><monsters MonsterID="78" intMonsterRef="0" strCharacterName="Small Puddle" intLevel="3" intExp="20" intHP="25" intMP="0" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="O" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="Water,200,Ice,25,Fire,-50,Energy,-50" intDefMelee="5" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="1" intDmgMin="6" intDmgMax="9" intBonus="0" strElement="Water" strWeaponFile="" strMovName="puddle" strMonsterFileName="none" RaceID="5" strRaceName="Elemental"/><monsters MonsterID="79" intMonsterRef="1" strCharacterName="Shockwisp" intLevel="3" intExp="20" intHP="25" intMP="0" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="O" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="Energy,200,Ice,-50,Water,-50" intDefMelee="5" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="1" intDmgMin="6" intDmgMax="9" intBonus="0" strElement="Energy" strWeaponFile="" strMovName="energywisp" strMonsterFileName="none" RaceID="5" strRaceName="Elemental"/><monsters MonsterID="80" intMonsterRef="2" strCharacterName="Thunderhead" intLevel="3" intExp="20" intHP="25" intMP="0" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="O" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="Wind,200,Stone,-50" intDefMelee="5" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Magic" intCrit="1" intDmgMin="6" intDmgMax="9" intBonus="0" strElement="Wind" strWeaponFile="" strMovName="thunderhead" strMonsterFileName="none" RaceID="5" strRaceName="Elemental"/><monsters MonsterID="81" intMonsterRef="3" strCharacterName="Flood" intLevel="4" intExp="40" intHP="40" intMP="50" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="O" intHairStyle="-1" intColorHair="-1" intColorSkin="-1" intColorBase="-1" intColorTrim="-1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Water Skin" strArmorDescription="" strArmorDesignInfo="" strArmorResists="Water,200,Ice,25,Fire,-50,Energy,-50" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Water Fists" strWeaponDescription="wet" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="2" intDmgMin="7" intDmgMax="12" intBonus="0" strElement="Water" strWeaponFile="" strMovName="waterelemental" strMonsterFileName="none" RaceID="5" strRaceName="Elemental"/><monsters MonsterID="82" intMonsterRef="4" strCharacterName="Tempest" intLevel="4" intExp="40" intHP="40" intMP="50" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="-1" intColorHair="-1" intColorSkin="-1" intColorBase="-1" intColorTrim="-1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Wind Skin" strArmorDescription="" strArmorDesignInfo="" strArmorResists="Wind,200,Stone,-50" intDefMelee="2" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="5" intBlock="0" strWeaponName="Wind Fists" strWeaponDescription="windy" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="2" intDmgMin="7" intDmgMax="12" intBonus="0" strElement="Wind" strWeaponFile="" strMovName="windelemental" strMonsterFileName="none" RaceID="5" strRaceName="Elemental"/><monsters MonsterID="83" intMonsterRef="5" strCharacterName="Positros" intLevel="8" intExp="40" intHP="40" intMP="50" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="O" intHairStyle="-1" intColorHair="-1" intColorSkin="-1" intColorBase="-1" intColorTrim="-1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Electric Skin" strArmorDescription="" strArmorDesignInfo="" strArmorResists="Energy,200,Ice,-50,Water,-50" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Water Fists" strWeaponDescription="wet" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="2" intDmgMin="7" intDmgMax="12" intBonus="0" strElement="Energy" strWeaponFile="" strMovName="energyelemental" strMonsterFileName="none" RaceID="5" strRaceName="Elemental"/><monsters MonsterID="676" intMonsterRef="6" strCharacterName="Lovey Bear" intLevel="5" intExp="25" intHP="104" intMP="0" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Thick Skin" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="0" intDmgMin="3" intDmgMax="8" intBonus="0" strElement="None" strWeaponFile="" strMovName="hhdbear2" strMonsterFileName="none" RaceID="6" strRaceName="Beast"/><monsters MonsterID="336" intMonsterRef="7" strCharacterName="Energy Elemental" intLevel="8" intExp="40" intHP="40" intMP="50" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="O" intHairStyle="-1" intColorHair="-1" intColorSkin="-1" intColorBase="-1" intColorTrim="-1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Electric Skin" strArmorDescription="" strArmorDesignInfo="" strArmorResists="Energy,200,Ice,-50,Water,-50" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Water Fists" strWeaponDescription="wet" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="2" intDmgMin="7" intDmgMax="12" intBonus="0" strElement="Energy" strWeaponFile="" strMovName="energyelemental" strMonsterFileName="none" RaceID="5" strRaceName="Elemental"/></quest></quest>
XML);
        }
        if($questID==101) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<quest xmlns:sql="urn:schemas-microsoft-com:xml-sql"><quest QuestID="101" strName="Return To The Intro" strDescription="We are not sure why you want to go back to that cliff. I guess the view is pretty nice." strComplete="You have done pretty much everything that you can do here." strFileName="towns/Oaklore/quest-oaklore-return.swf" strXFileName="none" intMaxSilver="1" intMaxGold="500" intMaxGems="0" intMaxExp="50000" intMinTime="0" intCounter="500000" strExtra="" intDailyIndex="0" intDailyReward="1" intMonsterMinLevel="0" intMonsterMaxLevel="5" strMonsterType="Forest" strMonsterGroupFileName="mset-forest-r1.swf"><monsters MonsterID="5" intMonsterRef="0" strCharacterName="Sneevil" intLevel="1" intExp="5" intHP="5" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Rags" strArmorDescription="Stinky Sneevil Garb" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Blades" strWeaponDescription="Stabbies!" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="5" intDmgMin="2" intDmgMax="10" intBonus="0" strElement="Metal" strWeaponFile="" strMovName="sneevil" strMonsterFileName="none" RaceID="10" strRaceName="Goblinkind"/><monsters MonsterID="6" intMonsterRef="1" strCharacterName="Gorillaphant" intLevel="5" intExp="38" intHP="104" intMP="0" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Think Skin" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="0" intDmgMin="3" intDmgMax="8" intBonus="0" strElement="Nature" strWeaponFile="" strMovName="gorillaphant" strMonsterFileName="none" RaceID="6" strRaceName="Beast"/><monsters MonsterID="7" intMonsterRef="2" strCharacterName="Seed Spitter" intLevel="3" intExp="15" intHP="37" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Leaves" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="0" intDmgMin="5" intDmgMax="11" intBonus="0" strElement="Nature" strWeaponFile="" strMovName="seedspitter" strMonsterFileName="none" RaceID="9" strRaceName="Plant"/><monsters MonsterID="687" intMonsterRef="3" strCharacterName="Pip" intLevel="7" intExp="35" intHP="43" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="F" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Thick Scales" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="1" intBlock="0" strWeaponName="Claws and Teeth" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Magic" intCrit="2" intDmgMin="8" intDmgMax="16" intBonus="0" strElement="Light" strWeaponFile="" strMovName="pip" strMonsterFileName="none" RaceID="18" strRaceName="Golem" strRaceResists=""/></quest></quest>
XML);
        }
        if($questID==103) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<quest xmlns:sql="urn:schemas-microsoft-com:xml-sql"><quest QuestID="103" strName="The Sweetest Thing" strDescription="Sir Junn in Oaklore Keep has asked you to head to the hive of the Oaklore Buzzers and recover a small jar of Royal Honey, the sweetest substance in the realm." strComplete="You defeated the Royal Buzzer and with the honey sample in hand you head back to Oaklore for a well deserved bath. You're very very very sticky." strFileName="quests/quest-beehive-r3.swf" strXFileName="none" intMaxSilver="0" intMaxGold="2000" intMaxGems="0" intMaxExp="50000" intMinTime="0" intCounter="500000" strExtra="" intDailyIndex="0" intDailyReward="1" intMonsterMinLevel="1" intMonsterMaxLevel="5" strMonsterType="Bugs" strMonsterGroupFileName="mset-beehive-r1.swf"><monsters MonsterID="151" intMonsterRef="0" strCharacterName="Oaklore Buzzer" intLevel="2" intExp="9" intHP="15" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Chiten" strArmorDescription="hard and shiney" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Stinger" strWeaponDescription="Allergic!" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="5" intDmgMin="2" intDmgMax="6" intBonus="0" strElement="None" strWeaponFile="" strMovName="bee2" strMonsterFileName="none" RaceID="14" strRaceName="Bug" strRaceResists=""/><monsters MonsterID="152" intMonsterRef="1" strCharacterName="Honey GuardiAnt" intLevel="3" intExp="11" intHP="20" intMP="0" intSilver="0" intGold="2" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Chiten" strArmorDescription="hard and shiney" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Stinger" strWeaponDescription="Allergic!" strWeaponDesignInfo="" strWeaponResists="" strType="Melee" intCrit="1" intDmgMin="2" intDmgMax="8" intBonus="0" strElement="None" strWeaponFile="" strMovName="metalant" strMonsterFileName="none" RaceID="14" strRaceName="Bug" strRaceResists=""/><monsters MonsterID="687" intMonsterRef="2" strCharacterName="Pip" intLevel="7" intExp="35" intHP="43" intMP="0" intSilver="0" intGold="1" intGems="0" intDragonCoins="0" strGender="F" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="Thick Scales" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="1" intBlock="0" strWeaponName="Claws and Teeth" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="" strType="Magic" intCrit="2" intDmgMin="8" intDmgMax="16" intBonus="0" strElement="Light" strWeaponFile="" strMovName="pip" strMonsterFileName="none" RaceID="18" strRaceName="Golem" strRaceResists=""/></quest></quest>
XML);
        }
        if($questID==938) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<quest xmlns:sql="urn:schemas-microsoft-com:xml-sql"><quest QuestID="938" strName="A Hero Is Thawed" strDescription="Your origins are frosty, but your legend begins here in the forest of Oaklore!" strComplete="Your origins are frosty, but your legend begins here in the forest of Oaklore!" strFileName="towns/3Oaklore/quest-3oaklore-intro-r5.swf?ver=1" strXFileName="none" intMaxSilver="0" intMaxGold="100" intMaxGems="0" intMaxExp="50000" intMinTime="0" intCounter="500000" strExtra="" intDailyIndex="0" intDailyReward="1" intMonsterMinLevel="0" intMonsterMaxLevel="100" strMonsterType="manahunter" strMonsterGroupFileName="mset-3Oak-manahunter-r3.swf"><monsters MonsterID="714" intMonsterRef="0" strCharacterName="ManaHunter" intLevel="15" intExp="100" intHP="58" intMP="0" intSilver="0" intGold="10" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="0" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="Bacon,50" strType="Melee" intCrit="3" intDmgMin="15" intDmgMax="23" intBonus="5" strElement="Metal" strWeaponFile="" strMovName="manahunter" strMonsterFileName="none" RaceID="1" strRaceName="Human"/><monsters MonsterID="714" intMonsterRef="1" strCharacterName="ManaHunter" intLevel="15" intExp="100" intHP="58" intMP="0" intSilver="0" intGold="10" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="0" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="Bacon,50" strType="Melee" intCrit="3" intDmgMin="15" intDmgMax="23" intBonus="5" strElement="Metal" strWeaponFile="" strMovName="manahunter" strMonsterFileName="none" RaceID="1" strRaceName="Human"/><monsters MonsterID="714" intMonsterRef="2" strCharacterName="ManaHunter" intLevel="15" intExp="100" intHP="58" intMP="0" intSilver="0" intGold="10" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="0" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="Bacon,50" strType="Melee" intCrit="3" intDmgMin="15" intDmgMax="23" intBonus="5" strElement="Metal" strWeaponFile="" strMovName="manahunter" strMonsterFileName="none" RaceID="1" strRaceName="Human"/><monsters MonsterID="714" intMonsterRef="3" strCharacterName="ManaHunter" intLevel="15" intExp="100" intHP="58" intMP="0" intSilver="0" intGold="10" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="0" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="Bacon,50" strType="Melee" intCrit="3" intDmgMin="15" intDmgMax="23" intBonus="5" strElement="Metal" strWeaponFile="" strMovName="manahunter" strMonsterFileName="none" RaceID="1" strRaceName="Human"/><monsters MonsterID="824" intMonsterRef="4" strCharacterName="ManaHunter" intLevel="15" intExp="100" intHP="58" intMP="0" intSilver="0" intGold="10" intGems="0" intDragonCoins="0" strGender="M" intHairStyle="1" intColorHair="1" intColorSkin="1" intColorBase="1" intColorTrim="1" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" strArmorName="None" strArmorDescription="" strArmorDesignInfo="" strArmorResists="" intDefMelee="0" intDefPierce="0" intDefMagic="0" intParry="0" intDodge="0" intBlock="0" strWeaponName="None" strWeaponDescription="" strWeaponDesignInfo="" strWeaponResists="Bacon,50" strType="Melee" intCrit="3" intDmgMin="15" intDmgMax="23" intBonus="0" strElement="Metal" strWeaponFile="" strMovName="manahunter2" strMonsterFileName="none" RaceID="1" strRaceName="Human"/></quest></quest>
XML);
        }

        return \simplexml_load_string(<<<XML
<error>
    <info code="538.07" reason="Invalid Input!" message="Message" action="None"/>
</error>
XML);
    }

    // NEED ATTENTION
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

// in case of error, return <error><info code="538.07" reason="Invalid Input!" message="Message" action="None"/></error> anytime
        return $xml;
    }

    #[Request(
        method: '/cf-questcomplete-Mar2011.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function complete_mar2011(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intWaveCount>1</intWaveCount><intRare>0</intRare><intWar>0</intWar><intLootID>-1</intLootID><intExp>undefined</intExp><intGold>undefined</intGold><intQuestID>54</intQuestID><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

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
        method: '/cf-questreward.asp',
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
        method: '/cf-savequeststring.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function saveQuestString(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intValue>1</intValue><intIndex>55</intIndex><strToken>TOKEN HERE</strToken><intCharID>12345678</intCharID></flash>
        return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SaveQuestString xmlns:sql="urn:schemas-microsoft-com:xml-sql"></SaveQuestString>
XML);
    }

}