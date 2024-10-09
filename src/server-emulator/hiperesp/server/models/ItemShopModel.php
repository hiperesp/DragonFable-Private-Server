<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\ItemShopVO;

class ItemShopModel extends Model {

    const COLLECTION = 'itemShop';

    public function getById(int $shopId): ItemShopVO {
        $shop = $this->storage->select(self::COLLECTION, ['id' => $shopId]);
        if(isset($shop[0]) && $shop = $shop[0]) {
            return new ItemShopVO($shop);
        }
        throw new DFException(DFException::SHOP_NOT_FOUND);
    }

}