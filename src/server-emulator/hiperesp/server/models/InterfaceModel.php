<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\InterfaceVO;

class InterfaceModel extends Model {

    const COLLECTION = 'interface';

    public function getById(int $interfaceId): InterfaceVO {
        $interface = $this->storage->select(self::COLLECTION, ['id' => $interfaceId]);
        if(isset($interface[0]) && $interface = $interface[0]) {
            return new InterfaceVO($interface);
        }
        throw DFException::fromCode(DFException::INTERFACE_NOT_FOUND);
    }

}