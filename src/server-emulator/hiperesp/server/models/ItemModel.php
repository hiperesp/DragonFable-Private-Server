<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\ItemVO;

class ItemModel extends Model {

    const COLLECTION = 'item';

    public function getById(int $itemId): ItemVO {
        $item = $this->storage->select(self::COLLECTION, ['id' => $itemId]);
        if(isset($item[0]) && $item = $item[0]) {
            return new ItemVO($item);
        }
        throw DFException::fromCode(DFException::ITEM_NOT_FOUND);
    }

}