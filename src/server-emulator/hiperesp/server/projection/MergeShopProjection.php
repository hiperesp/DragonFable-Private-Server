<?php
namespace hiperesp\server\projection;

use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\MergeShopVO;
use hiperesp\server\vo\MergeVO;

class MergeShopProjection extends Projection {

    public function loaded(MergeShopVO $shop): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<mergeshop/>');
        $shopEl = $xml->addChild('mergeshop');
        $shopEl->addAttribute('MSID', $shop->id);
        $shopEl->addAttribute('strCharacterName', $shop->name);

        foreach($shop->getMerges() as $merge) {
            $itemEl = $shopEl->addChild('items');

            $itemEl->addAttribute('ID', $merge->id);

            $item1 = $merge->getItem1();
            $itemEl->addAttribute('ItemID1', $item1?->id ?: '-1');
            $itemEl->addAttribute('Item1', $item1?->name ?: '');
            $itemEl->addAttribute('Qty1', $merge->amount1);

            $item2 = $merge->getItem2();
            $itemEl->addAttribute('ItemID2', $item2?->id ?: '-1');
            $itemEl->addAttribute('Item2', $item2?->name ?: '');
            $itemEl->addAttribute('Qty2', $merge->amount2);

            $itemEl->addAttribute('intString', $merge->string);
            $itemEl->addAttribute('intIndex', $merge->index);
            $itemEl->addAttribute('intValue', $merge->value);
            $itemEl->addAttribute('intReqdLevel', $merge->level);

            $item = $merge->getItem();
            $itemEl->addAttribute('NewItemID', $item->id);
            $itemEl->addAttribute('strItemName', $item->name);
            $itemEl->addAttribute('strItemDescription', $item->description);
            $itemEl->addAttribute('bitDragonAmulet', $item->dragonAmulet);
            $itemEl->addAttribute('intCurrency', $item->currency);
            $itemEl->addAttribute('intCost', $item->cost);
            $itemEl->addAttribute('intMaxStackSize', $item->maxStackSize);
            $itemEl->addAttribute('intBonus', $item->bonus);
            $itemEl->addAttribute('intRarity', $item->rarity);
            $itemEl->addAttribute('intLevel', $item->level);
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

    public function merged(MergeVO $merge, CharacterItemVO $newCharItem, ?CharacterItemVO $removedCharItem1, ?CharacterItemVO $removedCharItem2): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<ItemMerge/>');
        $mergeEl = $xml->addChild('Merge');

        $mergeEl->addAttribute('ItemID1', $removedCharItem1?->itemId ?: '-1');
        $mergeEl->addAttribute('CharItemID1', $removedCharItem1?->id ?: '');
        $mergeEl->addAttribute('Qty1', $merge->amount1);

        $mergeEl->addAttribute('ItemID2', $removedCharItem2?->itemId ?: '-1');
        $mergeEl->addAttribute('CharItemID2', $removedCharItem2?->id ?: '');
        $mergeEl->addAttribute('Qty2', $merge->amount2);

        $item = $newCharItem->getItem();
        $newItemEl = $mergeEl->addChild('NewItem');
        $newItemEl->addAttribute('ItemID', $item->id);
        $newItemEl->addAttribute('strItemName', $item->name);
        $newItemEl->addAttribute('strItemDescription', $item->description);

        $newItemEl->addAttribute('bitVisible', $item->visible);
        $newItemEl->addAttribute('bitDestroyable', $item->destroyable);
        $newItemEl->addAttribute('bitSellable', $item->sellable);
        $newItemEl->addAttribute('bitDragonAmulet', $item->dragonAmulet);
        $newItemEl->addAttribute('intCurrency', $item->currency);
        $newItemEl->addAttribute('intCost', $item->cost);
        $newItemEl->addAttribute('intMaxStackSize', $item->maxStackSize);
        $newItemEl->addAttribute('intBonus', $item->bonus);
        $newItemEl->addAttribute('intRarity', $item->rarity);
        $newItemEl->addAttribute('intLevel', $item->level);
        $newItemEl->addAttribute('strType', $item->type);
        $newItemEl->addAttribute('strElement', $item->element);

        $category = $item->getCategory();
        $newItemEl->addAttribute('strCategory', $category->name);

        $newItemEl->addAttribute('strEquipSpot', $item->equipSpot);
        $newItemEl->addAttribute('strItemType', $item->itemType);
        $newItemEl->addAttribute('strFileName', $item->swf);
        $newItemEl->addAttribute('strIcon', $item->icon);
        $newItemEl->addAttribute('intStr', $item->strength);
        $newItemEl->addAttribute('intDex', $item->dexterity);
        $newItemEl->addAttribute('intInt', $item->intelligence);
        $newItemEl->addAttribute('intLuk', $item->luck);
        $newItemEl->addAttribute('intCha', $item->charisma);
        $newItemEl->addAttribute('intEnd', $item->endurance);
        $newItemEl->addAttribute('intWis', $item->wisdom);
        $newItemEl->addAttribute('intMin', $item->damageMin);
        $newItemEl->addAttribute('intMax', $item->damageMax);
        $newItemEl->addAttribute('intDefMelee', $item->defenseMelee);
        $newItemEl->addAttribute('intDefPierce', $item->defensePierce);
        $newItemEl->addAttribute('intDefMagic', $item->defenseMagic);
        $newItemEl->addAttribute('intCrit', $item->critical);
        $newItemEl->addAttribute('intParry', $item->parry);
        $newItemEl->addAttribute('intDodge', $item->dodge);
        $newItemEl->addAttribute('intBlock', $item->block);
        $newItemEl->addAttribute('strResists', $item->resists);

        $newItemEl->addAttribute('bitEquipped', $newCharItem->equipped);
        $newItemEl->addAttribute('CharItemID', $newCharItem->id);
        $newItemEl->addAttribute('intCount', $newCharItem->count);

        return $xml;
    }

}