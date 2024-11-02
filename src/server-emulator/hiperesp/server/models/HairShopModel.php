<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\HairShopVO;

class HairShopModel extends Model {

    const COLLECTION = 'hairShop';

    public function getById(int $shopId): HairShopVO {
        $shop = $this->storage->select(self::COLLECTION, ['id' => $shopId]);
        if(isset($shop[0]) && $shop = $shop[0]) {
            return new HairShopVO($shop);
        }
        throw new DFException(DFException::SHOP_NOT_FOUND);
    }

}