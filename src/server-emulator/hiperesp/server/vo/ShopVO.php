<?php
namespace hiperesp\server\vo;

use hiperesp\server\models\ItemModel;

class ShopVO extends ValueObject {

    public readonly int $id;
    public readonly string $name;
    public readonly int $count;

}