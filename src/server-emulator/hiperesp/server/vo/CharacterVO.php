<?php
namespace hiperesp\server\vo;

use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\HairModel;
use hiperesp\server\models\QuestModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\WeaponModel;

class CharacterVO extends ValueObject {

    public readonly int $id;

    public readonly int $userId;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly string $name;

    public readonly int $level;
    public readonly int $experience;
    public readonly int $experienceToLevel;

    public readonly int $hitPoints;
    public readonly int $manaPoints;

    public readonly int $silver;
    public readonly int $gold;
    public readonly int $gems;
    public readonly int $coins;

    public readonly int $maxBagSlots;
    public readonly int $maxBankSlots;
    public readonly int $maxHouseSlots;
    public readonly int $maxHouseItemSlots;

    public readonly bool $hasDragonAmulet;
    public readonly int $accessLevel;

    public readonly string $gender;
    public readonly string $pronoun;

    public readonly int $hairId;
    public readonly string $colorHair;
    public readonly string $colorSkin;
    public readonly string $colorBase;
    public readonly string $colorTrim;

    public readonly int $questId;

    public readonly int $strength;
    public readonly int $dexterity;
    public readonly int $intelligence;
    public readonly int $luck;
    public readonly int $charisma;
    public readonly int $endurance;
    public readonly int $wisdom;

    public readonly int $skillPoints;
    public readonly int $statPoints;

    public readonly int $status;
    public readonly int $daily;

    public readonly string $armor;
    public readonly string $skills;
    public readonly string $quests;

    public readonly int $raceId;
    public readonly int $classId;
    public readonly int $baseClassId;

    public function asCreatedResponse(): array {
        return [
            "code" => 0,
            "reason" => "Character created Successfully!",
            "message" => "none",
            "action" => "none"
        ];
    }

    public function asDeleteResponse(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<charDelete/>');
        $charDelete = $xml->addChild('charDelete');
        $charDelete->addAttribute('message', 'Character Deleteion Successful!!');

        return $xml;
    }

    public function asDragonAmuletCheckResponse(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<character/>');
        $character = $xml->addChild('character');
        $character->addAttribute('intDragonAmulet', $this->hasDragonAmulet ? '1' : '0');

        return $xml;
    }

