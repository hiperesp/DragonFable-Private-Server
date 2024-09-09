<?php
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ItemVO;

class ItemShopService extends Service {

    private CharacterModel $characterModel;
    private CharacterItemModel $characterItemModel;

    public function buy(CharacterVO $char, ItemVO $item): CharacterItemVO {
        if(!$char->canBuyItem($item)) throw new DFException(DFException::CANNOT_BUY_ITEM);

        $charItem = $this->characterItemModel->addItemToChar($char, $item);
        $this->characterModel->chargeItem($charItem);

        return $charItem;
    }

    public function sell(CharacterItemVO $charItem, int $quantity, int $returnPercent): void {
        $char = $charItem->getChar();

        $this->characterModel->refundItem($char, $charItem,
            quantity: $quantity,
            returnPercent: $returnPercent
        );

        $this->characterItemModel->destroy($charItem);
    }

}