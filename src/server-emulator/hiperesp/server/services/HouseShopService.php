<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\models\HouseShopModel;
use hiperesp\server\vo\HouseShopVO;

class HouseShopService extends Service {

    private HouseShopModel $houseShopModel;

    public function getShop(int $shopId): HouseShopVO {
        return $this->houseShopModel->getById($shopId);
    }

}