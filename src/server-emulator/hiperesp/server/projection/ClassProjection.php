<?php
namespace hiperesp\server\projection;

use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\WeaponModel;
use hiperesp\server\vo\ClassVO;

class ClassProjection extends Projection {

    private ArmorModel $armorModel;
    private WeaponModel $weaponModel;

    public function changed(ClassVO $class): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<changeClass/>');
        $charEl = $xml->addChild('character');

        $charEl->addAttribute('ClassID', $class->id);
        $charEl->addAttribute('strClassName', $class->name);
        $charEl->addAttribute('strClassFileName', $class->swf);
        $charEl->addAttribute('strElement', $class->element);
        $charEl->addAttribute('intSavable', $class->savable);
        $charEl->addAttribute('strEquippable', $class->getEquippable());

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
        $charEl->addAttribute('strWeaponResists', $weapon->resists);
        $charEl->addAttribute('intWeaponLevel', $weapon->level);
        $charEl->addAttribute('strWeaponIcon', $weapon->icon);
        $charEl->addAttribute('strType', $weapon->type);
        $charEl->addAttribute('strItemType', $weapon->itemType);
        $charEl->addAttribute('intCrit', $weapon->critical);
        $charEl->addAttribute('intDmgMin', $weapon->damageMin);
        $charEl->addAttribute('intDmgMax', $weapon->damageMax);
        $charEl->addAttribute('intBonus', $weapon->bonus);

        return $xml;
    }

    public function loaded(ClassVO $class): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<ClassLoad/>');
        $charEl = $xml->addChild('character');

        $charEl->addAttribute('ClassID', $class->id);
        $charEl->addAttribute('strClassName', $class->name);
        $charEl->addAttribute('strClassFileName', $class->swf);
        $charEl->addAttribute('strElement', $class->element);
        $charEl->addAttribute('intSavable', $class->savable);
        $charEl->addAttribute('strEquippable', $class->getEquippable());

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
        $charEl->addAttribute('strWeaponResists', $weapon->resists);
        $charEl->addAttribute('intWeaponLevel', $weapon->level);
        $charEl->addAttribute('strWeaponIcon', $weapon->icon);
        $charEl->addAttribute('strType', $weapon->type);
        $charEl->addAttribute('strItemType', $weapon->itemType);
        $charEl->addAttribute('intCrit', $weapon->critical);
        $charEl->addAttribute('intDmgMin', $weapon->damageMin);
        $charEl->addAttribute('intDmgMax', $weapon->damageMax);
        $charEl->addAttribute('intBonus', $weapon->bonus);

        return $xml;
    }
}