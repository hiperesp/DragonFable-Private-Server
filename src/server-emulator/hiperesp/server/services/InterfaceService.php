<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\models\InterfaceModel;
use hiperesp\server\vo\InterfaceVO;

class InterfaceService extends Service {

    private InterfaceModel $interfaceModel;

    public function load(int $interfaceId): InterfaceVO {
        return $this->interfaceModel->getById($interfaceId);
    }
}