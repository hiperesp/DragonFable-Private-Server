<?php declare(strict_types=1);
namespace hiperesp\server\util;

use hiperesp\server\models\LogsModel;
use hiperesp\server\models\SettingsModel;
use hiperesp\server\storage\Storage;
use hiperesp\server\vo\SettingsVO;

class AutoInstantiate {

    const MODEL = \hiperesp\server\models\Model::class;
    const SERVICE = \hiperesp\server\services\Service::class;

    private object $instance;
    private \ReflectionClass $rClass;

    public function __construct(object $instance) {
        $this->instance = $instance;
        $this->rClass = new \ReflectionClass($instance);
    }

    public function models(): void {
        $this->subclass(self::MODEL);
    }
    public function services(): void {
        $this->subclass(self::SERVICE);
    }
    public function settings(): void {
        if($this->instance instanceof SettingsModel) {
            return; // avoid infinite loop
        }
        $currentClass = $this->rClass;
        while($currentClass) {
            foreach($currentClass->getProperties() as $rProperty) {
                if($rProperty->getType()->getName() === SettingsVO::class) {
                    $settingsModel = new SettingsModel(self::getStorage());
                    $settings = $settingsModel->getSettings();
                    $rProperty->setAccessible(true);
                    $rProperty->setValue($this->instance, $settings);
                    continue;
                }
            }
            $currentClass = $currentClass->getParentClass();
        }
    }
    public function logs(): void {
        if($this->instance instanceof LogsModel) {
            return; // avoid infinite loop
        }
        $currentClass = $this->rClass;
        while($currentClass) {
            foreach($currentClass->getProperties() as $rProperty) {
                if($rProperty->getType()->getName() === LogsModel::class) {
                    $logsModel = new LogsModel(self::getStorage());
                    $rProperty->setAccessible(true);
                    $rProperty->setValue($this->instance, $logsModel);
                    continue;
                }
            }
            $currentClass = $currentClass->getParentClass();
        }
    }

    private function subclass(string $subclass): void {
        $currentClass = $this->rClass;
        while($currentClass) {
            foreach($currentClass->getProperties() as $rProperty) {
                $rType = $rProperty->getType();
                if($rType===null) continue;
                if($rType->isBuiltin()) continue;
                $rTypeName = $rType->getName();
                if(\is_subclass_of($rTypeName, $subclass)) {
                    $rProperty->setValue($this->instance, new $rTypeName(self::getStorage()));
                }
            }
            $currentClass = $currentClass->getParentClass();
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