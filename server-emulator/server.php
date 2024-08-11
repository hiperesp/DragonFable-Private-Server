<?php

$methodServerMap = [
    "/cf-userlogin.asp" => \hiperesp\server\controllers\Auth::class,
];

\spl_autoload_register(function ($className) {
    $classFile = __DIR__.DIRECTORY_SEPARATOR.\preg_replace('/\\\\/', DIRECTORY_SEPARATOR, $className).".php";
    if(!\file_exists($classFile)) {
        throw new \Exception("The file {$classFile} does not exists. See {$className}");
    }
    include $classFile;
});

$method = $_SERVER["PATH_INFO"];
if(!isset($methodServerMap[$method])) {
    throw new \Exception("Method {$method} is not mapped.");
}

$class = $methodServerMap[$method];

$classInstance = new $class;
if(!($classInstance instanceof \hiperesp\server\controllers\Controller)) {
    throw new \Exception("The class {$class} is not a instance of default controller");
}

$classInstance->entry($method);
die;

class DragonFable {
    /**
     * debug
     */
    public function dd(): DragonFableOutput {
        return DragonFableOutput::text((new DragonFableCrypto2)->decrypt($_GET["ninja2"]));
    }

    /**
     * pre-login
     */
    public function DFversion(): DragonFableOutput {
        return DragonFableOutput::form([
            "gamemovie" => "game15_8_05.swf",
            "gamefilesPath" => "http://127.0.0.1/gamefiles-custom/",
            "serverPath" => "http://127.0.0.1/server-emulator/server.php/",
            "end" => "here",
        ]);
    }

