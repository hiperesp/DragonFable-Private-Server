<?php
namespace hiperesp\server\vo;

abstract class ValueObject {

    public function __construct(array $data) {
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if(!$property->isReadOnly()) throw new \Exception("Property {$propertyName} is not read-only");
            if(!array_key_exists($propertyName, $data)) throw new \Exception("Property {$propertyName} not found in data");

            // get the type of the property
            $propertyType = $property->getType();
            if($propertyType == null) throw new \Exception("Property {$propertyName} has no type");

            if($propertyType->getName() == 'int') {
                $this->$propertyName = \intval($data[$propertyName]);
            } else if($propertyType->getName() == 'string') {
                $this->$propertyName = \strval($data[$propertyName]);
            } else if($propertyType->getName() == 'float') {
                $this->$propertyName = \floatval($data[$propertyName]);
            } else if($propertyType->getName() == 'bool') {
                $this->$propertyName = \boolval($data[$propertyName]);
            } else {
                throw new \Exception("Property {$propertyName} has an invalid type");
            }
        }
    }

}