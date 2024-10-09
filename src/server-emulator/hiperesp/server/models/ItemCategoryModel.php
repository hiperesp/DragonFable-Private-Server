<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\ItemCategoryVO;
use hiperesp\server\vo\ItemVO;

class ItemCategoryModel extends Model {

    const COLLECTION = 'itemCategory';

    public function getById(int $categoryId): ItemCategoryVO {
        $category = $this->storage->select(self::COLLECTION, ['id' => $categoryId]);
        if(isset($category[0]) && $category = $category[0]) {
            return new ItemCategoryVO($category);
        }
        throw new DFException(DFException::CATEGORY_NOT_FOUND);
    }

    public function getByItem(ItemVO $item): ItemCategoryVO {
        return $this->getById($item->categoryId);
    }

}