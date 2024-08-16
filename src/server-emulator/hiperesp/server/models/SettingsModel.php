<?php
namespace hiperesp\server\models;

use hiperesp\server\vo\SettingsVO;

class SettingsModel extends Model {

    public const COLLECTION = 'settings';

    public function getSettings(): SettingsVO {
        return new SettingsVO();
    }

}