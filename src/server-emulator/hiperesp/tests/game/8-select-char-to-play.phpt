--TEST--
8 - Select char to play
--FILE--

$charController = new \hiperesp\server\controllers\CharacterController;

$response = $charController->load(new \SimpleXMLElement(<<<XML
<flash><strToken>{$context["userToken"]}</strToken><intCharID>{$context["charId"]}</intCharID></flash>
XML));

$response = \json_decode(\json_encode($response), true);

// patch dynamic data
$response["character"]["@attributes"]["CharID"] = -1;
$response["character"]["@attributes"]["dateCreated"] = "2024-10-10T00:00:00";
$response["character"]["@attributes"]["isBirthday"] = "0";

print_r($response);

--EXPECT--
Array
(
    [character] => Array
        (
            [@attributes] => Array
                (
                    [CharID] => -1
                    [strCharacterName] => testchar
                    [dateCreated] => 2024-10-10T00:00:00
                    [isBirthday] => 0
                    [intLevel] => 1
                    [intExp] => 0
                    [intHP] => 100
                    [intMP] => 100
                    [intSilver] => 0
                    [intGold] => 1000
                    [intGems] => 0
                    [intCoins] => 0
                    [intMaxBagSlots] => 30
                    [intMaxBankSlots] => 0
                    [intMaxHouseSlots] => 5
                    [intMaxHouseItemSlots] => 20
                    [intDragonAmulet] => 0
                    [intAccesslevel] => 0
                    [strGender] => M
                    [strPronoun] => M
                    [intColorHair] => 7027237
                    [intColorSkin] => 15388042
                    [intColorBase] => 12766664
                    [intColorTrim] => 7570056
                    [intStr] => 0
                    [intDex] => 0
                    [intInt] => 0
                    [intLuk] => 0
                    [intCha] => 0
                    [intEnd] => 0
                    [intWis] => 0
                    [intSkillPoints] => 0
                    [intStatPoints] => 0
                    [intCharStatus] => 0
                    [strArmor] => 0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
                    [strSkills] => 000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
                    [strQuests] => 000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
                    [intExpToLevel] => 20
                    [GuildID] => 1
                    [strGuildName] => None
                    [RaceID] => 1
                    [strRaceName] => Human
                    [QuestID] => 933
                    [strQuestName] => Prologue
                    [strQuestFileName] => town-prologuechoice-r7.swf
                    [strXQuestFileName] => none
                    [strExtra] => 
                    [BaseClassID] => 2
                    [ClassID] => 2
                    [strClassName] => Warrior
                    [strClassFileName] => class-2016warrior-r3.swf
                    [strElement] => Metal
                    [intSavable] => 2
                    [strArmorName] => Plate Mail
                    [strArmorDescription] => The shiny armor of Warriors!
                    [strArmorResists] => Darkness,5,Light,5
                    [intDefMelee] => 5
                    [intDefPierce] => 5
                    [intDefMagic] => 5
                    [intParry] => 0
                    [intDodge] => 0
                    [intBlock] => 0
                    [strWeaponName] => Longsword
                    [strWeaponDescription] => A two handed long sword... of justice!
                    [strWeaponDesignInfo] => none
                    [strWeaponResists] => 
                    [intWeaponLevel] => 1
                    [strWeaponIcon] => sword
                    [strType] => Melee
                    [strItemType] => Sword
                    [intCrit] => 0
                    [intDmgMin] => 5
                    [intDmgMax] => 10
                    [intBonus] => 1
                    [strEquippable] => Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket,Artifact
                    [strHairFileName] => head/M/hair-male-hero.swf
                    [intHairFrame] => 1
                    [gemReward] => 0
                    [intDaily] => 1
                    [intDailyRoll] => 1
                )

        )

)
