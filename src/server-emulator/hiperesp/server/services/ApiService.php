<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\vo\SettingsVO;

class ApiService extends Service {

    #[Inject] private SettingsVO $settings;
    #[Inject] private CharacterModel $characterModel;

    public function getWebStats(): array {
        return [
            'onlineUsers' => $this->characterModel->getOnlineCount(),
            'serverTime' => \date('c'),
            'serverVersion' => $this->settings->serverVersion,
            'gitRev' => \getenv('GIT_REV') ?: null,
        ];
    }
}