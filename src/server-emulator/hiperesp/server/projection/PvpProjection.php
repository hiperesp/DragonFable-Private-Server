<?php
namespace hiperesp\server\projection;

use hiperesp\server\attributes\Inject;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class PvpProjection extends Projection {

    #[Inject] private SettingsVO $settings;

    public function loaded(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><PvPChar xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
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
            if (!$characterItem->equipped) {
                continue; // Skip items that are not equipped
            }

            $itemEl = $charEl->addChild('items');
            $item = $characterItem->getItem();
            $itemEl->addAttribute('ItemID', $item->id);
            $itemEl->addAttribute('strItemName', $item->name);
            $itemEl->addAttribute('strItemDescription', $item->description);
            $itemEl->addAttribute('bitEquipped', $characterItem->equipped ? 1 : 0);
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
            $itemEl->addAttribute('intDefRange', $item->defensePierce);
            $itemEl->addAttribute('intDefMagic', $item->defenseMagic);
            $itemEl->addAttribute('intCrit', $item->critical);
            $itemEl->addAttribute('intParry', $item->parry);
            $itemEl->addAttribute('intDodge', $item->dodge);
            $itemEl->addAttribute('intBlock', $item->block);
            $itemEl->addAttribute('strResists', $item->resists);
            $itemEl->addAttribute('CharItemID', $characterItem->id);
        }
        return $xml;
    }

    public function loadedDragonRider(CharacterVO $char): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><PvPDragon xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
        $charEl = $xml->addChild('character');
        $charEl->addAttribute('CharID', $char->id);
        $charEl->addAttribute('strCharacterName', $char->name);
        $charEl->addAttribute('intLevel', $char->level);
        $charEl->addAttribute('intExp', $char->experience);
        $charEl->addAttribute('intHP', $char->hitPoints);
        $charEl->addAttribute('intMP', $char->manaPoints);
        $charEl->addAttribute('intGold', $char->gold);
        $charEl->addAttribute('intCoins', $char->coins);
        $charEl->addAttribute('strGender', $char->gender);
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
        $charEl->addAttribute('strArmor', $char->armor);
        $charEl->addAttribute('intExpToLevel', $char->experienceToLevel);

        $race = $char->getRace();
        $charEl->addAttribute('RaceID', $race->id);
        $charEl->addAttribute('strRaceName', $race->name);

        $class = $char->getClass();
        $charEl->addAttribute('ClassID', 13);
        $charEl->addAttribute('strClassName', "DragonRider");
        $charEl->addAttribute('strClassFileName', "class-dragonrider-NEWr4.swf?ver=1");

        $armor = $class->getArmor();
        $charEl->addAttribute('strArmorResists', "");
        $charEl->addAttribute('intDefMelee', 5);
        $charEl->addAttribute('intDefPierce', 5);
        $charEl->addAttribute('intDefMagic', 5);
        $charEl->addAttribute('intParry', 0);
        $charEl->addAttribute('intDodge', 0);
        $charEl->addAttribute('intBlock', 0);

        $weapon = $class->getWeapon();
        $charEl->addAttribute('strWeaponResists', "");
        $charEl->addAttribute('strType', "Melee");
        $charEl->addAttribute('strItemType', "Scythe");
        $charEl->addAttribute('intCrit', 5);
        $charEl->addAttribute('intDmgMin', 700);
        $charEl->addAttribute('intDmgMax', 800);
        $charEl->addAttribute('intBonus', 0);
        $charEl->addAttribute('strEquippable', "");

        $hair = $char->getHair();
        $charEl->addAttribute('strHairFileName', $hair->swf);
        $charEl->addAttribute('intHairFrame', 1);
        $charEl->addAttribute('strElement', "None");

        $dragon = $char->getDragon();
        if(isset($dragon['id'])) {
            $dragonEl = $charEl->addChild('dragon');
            $dragonEl->addAttribute('idCore_CharDragons', $dragon['id']);
            $dragonEl->addAttribute('strName', $dragon['name']);
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
            $dragonEl->addAttribute('strElement', $dragon['element']);
            $dragonEl->addAttribute('intColorDelement', $dragon['colorDElement']);
        }
        else {
            return new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><PvPDragon xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
        }
        return $xml;
    }

}