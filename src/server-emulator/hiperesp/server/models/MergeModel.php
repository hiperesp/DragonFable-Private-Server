<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\MergeShopVO;
use hiperesp\server\vo\MergeVO;

class MergeModel extends Model {

    const COLLECTION = 'merge';
    const MERGE_SHOP_ASSOCIATION = 'mergeShop_merge';

    public function getById(int $itemId): MergeVO {
        $item = $this->storage->select(self::COLLECTION, ['id' => $itemId]);
        if(isset($item[0]) && $item = $item[0]) {
            return new MergeVO($item);
        }
        throw new DFException(DFException::MERGE_NOT_FOUND);
    }

    /** @return array<MergeVO> */
    public function getByShop(MergeShopVO $shop): array {
        $itemIds = \array_map(function(array $item): int {
            return (int)$item['mergeId'];
        }, $this->storage->select(self::MERGE_SHOP_ASSOCIATION, ['mergeShopId' => $shop->id], null));

        return \array_map(function(array $item): MergeVO {
            return new MergeVO($item);
        }, $this->storage->select(self::COLLECTION, ['id' => $itemIds], null));
    }

}