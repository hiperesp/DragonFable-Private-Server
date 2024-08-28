<?php
namespace hiperesp\server\projection;

use hiperesp\server\util\AutoInstantiate;

abstract class Projection {

    public function __construct() {
        $autoInstantiate = new AutoInstantiate($this);
        $autoInstantiate->models();
    }

    public static function instance(): static {
        return new static();
    }

}