<?php
namespace hiperesp\server\projection;

use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\MonsterModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\WeaponModel;
use hiperesp\server\vo\QuestVO;

class QuestProjection extends Projection {

    private MonsterModel $monsterModel;
    private ArmorModel $armorModel;
    private WeaponModel $weaponModel;
    private RaceModel $raceModel;

    public function loaded(QuestVO $quest): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<quest/>');
        $questEl = $xml->addChild('quest');
        $questEl->addAttribute('QuestID', $quest->id);
        $questEl->addAttribute('strName', $quest->name);
        $questEl->addAttribute('strDescription', $quest->description);
        $questEl->addAttribute('strComplete', $quest->complete);
        $questEl->addAttribute('strFileName', $quest->swf);
        $questEl->addAttribute('strXFileName', $quest->swfX);
        $questEl->addAttribute('intMaxSilver', $quest->maxSilver);
        $questEl->addAttribute('intMaxGold', $quest->maxGold);
        $questEl->addAttribute('intMaxGems', $quest->maxGems);
        $questEl->addAttribute('intMaxExp', $quest->maxExp);
        $questEl->addAttribute('intMinTime', $quest->minTime);
        $questEl->addAttribute('intCounter', $quest->counter);
        $questEl->addAttribute('strExtra', $quest->extra);
        $questEl->addAttribute('intDailyIndex', $quest->dailyIndex);
        $questEl->addAttribute('intDailyReward', $quest->dailyReward);
        $questEl->addAttribute('intMonsterMinLevel', $quest->monsterMinLevel);
        $questEl->addAttribute('intMonsterMaxLevel', $quest->monsterMaxLevel);
        $questEl->addAttribute('strMonsterType', $quest->monsterType);
        $questEl->addAttribute('strMonsterGroupFileName', $quest->monsterGroupSwf);

        foreach($this->monsterModel->getByQuest($quest) as $index => $monster) {
            $monsterEl = $questEl->addChild('monsters');

            $monsterEl->addAttribute('MonsterID', $monster->id);
            $monsterEl->addAttribute('intMonsterRef', $index);
            $monsterEl->addAttribute('strCharacterName', $monster->name);
            $monsterEl->addAttribute('intLevel', $monster->level);
            $monsterEl->addAttribute('intExp', $monster->experience);
            $monsterEl->addAttribute('intHP', $monster->hitPoints);
            $monsterEl->addAttribute('intMP', $monster->manaPoints);
            $monsterEl->addAttribute('intSilver', $monster->silver);
            $monsterEl->addAttribute('intGold', $monster->gold);
            $monsterEl->addAttribute('intGems', $monster->gems);
            $monsterEl->addAttribute('intDragonCoins', $monster->coins);
            $monsterEl->addAttribute('strGender', $monster->gender);
            $monsterEl->addAttribute('intHairStyle', $monster->hairStyle);
            $monsterEl->addAttribute('intColorHair', \hexdec($monster->colorHair));
            $monsterEl->addAttribute('intColorSkin', \hexdec($monster->colorSkin));
            $monsterEl->addAttribute('intColorBase', \hexdec($monster->colorBase));
            $monsterEl->addAttribute('intColorTrim', \hexdec($monster->colorTrim));
            $monsterEl->addAttribute('intStr', $monster->strength);
            $monsterEl->addAttribute('intDex', $monster->dexterity);
            $monsterEl->addAttribute('intInt', $monster->intelligence);
            $monsterEl->addAttribute('intLuk', $monster->luck);
            $monsterEl->addAttribute('intCha', $monster->charisma);
            $monsterEl->addAttribute('intEnd', $monster->endurance);
            $monsterEl->addAttribute('intWis', $monster->wisdom);

            $armor = $this->armorModel->getById($monster->armorId);
            $monsterEl->addAttribute('strArmorName', $armor->name);
            $monsterEl->addAttribute('strArmorDescription', $armor->description);
            $monsterEl->addAttribute('strArmorDesignInfo', $armor->designInfo);
            $monsterEl->addAttribute('strArmorResists', $armor->resists);
            $monsterEl->addAttribute('intDefMelee', $armor->defenseMelee);
            $monsterEl->addAttribute('intDefPierce', $armor->defensePierce);
            $monsterEl->addAttribute('intDefMagic', $armor->defenseMagic);
            $monsterEl->addAttribute('intParry', $armor->parry);
            $monsterEl->addAttribute('intDodge', $armor->dodge);
            $monsterEl->addAttribute('intBlock', $armor->block);

            $weapon = $this->weaponModel->getById($monster->weaponId);
            $monsterEl->addAttribute('strWeaponName', $weapon->name);
            $monsterEl->addAttribute('strWeaponDescription', $weapon->description);
            $monsterEl->addAttribute('strWeaponDesignInfo', $weapon->designInfo);
            $monsterEl->addAttribute('strWeaponResists', $weapon->resists);
            $monsterEl->addAttribute('strType', $weapon->type);
            $monsterEl->addAttribute('intCrit', $weapon->critical);
            $monsterEl->addAttribute('intDmgMin', $weapon->damageMin);
            $monsterEl->addAttribute('intDmgMax', $weapon->damageMax);
            $monsterEl->addAttribute('intBonus', $weapon->bonus);
            $monsterEl->addAttribute('strWeaponFile', $weapon->swf);
            $monsterEl->addAttribute('strElement', $monster->element);
            $monsterEl->addAttribute('strMovName', $monster->movName);
            $monsterEl->addAttribute('strMonsterFileName', $monster->swf);

            $race = $this->raceModel->getById($monster->raceId);
            $monsterEl->addAttribute('RaceID', $monster->raceId);
            $monsterEl->addAttribute('strRaceName', $race->name);
            $monsterEl->addAttribute('strRaceResists', $race->resists);
        }

        return $xml;
    }

}