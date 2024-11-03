<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ItemModel;
use hiperesp\server\models\MergeShopModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\MergeShopVO;
use hiperesp\server\vo\SettingsVO;

class MergeShopService extends Service {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private CharacterItemModel $characterItemModel;
    private MergeShopModel $mergeShopModel;
    private ItemModel $itemModel;
    private LogsModel $logsModel;

    private SettingsVO $settings;

    public function getShop(int $shopId): MergeShopVO {
        return $this->mergeShopModel->getById($shopId);
    }

    public function buy(CharacterVO $char, int $shopId, int $itemId): CharacterItemVO {
        throw new \Exception('Not implemented');
    }

    public function sell(CharacterVO $char, int $charItemId, int $quantity, int $returnPercent): void {
        throw new \Exception('Not implemented');
    }

}