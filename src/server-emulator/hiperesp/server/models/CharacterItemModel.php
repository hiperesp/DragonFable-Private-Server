<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ItemVO;

class CharacterItemModel extends Model {

    const COLLECTION = 'char_item';

    /** @return array<CharacterItemVO> */
    public function getByChar(CharacterVO $char): array {
        $charItems = $this->storage->select(self::COLLECTION, ['charId' => $char->id], null);
        return \array_map(fn($charItem) => new CharacterItemVO($charItem), $charItems);
    }

    public function addItemToChar(CharacterVO $char, ItemVO $item, int $count = 1): CharacterItemVO {
        $data = [];
        $data['charId'] = $char->id;
        $data['itemId'] = $item->id;
        $data['count'] = $count;
        $newData = $this->storage->insert(self::COLLECTION, $data);
        return new CharacterItemVO($newData);
    }

    public function getByCharAndId(CharacterVO $char, int $id): CharacterItemVO {
        $charItem = $this->storage->select(self::COLLECTION, ['charId' => $char->id, 'id' => $id]);
        if(isset($charItem[0]) && $charItem = $charItem[0]) {
            return new CharacterItemVO($charItem);
        }
        throw new DFException(DFException::CHARACTER_ITEM_NOT_FOUND);
    }

    public function destroy(CharacterItemVO $charItem) {
        $this->storage->delete(self::COLLECTION, ['id' => $charItem->id]);
    }

}