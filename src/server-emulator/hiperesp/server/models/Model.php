<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\storage\Storage;
use hiperesp\server\util\AutoInstantiate;

abstract class Model {

    protected Storage $storage;
    final public function __construct(Storage $storage) {
        $this->storage = $storage;

        $autoInstantiate = new AutoInstantiate($this);
        $autoInstantiate->settings();
        $autoInstantiate->logs();
    }

}