<?php
namespace hiperesp\server\projection;

use hiperesp\server\models\ItemCategoryModel;
use hiperesp\server\models\MergeShopItemModel;
use hiperesp\server\vo\MergeShopVO;

class MergeShopProjection extends Projection {

    private MergeShopItemModel $mergeShopItemModel;
    private ItemCategoryModel $itemCategoryModel;

    public function loaded(MergeShopVO $shop): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<mergeshop/>');
        $shopEl = $xml->addChild('mergeshop');
        $shopEl->addAttribute('MSID', $shop->id);
        $shopEl->addAttribute('strCharacterName', $shop->name);

        foreach($this->mergeShopItemModel->getByShop($shop) as $mergeShopItem) {
            $itemEl = $shopEl->addChild('items');

            $itemEl->addAttribute('ID', $mergeShopItem->id);

            $item1 = $mergeShopItem->getItem1();
            $itemEl->addAttribute('ItemID1', $item1->id);
            $itemEl->addAttribute('Item1', $item1->name);
            $itemEl->addAttribute('Qty1', $mergeShopItem->amount1);

            $item2 = $mergeShopItem->getItem2();
            $itemEl->addAttribute('ItemID2', $item2->id);
            $itemEl->addAttribute('Item2', $item2->name);
            $itemEl->addAttribute('Qty2', $mergeShopItem->amount2);

            $itemEl->addAttribute('intString', $mergeShopItem->string);
            $itemEl->addAttribute('intIndex', $mergeShopItem->index);
            $itemEl->addAttribute('intValue', $mergeShopItem->value);
            $itemEl->addAttribute('intReqdLevel', $mergeShopItem->level);

            $item = $mergeShopItem->getItem();
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

            $category = $this->itemCategoryModel->getByItem($item);
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

}