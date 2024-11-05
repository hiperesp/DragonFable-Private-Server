<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\MergeShopModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\models\MergeModel;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\MergeShopVO;
use hiperesp\server\vo\MergeVO;

class MergeShopService extends Service {

    private CharacterItemModel $characterItemModel;
    private MergeModel $mergeModel;
    private MergeShopModel $mergeShopModel;
    private LogsModel $logsModel;

    public function getShop(int $shopId): MergeShopVO {
        return $this->mergeShopModel->getById($shopId);
    }

    public function getMerge(int $mergeId): MergeVO {
        return $this->mergeModel->getById($mergeId);
    }

    /** @return array<CharacterItemVO> */
    public function merge(CharacterVO $char, MergeVO $merge): array {
        if(!$char->canMerge($merge)) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'mergeItem', 'Cannot merge item', $char, $merge, [])->asException(DFException::CANNOT_MERGE_ITEM);
        }

        $removedItem1 = null;
        if($item1 = $merge->getItem1()) {
            $removedItem1 = $this->characterItemModel->removeItemFromChar($char, $item1, $merge->amount1);
        }

        $removedItem2 = null;
        if($item2 = $merge->getItem2()) {
            $removedItem2 = $this->characterItemModel->removeItemFromChar($char, $item2, $merge->amount2);
        }

        $newCharItem = $this->characterItemModel->addItemToChar($char, $merge->getItem());

        return [
            'newCharItem'  => $newCharItem,
            'removedItem1' => $removedItem1,
            'removedItem2' => $removedItem2
        ];
    }

}