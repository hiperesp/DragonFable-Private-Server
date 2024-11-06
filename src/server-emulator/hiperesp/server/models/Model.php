<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\attributes\Inject;
use hiperesp\server\storage\Storage;
use hiperesp\server\traits\InjectDependency;

abstract class Model {

    #[Inject] protected Storage $storage;

    use InjectDependency;

    final public function __construct() {
        $this->injectDependencies();
    }

}