<?php
namespace hiperesp\server\projection;

use hiperesp\server\util\AutoInstantiate;

abstract class Projection {

    final public function __construct() {
        $autoInstantiate = new AutoInstantiate($this);
        $autoInstantiate->models();
        $autoInstantiate->settings();
    }

    public final static function instance(): static {
        return new static();
    }

}