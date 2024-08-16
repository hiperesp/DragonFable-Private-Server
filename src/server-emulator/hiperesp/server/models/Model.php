<?php
namespace hiperesp\server\models;

use hiperesp\server\storage\Storage;

abstract class Model {

    protected Storage $storage;
    public function __construct(Storage $storage) {
        $this->storage = $storage;
    }

}