    /**
     * Dragon Amulet check
     * any time after logged
     * created without references
     * must return updated character based on strToken and characterId
     */
    public function cf_DACheck(): DragonFableOutput {
        $giveDragonAmulet = false;
        
        $input = DragonFableInput::ninja2xml(); // <flash><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        if($input->strToken=="LOGINTOKENSTRNG" && $input->intCharID=="12345678") {
            $intGiveDragonAmulet = (int)$giveDragonAmulet;
            return DragonFableOutput::xml(<<<XML
<character xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <character CharID="12345678" strCharacterName="hiperesp" dateCreated="2024-08-10T18:46:00" isBirthday="0" intLevel="1" intExp="0" intHP="100" intMP="100" intSilver="0" intGold="1000" intGems="0" intCoins="0" intMaxBagSlots="30" intMaxBankSlots="0" intMaxHouseSlots="5" intMaxHouseItemSlots="20" intDragonAmulet="{$intGiveDragonAmulet}" intAccesslevel="1" strGender="M" strPronoun="M" intColorHair="7027237" intColorSkin="15388042" intColorBase="12766664" intColorTrim="7570056" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" intSkillPoints="0" intStatPoints="0" intCharStatus="0" intDaily="0" strArmor="0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" strSkills="000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" strQuests="000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" intExpToLevel="20" RaceID="1" strRaceName="Human" GuildID="1" strGuildName="None" QuestID="933" strQuestName="Prologue" strQuestFileName="town-prologuechoice-r7.swf" strXQuestFileName="none" strExtra="" BaseClassID="2" ClassID="2" strClassName="Warrior" strClassFileName="class-2016warrior-r3.swf" strArmorName="Plate Mail" strArmorDescription="The shiny armor of Warriors!" strArmorResists="Darkness,5,Light,5" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Longsword" strWeaponDescription="A two handed long sword... of justice!" strWeaponDesignInfo="none" strWeaponResists="" intWeaponLevel="1" strWeaponIcon="sword" strType="Melee     " strItemType="Sword" intCrit="0" intDmgMin="5" intDmgMax="10" intBonus="1" strEquippable="Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket,Artifact" intSavable="2" strHairFileName="head/M/hair-male-hero.swf" intHairFrame="1" strElement="Metal" gemReward="0" intDailyRoll="1"/>
</character>
XML);
        } else {
            return DragonFableOutput::xml(<<<XML
<info>
    <info code="0.0" reason="Character Not Found or Session Expired" message="The session is expired or your character not found. Please login again." action="None"/>
</info>
XML);
        }
    }

    /**
     * login screen
     * must return plain xml string
     */
    public function cf_userLogin(): DragonFableOutput {
        $input = DragonFableInput::ninja2xml(); // <flash><strPassword>admin</strPassword><strUsername>admin</strUsername></flash>

        if($input->strUsername=="admin" && $input->strPassword=="admin") {
            // gerar um strToken aleatório para o charId no banco durante o login, mas vamos manter fixo como debug
            return DragonFableOutput::xml(<<<XML
<characters xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <user UserID="40346341" intCharsAllowed="3" intAccessLevel="0" intUpgrade="0" intActivationFlag="5" bitOptin="0" strToken="LOGINTOKENSTRNG" strNews="It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!\n\nIt's Togsday!\n\nCheck out the DNs for more info!" bitAdFlag="0" dateToday="2024-08-10T18:31:35.920">
        <characters CharID="12345678" strCharacterName="hiperesp" intLevel="1" intAccessLevel="1" intDragonAmulet="0" strClassName="Mage" strRaceName="Human" orgClassID="3"/>
    </user>
</characters>
XML);
        }
        return DragonFableOutput::xml(<<<XML
<error>
    <info code="526.14" reason="User Not Found or Wrong Password" message="The username or password you typed was not correct. Please check the exact spelling and try again." action="None"/>
</error>
XML);
    }

    /**
     * after login
     */
    public function cf_characterNew(): DragonFableOutput {
        $input = DragonFableInput::form();
        
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
        return DragonFableOutput::form([
            "code" => 0,
            "reason" => "Character created Successfully!",
            "message" => "none",
            "action" => "none"
        ]);
    }

    // public function cf_characterDelete(): DragonFableOutput {}

    /**
     * after character selection
     * must return plain xml string
     */
    public function cf_characterLoad(): DragonFableOutput {
        $input = DragonFableInput::ninja2xml(); // <flash><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        if($input->strToken=="LOGINTOKENSTRNG" && $input->intCharID=="12345678") {
            return DragonFableOutput::xml(<<<XML
<character xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <character CharID="12345678" strCharacterName="hiperesp" dateCreated="2024-08-10T18:46:00" isBirthday="0" intLevel="1" intExp="0" intHP="100" intMP="100" intSilver="0" intGold="1000" intGems="0" intCoins="0" intMaxBagSlots="30" intMaxBankSlots="0" intMaxHouseSlots="5" intMaxHouseItemSlots="20" intDragonAmulet="0" intAccesslevel="1" strGender="M" strPronoun="M" intColorHair="7027237" intColorSkin="15388042" intColorBase="12766664" intColorTrim="7570056" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" intSkillPoints="0" intStatPoints="0" intCharStatus="0" intDaily="0" strArmor="0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" strSkills="000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" strQuests="000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000" intExpToLevel="20" RaceID="1" strRaceName="Human" GuildID="1" strGuildName="None" QuestID="933" strQuestName="Prologue" strQuestFileName="town-prologuechoice-r7.swf" strXQuestFileName="none" strExtra="" BaseClassID="2" ClassID="2" strClassName="Warrior" strClassFileName="class-2016warrior-r3.swf" strArmorName="Plate Mail" strArmorDescription="The shiny armor of Warriors!" strArmorResists="Darkness,5,Light,5" intDefMelee="5" intDefPierce="5" intDefMagic="5" intParry="0" intDodge="0" intBlock="0" strWeaponName="Longsword" strWeaponDescription="A two handed long sword... of justice!" strWeaponDesignInfo="none" strWeaponResists="" intWeaponLevel="1" strWeaponIcon="sword" strType="Melee     " strItemType="Sword" intCrit="0" intDmgMin="5" intDmgMax="10" intBonus="1" strEquippable="Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket,Artifact" intSavable="2" strHairFileName="head/M/hair-male-hero.swf" intHairFrame="1" strElement="Metal" gemReward="0" intDailyRoll="1"/>
</character>
XML);
            
        }
        return null;
    }

    public function cf_changeHomeTown(): DragonFableOutput {
        $input = DragonFableInput::ninja2xml(); // <flash><intTownID>51</intTownID><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        return DragonFableOutput::ninja2(DragonFableOutput::xml(<<<XML
<changeHomeTown xmlns:sql="urn:schemas-microsoft-com:xml-sql"><newTown strQuestFileName="towns/oaklore/town-oaklore-2019.swf" strQuestXFileName="none" strExtra="oakloretown=towns/oaklore/town-oaklore-2019.swf\noaklore=towns/Oaklore/zone-oaklore-forest.swf\nmap=maps/map-oaklore.swf\nSirvey=towns/Oaklore/town-sirvey.swf\nMaya=towns/Oaklore/shop-maya-new.swf"/></changeHomeTown>
XML, true));
    }

    /**
     * after character load
     * must return plain xml string
     */
    public function cf_loadTownInfo(): DragonFableOutput {
        $input = DragonFableInput::ninja2xml();

        if($input->strToken=="LOGINTOKENSTRNG" && $input->intCharID=="12345678" && $input->intTownID==3) {
            return DragonFableOutput::xml(<<<XML
<LoadTown xmlns:sql="urn:schemas-microsoft-com:xml-sql"><newTown strQuestFileName="towns/Oaklore/town-oaklore-loader-r1.swf" strQuestXFileName="none" strExtra=""/></LoadTown>
XML);
        }

        return null;
    }

    /**
     * after loadtowninfo
     * must return plain xml string
     */
    public function cf_questLoad(): DragonFableOutput {
        $input = DragonFableInput::ninja2xml();
        if($input->strToken=="LOGINTOKENSTRNG" && $input->intCharID=="12345678" && $input->intQuestID==54) {
            return DragonFableOutput::xml(<<<XML
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

        return null;
    }

    public function cf_expSave(): DragonFableOutput {
        $input = DragonFableInput::ninja2xml(); // <flash><intExp>20</intExp><intGems>0</intGems><intGold>21</intGold><intSilver>0</intSilver><intQuestID>54</intQuestID><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        if($input->strToken=="LOGINTOKENSTRNG" && $input->intCharID=="12345678" && $input->intQuestID==54) {

            return DragonFableOutput::ninja2(DragonFableOutput::xml(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql"><questreward intLevel="2" intExp="0" intHP="120" intMP="105" intSilver="0" intGold="1021" intGems="0" intSkillPoints="0" intStatPoints="3" intExpToLevel="40"/></questreward>
XML, true));
        }

        return null;
    }

    public function cf_questComplete_mar2011(): DragonFableOutput {
        $input = DragonFableInput::ninja2xml(); // <flash><intWaveCount>1</intWaveCount><intRare>0</intRare><intWar>0</intWar><intLootID>-1</intLootID><intExp>undefined</intExp><intGold>undefined</intGold><intQuestID>54</intQuestID><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        if($input->strToken=="LOGINTOKENSTRNG" && $input->intCharID=="12345678" && $input->intQuestID==54) {
            return DragonFableOutput::xml(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <questreward intExp="20" intSilver="0" intGold="1021" intGems="0" intCoins="3">
        <items ItemID="20387" strItemName="Forgotten Spear" strItemDescription="Your first loot! Lucky for you, unlucky for whoever lost it. \n(Scythe-type weapons can be used with any stat type, STR, DEX, or INT.)" bitVisible="1" bitDestroyable="1" bitSellable="1" bitDragonAmulet="0" intCurrency="2" intCost="50" intMaxStackSize="1" intBonus="0" intRarity="0" intLevel="3" strType="Melee" strElement="Metal" strCategory="Weapon" strEquipSpot="Weapon" strItemType="Scythe" strFileName="items/scythes/scythe-pointystick.swf" strIcon="scythe" intStr="0" intDex="0" intInt="0" intLuk="0" intCha="0" intEnd="0" intWis="0" intMin="10" intMax="12" intDefMelee="0" intDefPierce="0" intDefMagic="0" intCrit="0" intParry="0" intDodge="0" intBlock="0" strResists=""/>
    </questreward>
</questreward>
XML);
        }

        return null;
    }

    public function cf_questReward(): DragonFableOutput {
        $input = DragonFableInput::ninja2xml(); // <flash><intNewItemID>20387</intNewItemID><strToken>DFMMoj18TjPEqzw</strToken><intCharID>12345678</intCharID></flash>

        if($input->strToken=="LOGINTOKENSTRNG" && $input->intCharID=="12345678" && $input->intNewItemID==20387) {
            return DragonFableOutput::xml(<<<XML
<questreward xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <CharItemID>783072142</CharItemID>
</questreward>
XML);
        }

        return null;
    }

    // public function cf_interfaceLoad(): DragonFableOutput {}

    public static function parseAction($action) {
        $action = \preg_replace('/-/', '_', $action);
        return $action;
    }
}

