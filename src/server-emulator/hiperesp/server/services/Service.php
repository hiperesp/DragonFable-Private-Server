<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\util\AutoInstantiate;

abstract class Service {

    final public function __construct() {
        $autoInstantiate = new AutoInstantiate($this);
        $autoInstantiate->models();
        $autoInstantiate->settings();
    }
}