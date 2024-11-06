<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\HouseShopModel;
use hiperesp\server\vo\HouseShopVO;

class HouseShopService extends Service {

    #[Inject] private HouseShopModel $houseShopModel;

    public function getShop(int $shopId): HouseShopVO {
        return $this->houseShopModel->getById($shopId);
    }

}