class DragonFableInput {
    public static function query(): array {
        return $_GET;
    }
    public static function form(): array {
        return $_POST;
    }
    public static function ninja2(): string {
        $input = \file_get_contents("php://input");
        if(!\preg_match('/^<ninja2>(.+)<\/ninja2>$/', $input, $matches)) {
            throw new \Exception("Bad input");
        }
        $dfc2 = new DragonFableCrypto2;
        return $dfc2->decrypt($matches[1]);
    }
    public static function ninja2xml(): SimpleXMLElement {
        return \simplexml_load_string(self::ninja2());
    }
}

class DragonFableOutput {
    private function __construct(
        public readonly string $contentType,
        public readonly string $body,
    ) {}

    public static function ninja2(DragonFableOutput $output): DragonFableOutput {
        $dfc2 = new DragonFableCrypto2;
        return DragonFableOutput::xml("<ninja2>{$dfc2->encrypt($output->body)}</ninja2>");
    }

    public static function form(array $output): DragonFableOutput {
        return new DragonFableOutput(
            contentType: "application/x-www-form-urlencoded",
            body: "&".\http_build_query($output)
        );
    }

    public static function text(string $output): DragonFableOutput {
        return new DragonFableOutput(
            contentType: "text/plain",
            body: $output
        );
    }

