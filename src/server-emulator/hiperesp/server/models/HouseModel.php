<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\HouseVO;
use hiperesp\server\vo\HouseShopVO;

class HouseModel extends Model {

    const COLLECTION = 'house';
    const HOUSE_SHOP_ASSOCIATION = 'houseShop_house';

    public function getById(int $itemId): HouseVO {
        $house = $this->storage->select(self::COLLECTION, ['id' => $itemId]);
        if(isset($house[0]) && $house = $house[0]) {
            return new HouseVO($house);
        }
        throw new DFException(DFException::ITEM_NOT_FOUND);
    }

    public function getByShopAndId(HouseShopVO $shop, int $id): HouseVO {
        $house = $this->storage->select(self::HOUSE_SHOP_ASSOCIATION, ['houseShopId' => $shop->id, 'houseId' => $id]);
        if(isset($house[0]) && $house = $house[0]) {
            return $this->getById($house['houseId']);
        }
        throw new DFException(DFException::ITEM_NOT_FOUND);
    }

    /** @return array<HouseVO> */
    public function getByShop(HouseShopVO $shop): array {
        $houseIds = \array_map(function(array $house): int {
            return (int)$house['houseId'];
        }, $this->storage->select(self::HOUSE_SHOP_ASSOCIATION, ['houseShopId' => $shop->id], null));

        return \array_map(function(array $house): HouseVO {
            return new HouseVO($house);
        }, $this->storage->select(self::COLLECTION, ['id' => $houseIds], null));
    }

}