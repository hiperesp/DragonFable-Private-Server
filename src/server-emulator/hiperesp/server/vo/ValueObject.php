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

            $value = $data[$propertyName];
            if($propertyType->getName() == 'int') {
                $value = \intval($value);
            } else if($propertyType->getName() == 'string') {
                $value = \strval($value);
            } else if($propertyType->getName() == 'float') {
                $value = \floatval($value);
            } else if($propertyType->getName() == 'bool') {
                $value = \boolval($value);
            } else {
                throw new \Exception("Property {$propertyName} has an invalid type");
            }
            $property->setAccessible(true);
            $property->setValue($this, $value);
        }
    }

}