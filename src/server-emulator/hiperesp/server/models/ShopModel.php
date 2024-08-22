<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\ShopVO;

class ShopModel extends Model {

    const COLLECTION = 'shop';

    public function getById(int $shopId): ShopVO {
        $shop = $this->storage->select(self::COLLECTION, ['id' => $shopId]);
        if(isset($shop[0]) && $shop = $shop[0]) {
            return new ShopVO($shop);
        }
        throw new DFException(DFException::SHOP_NOT_FOUND);
    }

}