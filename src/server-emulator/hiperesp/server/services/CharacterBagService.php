<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\vo\CharacterVO;

class CharacterBagService extends Service {

    #[Inject] private CharacterItemModel $characterItemModel;
    #[Inject] private LogsModel $logsModel;

    public function destroyItem(CharacterVO $char, int $itemId): void {

        try {
            $charItem = $this->characterItemModel->getByCharAndId($char, $itemId);
        } catch (DFException $e) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'destroyItem', 'CharacterItem not found', $char, $char, [
                'itemId' => $itemId
            ])->asException($e->getDFCode());
        }

        if(!$charItem->getItem()->destroyable) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'destroyItem', 'CharacterItem not destroyable', $char, $charItem, [
                'itemId' => $itemId
            ])->asException(DFException::ITEM_NOT_DESTROYABLE);
        }

        $this->characterItemModel->destroy($charItem);
        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'destroyItem', 'CharacterItem destroyed', $char, $charItem, []);

    }

}