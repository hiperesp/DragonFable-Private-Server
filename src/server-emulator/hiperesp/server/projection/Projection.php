<?php declare(strict_types=1);
namespace hiperesp\server\projection;

use hiperesp\server\traits\InjectDependency;

abstract class Projection {

    use InjectDependency;

    final public function __construct() {
        $this->injectDependencies();
    }

    public final static function instance(): static {
        return new static();
    }

}