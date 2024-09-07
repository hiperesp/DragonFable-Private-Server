<?php
namespace hiperesp\server\util;

use hiperesp\server\models\SettingsModel;
use hiperesp\server\storage\Storage;
use hiperesp\server\vo\SettingsVO;

class AutoInstantiate {

    const MODEL = \hiperesp\server\models\Model::class;

    private object $instance;
    private \ReflectionClass $rClass;

    public function __construct(object $instance) {
        $this->instance = $instance;
        $this->rClass = new \ReflectionClass($instance);
    }

    public function models(): void {
        $this->subclass(self::MODEL);
    }
    public function settings(): void {
        if($this->instance instanceof SettingsModel) {
            return; // avoid infinite loop
        }
        foreach($this->rClass->getProperties() as $rProperty) {
            if($rProperty->getType()->getName() === SettingsVO::class) {
                $settingsModel = new SettingsModel(self::getStorage());
                $settings = $settingsModel->getSettings();
                $rProperty->setAccessible(true);
                $rProperty->setValue($this->instance, $settings);
                continue;
            }
        }
    }

    private function subclass(string $subclass): void {
        foreach($this->rClass->getProperties() as $rProperty) {
            $rType = $rProperty->getType();
            if($rType===null) continue;
            if($rType->isBuiltin()) continue;
            $rTypeName = $rType->getName();
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