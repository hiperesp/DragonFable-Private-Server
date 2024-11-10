<?php declare(strict_types=1);
namespace hiperesp\server\traits;

use hiperesp\server\models\SettingsModel;
use hiperesp\server\storage\Storage;

trait InjectDependency {

    public function injectDependencies(): void {
        $class = new \ReflectionClass($this);
        do {
            $properties = $class->getProperties();
            foreach ($properties as $property) {
                $attributes = $property->getAttributes(\hiperesp\server\attributes\Inject::class);
                if(!$attributes) continue;

                $type = $property->getType()->getName();

                $value = match($type) {
                    \hiperesp\server\storage\Storage::class => Storage::getStorage(),
                    \hiperesp\server\vo\SettingsVO::class => (new SettingsModel)->getSettings(),
                    default => new $type
                };

                $property->setAccessible(true);
                $property->setValue($this, $value);
            }
        } while($class = $class->getParentClass());
    }

}