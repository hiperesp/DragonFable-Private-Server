<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\traits\InjectDependency;

abstract class Service {

    use InjectDependency;

    final public function __construct() {
        $this->injectDependencies();
    }
}