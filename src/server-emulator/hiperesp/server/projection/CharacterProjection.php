<?php
namespace hiperesp\server\projection;

use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\HairModel;
use hiperesp\server\models\ItemCategoryModel;
use hiperesp\server\models\ItemModel;
use hiperesp\server\models\QuestModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\WeaponModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\QuestVO;
use hiperesp\server\vo\SettingsVO;

class CharacterProjection extends Projection {

    private SettingsVO $settings;
    private RaceModel $raceModel;
    private QuestModel $questModel;
    private ClassModel $classModel;
    private ArmorModel $armorModel;
    private WeaponModel $weaponModel;
    private HairModel $hairModel;
    private ItemModel $itemModel;
    private ItemCategoryModel $itemCategoryModel;
    private CharacterItemModel $characterItemModel;

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

    public function loaded(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<character/>');
        $charEl = $xml->addChild('character');
        $charEl->addAttribute('CharID', $char->id);
        $charEl->addAttribute('strCharacterName', $char->name);
        $charEl->addAttribute('dateCreated', \date('Y-m-d\TH:i:s', \strtotime($char->createdAt)));
        $charEl->addAttribute('isBirthday', $char->isBirthday() ? '1' : '0');
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
        $charEl->addAttribute('intAccesslevel', $char->accessLevel);
        $charEl->addAttribute('strGender', $char->gender);
        $charEl->addAttribute('strPronoun', $char->pronoun);
        $charEl->addAttribute('intColorHair', \hexdec($char->colorHair));
        $charEl->addAttribute('intColorSkin', \hexdec($char->colorSkin));
        $charEl->addAttribute('intColorBase', \hexdec($char->colorBase));
        $charEl->addAttribute('intColorTrim', \hexdec($char->colorTrim));
        $charEl->addAttribute('intStr', $char->strength);
        $charEl->addAttribute('intDex', $char->dexterity);
        $charEl->addAttribute('intInt', $char->intelligence);
        $charEl->addAttribute('intLuk', $char->luck);
        $charEl->addAttribute('intCha', $char->charisma);
        $charEl->addAttribute('intEnd', $char->endurance);
        $charEl->addAttribute('intWis', $char->wisdom);
        $charEl->addAttribute('intSkillPoints', 0); // unused at game.swf
        $charEl->addAttribute('intStatPoints', 0);  // unused at game.swf
        $charEl->addAttribute('intCharStatus', $char->pvpStatus ? 1 : 0);
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
        $charEl->addAttribute('strEquippable', $class->equippable);

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

        $hair = $this->hairModel->getByChar($char);
        $charEl->addAttribute('strHairFileName', $hair->swf);
        $charEl->addAttribute('intHairFrame', 1);

        $charEl->addAttribute('gemReward', 0); // unknown meaning
        $charEl->addAttribute('intDaily', $char->isDailyQuestAvailable() ? 1 : 0);
        $charEl->addAttribute('intDailyRoll', 1); // not used at game.swf

        foreach($this->characterItemModel->getByChar($char) as $characterItem) {
            $itemEl = $charEl->addChild('items');

            $itemEl->addAttribute('CharItemID', $characterItem->id);
            $itemEl->addAttribute('bitEquipped', $characterItem->equipped ? 1 : 0);
            $itemEl->addAttribute('intCount', $characterItem->count);
            $itemEl->addAttribute('intHoursOwned', $characterItem->hoursOwned);

            $item = $this->itemModel->getByCharItem($characterItem);

            $itemEl->addAttribute('ItemID', $item->id);
            $itemEl->addAttribute('strItemName', $item->name);
            $itemEl->addAttribute('strItemDescription', $item->description);
            $itemEl->addAttribute('bitVisible', $item->visible);
            $itemEl->addAttribute('bitDestroyable', $item->destroyable);
            $itemEl->addAttribute('bitSellable', $item->sellable);
            $itemEl->addAttribute('bitDragonAmulet', $item->dragonAmulet);
            $itemEl->addAttribute('intCurrency', $item->currency);
            $itemEl->addAttribute('intCost', $item->cost);
            $itemEl->addAttribute('intMaxStackSize', $item->maxStackSize);
            $itemEl->addAttribute('intBonus', $item->bonus);
            $itemEl->addAttribute('intRarity', $item->rarity);
            $itemEl->addAttribute('intLevel', $item->level);
            $itemEl->addAttribute('strType', $item->type);
            $itemEl->addAttribute('strElement', $item->element);

            $category = $this->itemCategoryModel->getByItem($item);
            $itemEl->addAttribute('intCategory', $category->id);
            $itemEl->addAttribute('strCategory', $category->name);

            $itemEl->addAttribute('strEquipSpot', $item->equipSpot);
            $itemEl->addAttribute('strItemType', $item->itemType);
            $itemEl->addAttribute('strFileName', $item->swf);
            $itemEl->addAttribute('strIcon', $item->icon);
            $itemEl->addAttribute('intStr', $item->strength);
            $itemEl->addAttribute('intDex', $item->dexterity);
            $itemEl->addAttribute('intInt', $item->intelligence);
            $itemEl->addAttribute('intLuk', $item->luck);
            $itemEl->addAttribute('intCha', $item->charisma);
            $itemEl->addAttribute('intEnd', $item->endurance);
            $itemEl->addAttribute('intWis', $item->wisdom);
            $itemEl->addAttribute('intMin', $item->damageMin);
            $itemEl->addAttribute('intMax', $item->damageMax);
            $itemEl->addAttribute('intDefMelee', $item->defenseMelee);
            $itemEl->addAttribute('intDefPierce', $item->defensePierce);
            $itemEl->addAttribute('intDefMagic', $item->defenseMagic);
            $itemEl->addAttribute('intCrit', $item->critical);
            $itemEl->addAttribute('intParry', $item->parry);
            $itemEl->addAttribute('intDodge', $item->dodge);
            $itemEl->addAttribute('intBlock', $item->block);
            $itemEl->addAttribute('strResists', $item->resists);
        }

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
        $questRewardEl->addAttribute('intSkillPoints', 0); // unused at game.swf
        $questRewardEl->addAttribute('intStatPoints', 0);  // unused at game.swf
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
            $coins = $this->settings->dailyQuestCoinsReward;
        }
        $questRewardEl->addAttribute('intCoins', $coins);

        return $xml;
    }

    public function statsTrained(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<TrainStats/>');
        $xml->addChild('status', 'SUCCESS');

        return $xml;
    }

    public function statsUntrained(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<TrainStats/>');
        $xml->addChild('status', 'SUCCESS');

        return $xml;
    }

    public function bankLoaded(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<bank/>');
        $bankEl = $xml->addChild('bank');
        $bankEl->addAttribute('bankID', $char->id);
        $bankEl->addAttribute('strCharacterName', $char->name);

        return $xml;
    }

}