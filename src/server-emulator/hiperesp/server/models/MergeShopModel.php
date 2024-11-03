<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\MergeShopVO;

class MergeShopModel extends Model {

    const COLLECTION = 'mergeShop';

    public function getById(int $shopId): MergeShopVO {
        $shop = $this->storage->select(self::COLLECTION, ['id' => $shopId]);
        if(isset($shop[0]) && $shop = $shop[0]) {
            return new MergeShopVO($shop);
        }
        throw new DFException(DFException::MERGE_SHOP_NOT_FOUND);
    }

}