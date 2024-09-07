<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\vo\SettingsVO;

class ApiController extends Controller {

    private SettingsVO $settings;
    private CharacterModel $characterModel;

    #[Request(
        endpoint: '/api/web-stats.json',
        inputType: Input::NONE,
        outputType: Output::JSON
    )]
    public function webStats(): mixed {

        $onlineCount = $this->characterModel->getOnlineCount();

        return [
            'onlineUsers' => $onlineCount,
            'serverTime' => \date('c'),
            'serverVersion' => $this->settings->serverVersion,
        ];
    }

}