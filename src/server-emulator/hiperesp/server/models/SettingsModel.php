<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\SettingsNotFoundException;
use hiperesp\server\vo\SettingsVO;

class SettingsModel extends Model {

    const COLLECTION = 'settings';

    private function getById(int $settingsId): SettingsVO {
        $settings = $this->storage->select(self::COLLECTION, ['id' => $settingsId]);
        if(isset($settings[0]) && $settings = $settings[0]) {
            return new SettingsVO($settings);
        }
        throw new SettingsNotFoundException;
    }

    private static SettingsVO $_settings;
    public function getSettings(): SettingsVO {
        global $config;
        if(!isset(self::$_settings)) {
            self::$_settings = $this->getById((int)$config['DF_SETTINGS_ID']);
        }
        return self::$_settings;
    }

}