    public function asLoadResponse(UserVO $user, RaceModel $raceModel, QuestModel $questModel, ClassModel $classModel, ArmorModel $armorModel, WeaponModel $weaponModel, HairModel $hairModel): \SimpleXMLElement {
        if($user->id != $this->userId) {
            throw new \Exception('Character does not belong to the user');
        }

        $race = $raceModel->getByCharacter($this);
        $quest = $questModel->getByCharacter($this);
        $class = $classModel->getByCharacter($this);
        $armor = $armorModel->getByClass($class);
        $weapon = $weaponModel->getByCharacter($class);
        $hair = $hairModel->getByCharacter($this);

        $xml = new \SimpleXMLElement('<character/>');
        $character = $xml->addChild('character');
        $character->addAttribute('CharID', $this->id);
        $character->addAttribute('strCharacterName', $this->name);
        $character->addAttribute('dateCreated', \date('Y-m-d\TH:i:s', \strtotime($this->createdAt)));
        $character->addAttribute('isBirthday', $user->isBirthday(\date('c')) ? '1' : '0');
        $character->addAttribute('intLevel', $this->level);
        $character->addAttribute('intExp', $this->experience);
        $character->addAttribute('intHP', $this->hitPoints);
        $character->addAttribute('intMP', $this->manaPoints);
        $character->addAttribute('intSilver', $this->silver);
        $character->addAttribute('intGold', $this->gold);
        $character->addAttribute('intGems', $this->gems);
        $character->addAttribute('intCoins', $this->coins);
        $character->addAttribute('intMaxBagSlots', $this->maxBagSlots);
        $character->addAttribute('intMaxBankSlots', $this->maxBankSlots);
        $character->addAttribute('intMaxHouseSlots', $this->maxHouseSlots);
        $character->addAttribute('intMaxHouseItemSlots', $this->maxHouseItemSlots);
        $character->addAttribute('intDragonAmulet', $this->hasDragonAmulet ? '1' : '0');
        $character->addAttribute('intAccesslevel', $this->accessLevel);
        $character->addAttribute('strGender', $this->gender);
        $character->addAttribute('strPronoun', $this->pronoun);
        $character->addAttribute('intColorHair', \hexdec($this->colorHair));
        $character->addAttribute('intColorSkin', \hexdec($this->colorSkin));
        $character->addAttribute('intColorBase', \hexdec($this->colorBase));
        $character->addAttribute('intColorTrim', \hexdec($this->colorTrim));
        $character->addAttribute('intStr', $this->strength);
        $character->addAttribute('intDex', $this->dexterity);
        $character->addAttribute('intInt', $this->intelligence);
        $character->addAttribute('intLuk', $this->luck);
        $character->addAttribute('intCha', $this->charisma);
        $character->addAttribute('intEnd', $this->endurance);
        $character->addAttribute('intWis', $this->wisdom);
        $character->addAttribute('intSkillPoints', $this->skillPoints);
        $character->addAttribute('intStatPoints', $this->statPoints);
        $character->addAttribute('intCharStatus', $this->status);
        $character->addAttribute('intDaily', $this->daily);
        $character->addAttribute('strArmor', $this->armor);
        $character->addAttribute('strSkills', $this->skills);
        $character->addAttribute('strQuests', $this->quests);
        $character->addAttribute('intExpToLevel', $this->experienceToLevel);
        $character->addAttribute('RaceID', $race->id);
        $character->addAttribute('strRaceName', $race->name);
        $character->addAttribute('GuildID', 1);
        $character->addAttribute('strGuildName', "None");

        $character->addAttribute('QuestID', $quest->id);
        $character->addAttribute('strQuestName', $quest->name);
        $character->addAttribute('strQuestFileName', $quest->swf);
        $character->addAttribute('strXQuestFileName', $quest->swfX);
        $character->addAttribute('strExtra', $quest->extra);

        $character->addAttribute('BaseClassID', $this->baseClassId);

        $character->addAttribute('ClassID', $class->id);
        $character->addAttribute('strClassName', $class->name);
        $character->addAttribute('strClassFileName', $class->swf);
        $character->addAttribute('strElement', $class->element);
        $character->addAttribute('intSavable', $class->savable);

        $character->addAttribute('strArmorName', $armor->name);
        $character->addAttribute('strArmorDescription', $armor->description);
        $character->addAttribute('strArmorResists', $armor->resists);
        $character->addAttribute('intDefMelee', $armor->defenseMelee);
        $character->addAttribute('intDefPierce', $armor->defensePierce);
        $character->addAttribute('intDefMagic', $armor->defenseMagic);
        $character->addAttribute('intParry', $armor->parry);
        $character->addAttribute('intDodge', $armor->dodge);
        $character->addAttribute('intBlock', $armor->block);

        $character->addAttribute('strWeaponName', $weapon->name);
        $character->addAttribute('strWeaponDescription', $weapon->description);
        $character->addAttribute('strWeaponDesignInfo', $weapon->designInfo);
        $character->addAttribute('strWeaponResists', $weapon->resists);
        $character->addAttribute('intWeaponLevel', $weapon->level);
        $character->addAttribute('strWeaponIcon', $weapon->icon);
        $character->addAttribute('strType', $weapon->type);
        $character->addAttribute('strItemType', $weapon->itemType);
        $character->addAttribute('intCrit', $weapon->critical);
        $character->addAttribute('intDmgMin', $weapon->damageMin);
        $character->addAttribute('intDmgMax', $weapon->damageMax);
        $character->addAttribute('intBonus', $weapon->bonus);

        $character->addAttribute('strEquippable', "Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket,Artifact");

        $character->addAttribute('strHairFileName', $hair->swf);
        $character->addAttribute('intHairFrame', 1);

        $character->addAttribute('gemReward', 0);
        $character->addAttribute('intDailyRoll', 1); // unknown meaning

        return $xml;
    }

    public function asSaveQuestStringResponse(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<SaveQuestString/>');
        return $xml;
    }

    public function asExpSaveResponse(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<questreward/>');
        $questreward = $xml->addChild('questreward');
        $questreward->addAttribute('intLevel', $this->level);
        $questreward->addAttribute('intExp', $this->experience);
        $questreward->addAttribute('intHP', $this->hitPoints);
        $questreward->addAttribute('intMP', $this->manaPoints);
        $questreward->addAttribute('intSilver', $this->silver);
        $questreward->addAttribute('intGold', $this->gold);
        $questreward->addAttribute('intGems', $this->gems);
        $questreward->addAttribute('intSkillPoints', $this->skillPoints);
        $questreward->addAttribute('intStatPoints', $this->statPoints);
        $questreward->addAttribute('intExpToLevel', $this->experienceToLevel);
        return $xml;
    }
}
