<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\traits\InjectDependency;

abstract class ValueObject {

    use InjectDependency;

    final public function __construct(array $data) {
        $this->injectDependencies();

        $data = $this->patch($data);
        $this->applyData($data);
    }

    protected function patch(array $data): array {
        return $data;
    }

    private function applyData(array $data) {
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // get the type of the property
            $propertyType = $property->getType();
            if($propertyType == null) throw new \Exception("Property {$propertyName} has no type");

            if($propertyType->getName() === SettingsVO::class) continue;

            if(!$propertyType->isBuiltin()) {
                if(\is_subclass_of($propertyType->getName(), \hiperesp\server\models\Model::class)) {
                    continue;
                }
            }

            if(!$property->isReadOnly()) throw new \Exception("Property {$propertyName} is not read-only");
            if(!\array_key_exists($propertyName, $data)) throw new \Exception("Property {$propertyName} not found in data");

            $value = $data[$propertyName];
            if($value===null && $propertyType->allowsNull()) {
                $newValue = null;
            } else if($propertyType->getName() == 'int') {
                $newValue = \intval($value);
            } else if($propertyType->getName() == 'string') {
                $newValue = \strval($value);
            } else if($propertyType->getName() == 'float') {
                $newValue = \floatval($value);
            } else if($propertyType->getName() == 'bool') {
                $newValue = \boolval($value);
            } else if($propertyType->getName() == 'array') {
                $newValue = (array)$value;
            } else if($propertyType->getName() == 'object') {
                $newValue = (object)$value;
            } else {
                throw new \Exception("Property {$propertyName} has an invalid type");
            }

            $property->setAccessible(true);
            $property->setValue($this, $newValue);
        }
    }

}