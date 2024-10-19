<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ItemModel;
use hiperesp\server\models\ItemShopModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ItemShopVO;
use hiperesp\server\vo\SettingsVO;

class ItemShopService extends Service {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private CharacterItemModel $characterItemModel;
    private ItemShopModel $itemShopModel;
    private ItemModel $itemModel;
    private LogsModel $logsModel;

    private SettingsVO $settings;

    public function getShop(int $shopId): ItemShopVO {
        return $this->itemShopModel->getById($shopId);
    }

    public function buy(CharacterVO $char, int $shopId, int $itemId): CharacterItemVO {
        try {
            $shop = $this->itemShopModel->getById($shopId);
            $item = $this->itemModel->getByShopAndId($shop, $itemId);
        } catch(\Exception $e) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyItem', 'Invalid shopId or itemId', $char, $char, [
                'shopId' => $shopId,
                'itemId' => $itemId
            ])->asException(DFException::INVALID_REFERENCE);
        }

        if(!$char->canBuyItem($item)) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyItem', 'Cannot buy item', $char, $item, [])->asException(DFException::CANNOT_BUY_ITEM);
        }

        $charItem = $this->characterItemModel->addItemToChar($char, $item);
        $this->characterModel->chargeItem($charItem);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'buyItem', 'Item bought', $char, $charItem, []);
        return $charItem;
    }

    public function sell(CharacterVO $char, int $charItemId, int $quantity, int $returnPercent): void {

        $charItem = $this->characterItemModel->getByCharAndId($char, $charItemId);

        if($this->settings->revalidateClientValues) {
            $item = $charItem->getItem();
            if($item->getPriceCoins()) {
                if($charItem->hoursOwned >= 24) {
                    $newReturnPercent = 25;
                } else {
                    $newReturnPercent = 90;
                }
            } else {
                $newReturnPercent = 10;
            }
            if($returnPercent != $newReturnPercent) {
                if($this->settings->banInvalidClientValues) {
                    $actionLog = $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'sellItem', "Invalid returnPercent for charItem. Should be {$newReturnPercent}.", $char, $charItem, [
                        'quantity' => $quantity,
                        'returnPercent' => $returnPercent
                    ]);
                    $this->userModel->ban($char, 'Invalid returnPercent for sellItem.', $actionLog);
                }
            }
            $returnPercent = $newReturnPercent;
        }

        $this->characterModel->refundItem($char, $charItem,
            quantity: $quantity,
            returnPercent: $returnPercent
        );
        $this->characterItemModel->destroy($charItem);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'sellItem', 'Item sold', $char, $charItem, [
            'quantity' => $quantity,
            'returnPercent' => $returnPercent
        ]);
    }

}