<?php
namespace hiperesp\server\projection;

use hiperesp\server\attributes\Inject;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\QuestVO;
use hiperesp\server\vo\SettingsVO;

class CharacterProjection extends Projection {

    #[Inject] private SettingsVO $settings;

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
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><character xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
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
        $charEl->addAttribute('intMaxBagSlots', $char->bagSlots);
        $charEl->addAttribute('intMaxBankSlots', $char->bankSlots);
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

        $race = $char->getRace();
        $charEl->addAttribute('RaceID', $race->id);
        $charEl->addAttribute('strRaceName', $race->name);

        $town = $char->getTown();
        $charEl->addAttribute('QuestID', $town->id);
        $charEl->addAttribute('strQuestName', $town->name);
        $charEl->addAttribute('strQuestFileName', $town->swf);
        $charEl->addAttribute('strXQuestFileName', $town->swfX);
        $charEl->addAttribute('strExtra', $town->extra);

        $charEl->addAttribute('BaseClassID', $char->baseClassId);

        $class = $char->getClass();
        $charEl->addAttribute('ClassID', $class->id);
        $charEl->addAttribute('strClassName', $class->name);
        $charEl->addAttribute('strClassFileName', $class->swf);
        $charEl->addAttribute('strElement', $class->element);
        $charEl->addAttribute('intSavable', $class->savable);
        $charEl->addAttribute('strEquippable', $class->equippable);

        $armor = $class->getArmor();
        $charEl->addAttribute('strArmorName', $armor->name);
        $charEl->addAttribute('strArmorDescription', $armor->description);
        $charEl->addAttribute('strArmorResists', $armor->resists);
        $charEl->addAttribute('intDefMelee', $armor->defenseMelee);
        $charEl->addAttribute('intDefPierce', $armor->defensePierce);
        $charEl->addAttribute('intDefMagic', $armor->defenseMagic);
        $charEl->addAttribute('intParry', $armor->parry);
        $charEl->addAttribute('intDodge', $armor->dodge);
        $charEl->addAttribute('intBlock', $armor->block);

        $weapon = $class->getWeapon();
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

        $hair = $char->getHair();
        $charEl->addAttribute('strHairFileName', $hair->swf);
        $charEl->addAttribute('intHairFrame', 1);

        $charEl->addAttribute('gemReward', 0); // unknown meaning
        $charEl->addAttribute('intDaily', $char->isDailyQuestAvailable() ? 1 : 0);
        $charEl->addAttribute('intDailyRoll', 1); // not used at game.swf

        foreach($char->getBag() as $characterItem) {
			if ($characterItem->banked) {
				continue; // Skip items that are banked
			}
            $itemEl = $charEl->addChild('items');

            $itemEl->addAttribute('CharItemID', $characterItem->id);
            $itemEl->addAttribute('bitEquipped', $characterItem->equipped ? 1 : 0);
            $itemEl->addAttribute('intCount', $characterItem->count);
            $itemEl->addAttribute('intHoursOwned', $characterItem->hoursOwned);

            $item = $characterItem->getItem();
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

            $category = $item->getCategory();
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
		$dragon = $char->getDragon();
		if(isset($dragon['id'])) {
            $dragonEl = $charEl->addChild('dragon');
			$dragonEl->addAttribute('idCore_CharDragons', $dragon['id']);
			$dragonEl->addAttribute('strName', $dragon['name']);
			$dragonEl->addAttribute('dateLastFed', \date('Y-m-d\TH:i:s', \strtotime($dragon['lastFed'])));
			$dragonEl->addAttribute('intGrowthLevel', $dragon['growthLevel']);
			$dragonEl->addAttribute('intTotalStats', $dragon['totalStats']);
			$dragonEl->addAttribute('intHeal', $dragon['heal']);
			$dragonEl->addAttribute('intMagic', $dragon['magic']);
			$dragonEl->addAttribute('intMelee', $dragon['melee']);
			$dragonEl->addAttribute('intBuff', $dragon['buff']);
			$dragonEl->addAttribute('intDebuff', $dragon['debuff']);
			$dragonEl->addAttribute('intColorDskin', $dragon['colorDSkin']);
			$dragonEl->addAttribute('intColorDeye', $dragon['colorDEye']);
			$dragonEl->addAttribute('intColorDhorn', $dragon['colorDHorn']);
			$dragonEl->addAttribute('intColorDwing', $dragon['colorDWing']);
			$dragonEl->addAttribute('intHeadID', $dragon['headId']);
			$dragonEl->addAttribute('strHeadFilename', $dragon['headFileName']);
			$dragonEl->addAttribute('intWingID', $dragon['wingId']);
			$dragonEl->addAttribute('strWingFilename', $dragon['wingFileName']);
			$dragonEl->addAttribute('intTailID', $dragon['tailId']);
			$dragonEl->addAttribute('strTailFilename', $dragon['tailFileName']);
			$dragonEl->addAttribute('strFilename', $dragon['filename']);
			$dragonEl->addAttribute('intMin', $dragon['min']);
			$dragonEl->addAttribute('intMax', $dragon['max']);
			$dragonEl->addAttribute('strType', $dragon['type']);
			$dragonEl->addAttribute('strElement', $dragon['element']);
			$dragonEl->addAttribute('intColorDelement', $dragon['colorDElement']);
        }
        return $xml;
    }

