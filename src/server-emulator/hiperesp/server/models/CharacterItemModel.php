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

    public function addItemToChar(CharacterVO $char, ItemVO $item): CharacterItemVO {
        if($item->maxStackSize > 1) {
            $charItems = $this->storage->select(self::COLLECTION, ['charId' => $char->id, 'itemId' => $item->id], null);
            foreach($charItems as $charItem) {
                if($charItem['count'] < $item->maxStackSize) {
                    $charItem['count']++;
                    $this->storage->update(self::COLLECTION, $charItem);
                    return new CharacterItemVO($charItem);
                }
            }
            throw new DFException(DFException::CHARACTER_ITEM_MAX_STACK_SIZE);
        }

        $data = [];
        $data['charId'] = $char->id;
        $data['itemId'] = $item->id;
        $data['count'] = 1;
        $newData = $this->storage->insert(self::COLLECTION, $data);
        return new CharacterItemVO($newData);
    }

    public function removeItemFromChar(CharacterVO $char, ItemVO $item, int $amount): void {
        $charItem = $this->storage->select(self::COLLECTION, ['charId' => $char->id, 'itemId' => $item->id]);
        if(!isset($charItem[0]) || !$charItem = $charItem[0]) {
            throw new DFException(DFException::CHARACTER_ITEM_NOT_FOUND);
        }

        if($charItem['count'] < $amount) {
            throw new DFException(DFException::ITEM_NOT_ENOUGH);
        }

        $charItem['count'] -= $amount;
        if($charItem['count'] > 0) {
            $this->storage->update(self::COLLECTION, $charItem);
        } else {
            $this->storage->delete(self::COLLECTION, ['id' => $charItem['id']]);
        }
    }

    public function getByCharAndId(CharacterVO $char, int $id): CharacterItemVO {
        $charItem = $this->storage->select(self::COLLECTION, ['charId' => $char->id, 'id' => $id]);
        if(isset($charItem[0]) && $charItem = $charItem[0]) {
            return new CharacterItemVO($charItem);
        }
        throw new DFException(DFException::CHARACTER_ITEM_NOT_FOUND);
    }

    public function getByCharAndItemId(CharacterVO $char, int $itemId): CharacterItemVO {
        $charItem = $this->storage->select(self::COLLECTION, ['charId' => $char->id, 'itemId' => $itemId]);
        if(isset($charItem[0]) && $charItem = $charItem[0]) {
            return new CharacterItemVO($charItem);
        }
        throw new DFException(DFException::CHARACTER_ITEM_NOT_FOUND);
    }

    public function destroy(CharacterItemVO $charItem): void {
        $this->storage->delete(self::COLLECTION, ['id' => $charItem->id]);
    }

}