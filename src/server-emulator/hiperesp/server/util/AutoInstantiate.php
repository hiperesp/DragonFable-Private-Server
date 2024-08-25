<?php
namespace hiperesp\server\util;

use hiperesp\server\storage\Storage;

class AutoInstantiate {

    const MODEL = \hiperesp\server\models\Model::class;

    private object $instance;

    public function __construct(object $instance) {
        $this->instance = $instance;
    }

    public function models(): void {
        $this->subclass(self::MODEL);
    }

    private function subclass(string $subclass): void {
        $rClass = new \ReflectionClass($this->instance);
        foreach($rClass->getProperties() as $rProperty) {
            $rType = $rProperty->getType();
            if($rType===null) continue;
            $rTypeName = $rType->getName();
            if(!\class_exists($rTypeName)) continue;
            if(\is_subclass_of($rTypeName, $subclass)) {
                $rProperty->setValue($this->instance, new $rTypeName(self::getStorage()));
            }
        }
    }

    private static Storage $storage;
    private static function getStorage(): Storage {
        if(!isset(self::$storage)) {
            self::$storage = Storage::getStorage();
        }
        return self::$storage;
    }
}