    public static function xml(string $xml, bool $isRawXml = false): DragonFableOutput {
        if(!$isRawXml) {
            $dom = new \DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = false;
            $dom->loadXml($xml);
            $xml = $dom->saveXml();
        }

        return new DragonFableOutput(
            contentType: "application/xml",
            body: $xml
        );
    }
}

class DragonFableCrypto2 {

    public function __construct(private string $key = "ZorbakOwnsYou") {}

    public function decrypt(string $theText): string {
        $decrypted = "";

        $textLength = \strlen($theText);
        $keyLength = \strlen($this->key);

        for($i=0; $i<$textLength; $i+=4) {
            $charP1 = \base_convert(\substr($theText, $i, 2), 30, 10);
            $charP2 = \base_convert(\substr($theText, $i + 2, 2), 30, 10);
            $charP3 = \ord($this->key[$i / 4 % $keyLength]);
            $decrypted .= \chr($charP1 - $charP2 - $charP3);
        }
        return $decrypted;
    }
    public function encrypt(string $theText): string {
        $encrypted = "";
        
        $textLength = \strlen($theText);
        $keyLength = \strlen($this->key);

        for($i=0; $i<$textLength; $i++) {
            $random = \floor(\mt_rand() / \mt_getrandmax() * 66) + 33;
            $char = \ord($this->key[$i % $keyLength]);
            $encrypted .= \base_convert(\ord($theText[$i]) + $random + $char, 10, 30).\base_convert($random, 10, 30);
        }
        return $encrypted;
    }

    public static function test(): bool {
        $tests = [
            [
                "key" => "ZorbakOwnsYou",
                "test" => function($obj) {
                    return $obj->decrypt("83338t1qa82q9k35a6349q2p7a2j7d1ea72m971g9128861p8h1d8t24aj339219a637a1309i2l7s379721812b6h15942a8s1bah2q882i9b2da12c9b2897219b23962n9p2g8i2q7116891fa62jao33862b9a1oaa358a188d1ga238871t951p751d8614691a6b13a1298l1pa42j9f2q8c198f1ra92s9h388p1j8h18921q6n1m8h2q8h337l19ac33a534a330881h7a1l");
                },
                "value" => '<flash><strPassword>a</strPassword><strUsername>a</strUsername></flash>',
            ],
            [
                "key" => "ZorbakOwnsYou",
                "test" => function($obj) {
                    return $obj->decrypt($obj->encrypt('<flash><strPassword>a</strPassword><strUsername>a</strUsername></flash>'));
                },
                "value" => '<flash><strPassword>a</strPassword><strUsername>a</strUsername></flash>',
            ],
        ];

        foreach($tests as $test) {
            $obj = new DragonFableCrypto2($test["key"]);
            if($test["test"]($obj)!==$test["value"]) {
                return false;
            }
        }
        return true;
    }
}

try {
    if(!isset($_GET['action'])) {
        throw new \Exception("Undefined action");
    }

    $action = DragonFable::parseAction($_GET['action']);
    $server = new DragonFable();
    if(!\method_exists($server, $action)) {
        throw new \Exception("Invalid action");
    }
    try {
        \ob_start();
        $output = $server->{$action}();
        $hasError = \ob_get_clean();
        if($hasError) {
            throw new \Exception("Dirty output");
        }
        \header("Content-Type: {$output->contentType}");
        echo $output->body;
        die;
    } catch(\Exception $e) {
        \http_response_code(500);
        echo "<h1>{$e->getMessage()}</h1><hr>{$action}";
        die;
    }
} catch(\Exception $e) {
    \http_response_code(400);
    echo "<h1>{$e->getMessage()}</h1><hr>{$action}";
    die;
}