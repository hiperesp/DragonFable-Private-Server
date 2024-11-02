<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\HairVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\HairShopVO;

class HairModel extends Model {

    const COLLECTION = 'hair';
    const HAIR_SHOP_ASSOCIATION = 'hairShop_hair';

    public function getById(int $hairId): HairVO {
        $hair = $this->storage->select(self::COLLECTION, ['id' => $hairId]);
        if(isset($hair[0]) && $hair = $hair[0]) {
            return new HairVO($hair);
        }
        throw new DFException(DFException::HAIR_NOT_FOUND);
    }

    public function getByChar(CharacterVO $char): HairVO {
        return $this->getById($char->hairId);
    }

    /**
     * @return array<HairVO>
     */
    public function getByShop(HairShopVO $shop, string $gender): array {
        $hairIds = \array_map(function(array $item): int {
            return (int)$item['hairId'];
        }, $this->storage->select(self::HAIR_SHOP_ASSOCIATION, ['hairShopId' => $shop->id], null));

        return \array_map(function(array $item): HairVO {
            return new HairVO($item);
        }, $this->storage->select(self::COLLECTION, ['id' => $hairIds, 'gender' => [ HairVO::GENDER_BOTH, $gender ]], null));
    }

}