    public function questStringSaved(): \SimpleXMLElement {
        return new \SimpleXMLElement('<SaveQuestString/>');
    }

    public function skillStringSaved(): \SimpleXMLElement {
        return new \SimpleXMLElement('<SaveSkillString/>');
    }
	
	public function armorStringSaved(): \SimpleXMLElement {
        return new \SimpleXMLElement('<SaveArmorString/>');
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

        if($item = $quest->getItemReward()) {
            $itemEl = $questRewardEl->addChild('items');

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

            $category = $item->getCategory();
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

    public function questItemReward(CharacterItemVO $charItem): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<questreward/>');
        $xml->addChild('CharItemID', $charItem->id);
        return $xml;
    }
	
	public function questMerged($questMerge): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><QuestMerge xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
        $mergeE1 = $xml->addChild('QuestMerge');
		$mergeE1->addAttribute('ItemID', $questMerge['itemId']);
		$mergeE1->addAttribute('CharItemID', $questMerge['charItemId']);
		$mergeE1->addAttribute('intQty', $questMerge['itemQty']);
		$mergeE1->addAttribute('intString', $questMerge['stringType']);
		$mergeE1->addAttribute('intIndex', $questMerge['stringIndex']);
		$mergeE1->addAttribute('intValue', $questMerge['stringValue']);

        return $xml;
	}

	public function goldSubtracted(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<SubtractGold/>');
        $xml->addChild('status', 'SUCCESS');

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
		
		foreach($char->getBag() as $characterItem) {
			if (!$characterItem->banked) {
				continue; // Skip items that are not banked
			}
			$itemEl = $bankEl->addChild('items');
			
            $itemEl->addAttribute('CharItemID', $characterItem->id);
            $itemEl->addAttribute('intCount', $characterItem->count);

            $item = $characterItem->getItem();
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

            $category = $item->getCategory();
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

	public function bankSlotsBought(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><BuyBankSlots xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
        $xml->addChild('status', 'SUCCESS');

        return $xml;
    }

	public function bagSlotsBought(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><BuyBagSlots xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
        $xml->addChild('status', 'SUCCESS');

        return $xml;
    }

	public function armorColorChanged(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><hairBuy xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');

        return $xml;
    }

    public function characterPage(CharacterVO $char): array {

        $user = $char->getUser();

        $hair = $char->getHair();
        $class = $char->getClass();
        $race = $char->getRace();

        $armor = $class->getArmor();
        $weapon = $class->getWeapon();

        return [
            "Name" => $char->name,
            "Level" => $char->level,
            "ClassName" => $class->name,
            "ClassFileName" => $class->swf,
            "Gender" => $char->gender,
            "Race" => $race->name,
            "Gold" => $char->gold,
            "DA" => $char->dragonAmulet ? 1 : 0,
            "strArmor" => $char->armor,
            "strSkills" => $char->skills,
            "strQuests" => $char->quests,
            "Founder" => $user->id == 1 ? 1 : 0,
            "HairColor" => \hexdec($char->colorHair),
            "SkinColor" => \hexdec($char->colorSkin),
            "BaseColor" => \hexdec($char->colorBase),
            "TrimColor" => \hexdec($char->colorTrim),
            "HairFileName" => $hair->swf,
            "WeaponFilename" => $weapon->swf ?: "none",
            "HelmFilename" => "none",
            "BackFilename" => "none",
            "NoDragon" => "right",
            "DHead" => "none",
            "DWing" => "none",
            "DTail" => "none",
            "DskinC" => "",
            "DeyeC" => "",
            "DhornC" => "",
            "DwingC" => "",
            "Created" => \date("Y-m-d", \strtotime($char->createdAt)),
            "LastPlayed" => \date("Y-m-d", \strtotime($char->lastTimeSeen)),
            "up" => "1",
        ];
    }

}