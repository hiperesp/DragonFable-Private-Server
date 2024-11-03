<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\ItemVO;
use hiperesp\server\vo\ItemShopVO;
use hiperesp\server\vo\MergeShopItemVO;
use hiperesp\server\vo\MergeShopVO;

class MergeShopItemModel extends Model {

    const COLLECTION = 'mergeShop_item';

    public function getById(int $itemId): ItemVO {
        $item = $this->storage->select(self::COLLECTION, ['id' => $itemId]);
        if(isset($item[0]) && $item = $item[0]) {
            return new ItemVO($item);
        }
        throw new DFException(DFException::ITEM_NOT_FOUND);
    }

    /**
     * @return array<MergeShopItemVO>
     */
    public function getByShop(MergeShopVO $shop): array {
        $items = $this->storage->select(self::COLLECTION, ['mergeShopId' => $shop->id], null);
        return \array_map(function(array $item): MergeShopItemVO {
            return new MergeShopItemVO($item);
        }, $items);
    }

}