<?php
namespace hiperesp\server\services;

use hiperesp\server\util\AutoInstantiate;

abstract class Service {

    public function __construct() {
        $autoInstantiate = new AutoInstantiate($this);
        $autoInstantiate->models();
        $autoInstantiate->settings();
    }
}