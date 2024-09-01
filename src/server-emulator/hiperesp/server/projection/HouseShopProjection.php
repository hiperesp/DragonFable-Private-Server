<?php
namespace hiperesp\server\projection;

use hiperesp\server\models\HouseModel;
use hiperesp\server\vo\HouseShopVO;

class HouseShopProjection extends Projection {

    private HouseModel $houseModel;

    public function loaded(HouseShopVO $shop): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<houseshop/>');
        $shopEl = $xml->addChild('shop');
        $shopEl->addAttribute('ShopID', $shop->id);
        $shopEl->addAttribute('strCharacterName', $shop->name);

        foreach($this->houseModel->getByShop($shop) as $item) {
            $itemEl = $shopEl->addChild('sHouses');

            $itemEl->addAttribute('HouseID', $item->id);
            $itemEl->addAttribute('strHouseName', $item->name);
            $itemEl->addAttribute('strHouseDescription', $item->description);
            $itemEl->addAttribute('bitVisible', $item->visible);
            $itemEl->addAttribute('bitDestroyable', $item->destroyable);
            $itemEl->addAttribute('bitEquippable', $item->equippable);
            $itemEl->addAttribute('bitRandomDrop', $item->randomDrop);
            $itemEl->addAttribute('bitSellable', $item->sellable);
            $itemEl->addAttribute('bitDragonAmulet', $item->dragonAmulet);
            $itemEl->addAttribute('bitEnc', $item->enc);
            $itemEl->addAttribute('intCost', $item->cost);
            $itemEl->addAttribute('intCurrency', $item->currency);
            $itemEl->addAttribute('intRarity', $item->rarity);
            $itemEl->addAttribute('intLevel', $item->level);
            $itemEl->addAttribute('intCategory', $item->category);
            $itemEl->addAttribute('intEquipSpot', $item->equipSpot);
            $itemEl->addAttribute('intType', $item->type);
            $itemEl->addAttribute('bitRandom', $item->random);
            $itemEl->addAttribute('intElement', $item->element);
            $itemEl->addAttribute('strType', $item->type);
            $itemEl->addAttribute('strIcon', $item->icon);
            $itemEl->addAttribute('strDesignInfo', $item->designInfo);
            $itemEl->addAttribute('strFileName', $item->swf);
            $itemEl->addAttribute('intRegion', $item->region);
            $itemEl->addAttribute('intTheme', $item->theme);
            $itemEl->addAttribute('intSize', $item->size);
            $itemEl->addAttribute('intBaseHP', $item->baseHP);
            $itemEl->addAttribute('intStorageSize', $item->storageSize);
            $itemEl->addAttribute('intMaxGuards', $item->maxGuards);
            $itemEl->addAttribute('intMaxRooms', $item->maxRooms);
            $itemEl->addAttribute('intMaxExtItems', $item->maxExtItems);

        }

        return $xml;
    }

}