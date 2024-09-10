<?php
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ItemVO;

class ItemShopService extends Service {

    private CharacterModel $characterModel;
    private CharacterItemModel $characterItemModel;
    private LogsModel $logsModel;

    public function buy(CharacterVO $char, ItemVO $item): CharacterItemVO {
        if(!$char->canBuyItem($item)) {
            $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyItem', 'Cannot buy item', $char, $item, []);
            throw new DFException(DFException::CANNOT_BUY_ITEM);
        }

        $charItem = $this->characterItemModel->addItemToChar($char, $item);
        $this->characterModel->chargeItem($charItem);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'buyItem', 'Item bought', $char, $charItem, []);
        return $charItem;
    }

    public function sell(CharacterItemVO $charItem, int $quantity, int $returnPercent): void {
        $char = $charItem->getChar();

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