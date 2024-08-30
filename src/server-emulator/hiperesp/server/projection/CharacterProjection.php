<?php
namespace hiperesp\server\projection;

use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\HairModel;
use hiperesp\server\models\QuestModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\SettingsModel;
use hiperesp\server\models\WeaponModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\QuestVO;
use hiperesp\server\vo\UserVO;

class CharacterProjection extends Projection {

    private SettingsModel $settingsModel;
    private RaceModel $raceModel;
    private QuestModel $questModel;
    private ClassModel $classModel;
    private ArmorModel $armorModel;
    private WeaponModel $weaponModel;
    private HairModel $hairModel;

    public function created(): array {
        return [
            "code" => 0,
            "reason" => "Character created Successfully!",
            "message" => "none",
            "action" => "none"
        ];
    }

    public function deleted(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<charDelete/>');
        $charDelete = $xml->addChild('charDelete');
        $charDelete->addAttribute('message', 'Character Deleteion Successful!!');

        return $xml;
    }

    public function dragonAmuletCheck(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<character/>');
        $charEl = $xml->addChild('character');
        $charEl->addAttribute('intDragonAmulet', $char->dragonAmulet ? 1 : 0);

        return $xml;
    }

    public function loaded(CharacterVO $char, UserVO $user): \SimpleXMLElement {
        if($user->id != $char->userId) {
            throw new \Exception('Character does not belong to the user');
        }

        $xml = new \SimpleXMLElement('<character/>');
        $charEl = $xml->addChild('character');
        $charEl->addAttribute('CharID', $char->id);
        $charEl->addAttribute('strCharacterName', $char->name);
        $charEl->addAttribute('dateCreated', \date('Y-m-d\TH:i:s', \strtotime($char->createdAt)));
        $charEl->addAttribute('isBirthday', $user->isBirthday(\date('c')) ? '1' : '0');
        $charEl->addAttribute('intLevel', $char->level);
        $charEl->addAttribute('intExp', $char->experience);
        $charEl->addAttribute('intHP', $char->hitPoints);
        $charEl->addAttribute('intMP', $char->manaPoints);
        $charEl->addAttribute('intSilver', $char->silver);
        $charEl->addAttribute('intGold', $char->gold);
        $charEl->addAttribute('intGems', $char->gems);
        $charEl->addAttribute('intCoins', $char->coins);
        $charEl->addAttribute('intMaxBagSlots', $char->maxBagSlots);
        $charEl->addAttribute('intMaxBankSlots', $char->maxBankSlots);
        $charEl->addAttribute('intMaxHouseSlots', $char->maxHouseSlots);
        $charEl->addAttribute('intMaxHouseItemSlots', $char->maxHouseItemSlots);
        $charEl->addAttribute('intDragonAmulet', $char->dragonAmulet ? 1 : 0);
        $charEl->addAttribute('intAccesslevel', $char->getAccessLevel());
        $charEl->addAttribute('strGender', $char->gender);
        $charEl->addAttribute('strPronoun', $char->pronoun);
        $charEl->addAttribute('intColorHair', $char->colorHair);
        $charEl->addAttribute('intColorSkin', $char->colorSkin);
        $charEl->addAttribute('intColorBase', $char->colorBase);
        $charEl->addAttribute('intColorTrim', $char->colorTrim);
        $charEl->addAttribute('intStr', $char->strength);
        $charEl->addAttribute('intDex', $char->dexterity);
        $charEl->addAttribute('intInt', $char->intelligence);
        $charEl->addAttribute('intLuk', $char->luck);
        $charEl->addAttribute('intCha', $char->charisma);
        $charEl->addAttribute('intEnd', $char->endurance);
        $charEl->addAttribute('intWis', $char->wisdom);
        $charEl->addAttribute('intSkillPoints', $char->skillPoints);
        $charEl->addAttribute('intStatPoints', $char->statPoints);
        $charEl->addAttribute('intCharStatus', 0);
        $charEl->addAttribute('strArmor', $char->armor);
        $charEl->addAttribute('strSkills', $char->skills);
        $charEl->addAttribute('strQuests', $char->quests);
        $charEl->addAttribute('intExpToLevel', $char->experienceToLevel);

        $charEl->addAttribute('GuildID', 1);
        $charEl->addAttribute('strGuildName', "None");

        $race = $this->raceModel->getByChar($char);
        $charEl->addAttribute('RaceID', $race->id);
        $charEl->addAttribute('strRaceName', $race->name);

        $quest = $this->questModel->getByChar($char);
        $charEl->addAttribute('QuestID', $quest->id);
        $charEl->addAttribute('strQuestName', $quest->name);
        $charEl->addAttribute('strQuestFileName', $quest->swf);
        $charEl->addAttribute('strXQuestFileName', $quest->swfX);
        $charEl->addAttribute('strExtra', $quest->extra);

        $charEl->addAttribute('BaseClassID', $char->baseClassId);

        $class = $this->classModel->getByChar($char);
        $charEl->addAttribute('ClassID', $class->id);
        $charEl->addAttribute('strClassName', $class->name);
        $charEl->addAttribute('strClassFileName', $class->swf);
        $charEl->addAttribute('strElement', $class->element);
        $charEl->addAttribute('intSavable', $class->savable);

        $armor = $this->armorModel->getByClass($class);
        $charEl->addAttribute('strArmorName', $armor->name);
        $charEl->addAttribute('strArmorDescription', $armor->description);
        $charEl->addAttribute('strArmorResists', $armor->resists);
        $charEl->addAttribute('intDefMelee', $armor->defenseMelee);
        $charEl->addAttribute('intDefPierce', $armor->defensePierce);
        $charEl->addAttribute('intDefMagic', $armor->defenseMagic);
        $charEl->addAttribute('intParry', $armor->parry);
        $charEl->addAttribute('intDodge', $armor->dodge);
        $charEl->addAttribute('intBlock', $armor->block);

        $weapon = $this->weaponModel->getByClass($class);
        $charEl->addAttribute('strWeaponName', $weapon->name);
        $charEl->addAttribute('strWeaponDescription', $weapon->description);
        $charEl->addAttribute('strWeaponDesignInfo', $weapon->designInfo);
        $charEl->addAttribute('strWeaponResists', $weapon->resists);
        $charEl->addAttribute('intWeaponLevel', $weapon->level);
        $charEl->addAttribute('strWeaponIcon', $weapon->icon);
        $charEl->addAttribute('strType', $weapon->type);
        $charEl->addAttribute('strItemType', $weapon->itemType);
        $charEl->addAttribute('intCrit', $weapon->critical);
        $charEl->addAttribute('intDmgMin', $weapon->damageMin);
        $charEl->addAttribute('intDmgMax', $weapon->damageMax);
        $charEl->addAttribute('intBonus', $weapon->bonus);

        $charEl->addAttribute('strEquippable', $char->getEquippable());

        $hair = $this->hairModel->getByChar($char);
        $charEl->addAttribute('strHairFileName', $hair->swf);
        $charEl->addAttribute('intHairFrame', 1);

        $charEl->addAttribute('gemReward', 0); // unknown meaning
        $charEl->addAttribute('intDaily', $char->getDailyQuestAvailable());
        $charEl->addAttribute('intDailyRoll', 1); // unknown meaning, always 1 with or without char with dragon amulet.

        return $xml;
    }

