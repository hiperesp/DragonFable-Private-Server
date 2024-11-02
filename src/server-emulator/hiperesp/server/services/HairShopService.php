<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\HairModel;
use hiperesp\server\models\HairShopModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\HairShopVO;
use hiperesp\server\vo\HairVO;

class HairShopService extends Service {

    private CharacterModel $characterModel;
    private HairModel $hairModel;
    private HairShopModel $hairShopModel;
    private LogsModel $logsModel;

    public function getShop(int $shopId): HairShopVO {
        return $this->hairShopModel->getById($shopId);
    }

    public function buy(CharacterVO $char, int $hairId, int $hairColor, int $skinColor): HairVO {
        try {
            $hair = $this->hairModel->getById($hairId);
        } catch(\Exception $e) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyHair', 'Invalid hairId', $char, $char, [
                'hairId' => $hairId
            ])->asException(DFException::INVALID_REFERENCE);
        }

        if(!$char->canBuy($hair)) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyHair', 'Cannot buy hair', $char, $hair, [])->asException(DFException::CANNOT_BUY_HAIR);
        }

        $this->characterModel->applyHair($char, $hair, $hairColor, $skinColor);
        $this->characterModel->charge($char, $hair);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'buyHair', 'Hair bought', $char, $hair, []);
        return $hair;
    }

}