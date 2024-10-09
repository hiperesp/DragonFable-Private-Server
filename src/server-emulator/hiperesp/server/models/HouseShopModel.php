<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\HouseShopVO;

class HouseShopModel extends Model {

    const COLLECTION = 'houseShop';

    public function getById(int $houseShopId): HouseShopVO {
        $houseShop = $this->storage->select(self::COLLECTION, ['id' => $houseShopId]);
        if(isset($houseShop[0]) && $houseShop = $houseShop[0]) {
            return new HouseShopVO($houseShop);
        }
        throw new DFException(DFException::HOUSE_SHOP_NOT_FOUND);
    }

}