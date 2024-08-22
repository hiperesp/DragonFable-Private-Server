<?php
namespace hiperesp\server\vo;

use hiperesp\server\models\ItemModel;

class ShopVO extends ValueObject {

    public readonly int $id;
    public readonly string $name;
    public readonly int $count;


    public function asLoadShopResponse(ItemModel $itemModel): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<shop/>');
        $shop = $xml->addChild('shop');
        $shop->addAttribute('ShopID', $this->id);
        $shop->addAttribute('strCharacterName', $this->name);
        $shop->addAttribute('intCount', $this->count);

        foreach($itemModel->getByShop($this) as $item) {
            $itemElement = $shop->addChild('items');

            $itemElement->addAttribute('ItemID', $item->id);
            $itemElement->addAttribute('strItemName', $item->name);
            $itemElement->addAttribute('strItemDescription', $item->description);
            $itemElement->addAttribute('bitVisible', $item->visible);
            $itemElement->addAttribute('bitDestroyable', $item->destroyable);
            $itemElement->addAttribute('bitSellable', $item->sellable);
            $itemElement->addAttribute('bitDragonAmulet', $item->dragonAmulet);
            $itemElement->addAttribute('intCurrency', $item->currency);
            $itemElement->addAttribute('intCost', $item->cost);
            $itemElement->addAttribute('intMaxStackSize', $item->maxStackSize);
            $itemElement->addAttribute('intBonus', $item->bonus);
            $itemElement->addAttribute('intRarity', $item->rarity);
            $itemElement->addAttribute('intLevel', $item->level);
            $itemElement->addAttribute('strType', $item->type);
            $itemElement->addAttribute('strElement', $item->element);
            $itemElement->addAttribute('strCategory', $item->category);
            $itemElement->addAttribute('strEquipSpot', $item->equipSpot);
            $itemElement->addAttribute('strItemType', $item->itemType);
            $itemElement->addAttribute('strFileName', $item->swf);
            $itemElement->addAttribute('strIcon', $item->icon);
            $itemElement->addAttribute('intStr', $item->strength);
            $itemElement->addAttribute('intDex', $item->dexterity);
            $itemElement->addAttribute('intInt', $item->intelligence);
            $itemElement->addAttribute('intLuk', $item->luck);
            $itemElement->addAttribute('intCha', $item->charisma);
            $itemElement->addAttribute('intEnd', $item->endurance);
            $itemElement->addAttribute('intWis', $item->wisdom);
            $itemElement->addAttribute('intMin', $item->damageMin);
            $itemElement->addAttribute('intMax', $item->damageMax);
            $itemElement->addAttribute('intDefMelee', $item->defenseMelee);
            $itemElement->addAttribute('intDefPierce', $item->defensePierce);
            $itemElement->addAttribute('intDefMagic', $item->defenseMagic);
            $itemElement->addAttribute('intCrit', $item->critical);
            $itemElement->addAttribute('intParry', $item->parry);
            $itemElement->addAttribute('intDodge', $item->dodge);
            $itemElement->addAttribute('intBlock', $item->block);
            $itemElement->addAttribute('strResists', $item->resists);

        }

        return $xml;
    }

}