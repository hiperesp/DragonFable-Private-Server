<?php
namespace hiperesp\server\vo;

use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\MonsterModel;
use hiperesp\server\models\QuestModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\WeaponModel;

class QuestVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;
    public readonly string $description;
    public readonly string $complete;

    public readonly string $swf;
    public readonly string $swfX;

    public readonly int $maxSilver;
    public readonly int $maxGold;
    public readonly int $maxGems;
    public readonly int $maxExp;

    public readonly int $minTime;
    public readonly int $counter;

    public readonly string $extra;

    public readonly int $dailyIndex;
    public readonly int $dailyReward;

    public readonly int $monsterMinLevel;
    public readonly int $monsterMaxLevel;

    public readonly string $monsterType;
    public readonly string $monsterGroupSwf;

    public function __construct(array $data) {

        $this->id = $data['id'];

        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->complete = $data['complete'];

        $this->swf = $data['swf'];
        $this->swfX = $data['swfX'];

        $this->maxSilver = $data['maxSilver'];
        $this->maxGold = $data['maxGold'];
        $this->maxGems = $data['maxGems'];
        $this->maxExp = $data['maxExp'];

        $this->minTime = $data['minTime'];
        $this->counter = $data['counter'];

        $this->extra = $data['extra'];

        $this->dailyIndex = $data['dailyIndex'];
        $this->dailyReward = $data['dailyReward'];

        $this->monsterMinLevel = $data['monsterMinLevel'];
        $this->monsterMaxLevel = $data['monsterMaxLevel'];
        $this->monsterType = $data['monsterType'];
        $this->monsterGroupSwf = $data['monsterGroupSwf'];

    }

    public function asLoadTownResponse(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<LoadTown/>');
        $newTown = $xml->addChild('newTown');
        $newTown->addAttribute('strQuestFileName', $this->swf);
        $newTown->addAttribute('strQuestXFileName', $this->swfX);
        $newTown->addAttribute('strExtra', $this->extra);

        return $xml;
    }

    public function asChangeHomeResponse(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<changeHomeTown/>');
        $newTown = $xml->addChild('newTown');
        $newTown->addAttribute('strQuestFileName', $this->swf);
        $newTown->addAttribute('strQuestXFileName', $this->swfX);
        $newTown->addAttribute('strExtra', $this->extra);

        return $xml;
    }

    public function asLoadQuestResponse(MonsterModel $monsterModel, ArmorModel $armorModel, WeaponModel $weaponModel, RaceModel $raceModel): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<quest/>');
        $quest = $xml->addChild('quest');
        $quest->addAttribute('QuestID', $this->id);
        $quest->addAttribute('strName', $this->name);
        $quest->addAttribute('strDescription', $this->description);
        $quest->addAttribute('strComplete', $this->complete);
        $quest->addAttribute('strFileName', $this->swf);
        $quest->addAttribute('strXFileName', $this->swfX);
        $quest->addAttribute('intMaxSilver', $this->maxSilver);
        $quest->addAttribute('intMaxGold', $this->maxGold);
        $quest->addAttribute('intMaxGems', $this->maxGems);
        $quest->addAttribute('intMaxExp', $this->maxExp);
        $quest->addAttribute('intMinTime', $this->minTime);
        $quest->addAttribute('intCounter', $this->counter);
        $quest->addAttribute('strExtra', $this->extra);
        $quest->addAttribute('intDailyIndex', $this->dailyIndex);
        $quest->addAttribute('intDailyReward', $this->dailyReward);
        $quest->addAttribute('intMonsterMinLevel', $this->monsterMinLevel);
        $quest->addAttribute('intMonsterMaxLevel', $this->monsterMaxLevel);
        $quest->addAttribute('strMonsterType', $this->monsterType);
        $quest->addAttribute('strMonsterGroupFileName', $this->monsterGroupSwf);

        foreach($monsterModel->getByQuest($this) as $index => $monster) {
            $monsterXml = $quest->addChild('monsters');

            $monsterXml->addAttribute('MonsterID', $monster->id);
            $monsterXml->addAttribute('intMonsterRef', $index);
            $monsterXml->addAttribute('strCharacterName', $monster->name);
            $monsterXml->addAttribute('intLevel', $monster->level);
            $monsterXml->addAttribute('intExp', $monster->experience);
            $monsterXml->addAttribute('intHP', $monster->hitPoints);
            $monsterXml->addAttribute('intMP', $monster->manaPoints);
            $monsterXml->addAttribute('intSilver', $monster->silver);
            $monsterXml->addAttribute('intGold', $monster->gold);
            $monsterXml->addAttribute('intGems', $monster->gems);
            $monsterXml->addAttribute('intDragonCoins', $monster->coins);
            $monsterXml->addAttribute('strGender', $monster->gender);
            $monsterXml->addAttribute('intHairStyle', $monster->hairStyle);
            $monsterXml->addAttribute('intColorHair', $monster->colorHair);
            $monsterXml->addAttribute('intColorSkin', $monster->colorSkin);
            $monsterXml->addAttribute('intColorBase', $monster->colorBase);
            $monsterXml->addAttribute('intColorTrim', $monster->colorTrim);
            $monsterXml->addAttribute('intStr', $monster->strength);
            $monsterXml->addAttribute('intDex', $monster->dexterity);
            $monsterXml->addAttribute('intInt', $monster->intelligence);
            $monsterXml->addAttribute('intLuk', $monster->luck);
            $monsterXml->addAttribute('intCha', $monster->charisma);
            $monsterXml->addAttribute('intEnd', $monster->endurance);
            $monsterXml->addAttribute('intWis', $monster->wisdom);

            $armor = $armorModel->getById($monster->armorId);
            $monsterXml->addAttribute('strArmorName', $armor->name);
            $monsterXml->addAttribute('strArmorDescription', $armor->description);
            $monsterXml->addAttribute('strArmorDesignInfo', $armor->designInfo);
            $monsterXml->addAttribute('strArmorResists', $armor->resists);
            $monsterXml->addAttribute('intDefMelee', $armor->defenseMelee);
            $monsterXml->addAttribute('intDefPierce', $armor->defensePierce);
            $monsterXml->addAttribute('intDefMagic', $armor->defenseMagic);
            $monsterXml->addAttribute('intParry', $armor->parry);
            $monsterXml->addAttribute('intDodge', $armor->dodge);
            $monsterXml->addAttribute('intBlock', $armor->block);

            $weapon = $weaponModel->getById($monster->weaponId);
            $monsterXml->addAttribute('strWeaponName', $weapon->name);
            $monsterXml->addAttribute('strWeaponDescription', $weapon->description);
            $monsterXml->addAttribute('strWeaponDesignInfo', $weapon->designInfo);
            $monsterXml->addAttribute('strWeaponResists', $weapon->resists);
            $monsterXml->addAttribute('strType', $weapon->type);
            $monsterXml->addAttribute('intCrit', $weapon->critical);
            $monsterXml->addAttribute('intDmgMin', $weapon->damageMin);
            $monsterXml->addAttribute('intDmgMax', $weapon->damageMax);
            $monsterXml->addAttribute('intBonus', $weapon->bonus);
            $monsterXml->addAttribute('strWeaponFile', $weapon->swf);
            $monsterXml->addAttribute('strElement', $monster->element);
            $monsterXml->addAttribute('strMovName', $monster->movName);
            $monsterXml->addAttribute('strMonsterFileName', $monster->swf);

            $race = $raceModel->getById($monster->raceId);
            $monsterXml->addAttribute('RaceID', $monster->raceId);
            $monsterXml->addAttribute('strRaceName', $race->name);
            $monsterXml->addAttribute('strRaceResists', $race->resists);

        }

        return $xml;
    }

}