    public function questStringSaved(): \SimpleXMLElement {
        return new \SimpleXMLElement('<SaveQuestString/>');
    }

    public function expSaved(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<questreward/>');
        $questRewardEl = $xml->addChild('questreward');
        $questRewardEl->addAttribute('intLevel', $char->level);
        $questRewardEl->addAttribute('intExp', $char->experience);
        $questRewardEl->addAttribute('intHP', $char->hitPoints);
        $questRewardEl->addAttribute('intMP', $char->manaPoints);
        $questRewardEl->addAttribute('intSilver', $char->silver);
        $questRewardEl->addAttribute('intGold', $char->gold);
        $questRewardEl->addAttribute('intGems', $char->gems);
        $questRewardEl->addAttribute('intSkillPoints', $char->skillPoints);
        $questRewardEl->addAttribute('intStatPoints', $char->statPoints);
        $questRewardEl->addAttribute('intExpToLevel', $char->experienceToLevel);
        return $xml;
    }

    /** @param array<ItemVO> $rewards */
    public function questCompletedMar2011(QuestVO $quest, CharacterVO $char, array $rewards): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<questreward/>');
        $questRewardEl = $xml->addChild('questreward');
        $questRewardEl->addAttribute('intExp', $char->experience);
        $questRewardEl->addAttribute('intSilver', $char->silver);
        $questRewardEl->addAttribute('intGold', $char->gold);
        $questRewardEl->addAttribute('intGems', $char->gems);

        $coins = 0;
        if($quest->isDailyQuest()) {
            $settings = $this->settingsModel->getSettings();
            $coins = $settings->dailyQuestCoinsReward;
        }
        $questRewardEl->addAttribute('intCoins', $coins);

        return $xml;
    }

}