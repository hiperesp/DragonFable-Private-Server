<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\SettingsVO;

class SettingsModel extends Model {

    const COLLECTION = 'settings';

    private function getById(int $settingsId): SettingsVO {
        $settings = $this->storage->select(self::COLLECTION, ['id' => $settingsId]);
        if(isset($settings[0]) && $settings = $settings[0]) {
            return new SettingsVO($settings);
        }
        throw new DFException(DFException::SETTINGS_NOT_FOUND);
    }

    private static SettingsVO $_settings;
    public function getSettings(): SettingsVO {
        if(!isset(self::$_settings)) {
            self::$_settings = $this->getById((int)\getenv("DF_SETTINGS_ID"));
        }
        return self::$_settings;
    }

}