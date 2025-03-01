<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DBConfigException;
use hiperesp\server\exceptions\SettingsNotFoundException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\SettingsModel;
use hiperesp\server\storage\Storage;
use hiperesp\server\vo\SettingsVO;

class WebStatsService extends Service {

    # We are not using #[Inject] here because we want this to work without the correct table structure,
    # to show the user that the server is not ready yet if the setup is not done.

    public function version(): array {
        $settings = $this->getSettings();
        return [
            "gamemovie"     => $settings?->gameSwf,
            "signUpMessage" => $settings?->signUpMessage,
            "server"        => $settings?->serverLocation,
            "gamefilesPath" => $settings?->gamefilesPath,
            "gameVersion"   => $settings?->serverVersion,
            "online"        => $this->getStatus()['online'] ? "true" : "false",
            "end"           => "here",
        ];
    }

    public function stats(): array {
        global $config;
        $settings = $this->getSettings();

        try {
            $onlineUsers = (new CharacterModel())->getOnlineCount();
        } catch(\Exception) {
            $onlineUsers = 0;
        }
        return [
            'onlineUsers' => $onlineUsers,
            'status' => $this->getStatus(),
            'serverTime' => \date('c'),
            'serverVersion' => $settings?->serverVersion,
            'gitRev' => $config['GIT_REV'] ?: null,
        ];
    }

    private function getSettings(): SettingsVO|null {
        try {
            return (new SettingsModel)->getSettings();
        } catch(DBConfigException $e) {
            // config is not ready yet
            return null;
        } catch(SettingsNotFoundException $e) {
            // tables are not ready yet
            return null;
        }
    }

    private function getStatus(): array {
        try {
            $storage = Storage::getStorage();
        } catch(DBConfigException $e) {
            return [
                'online' => false,
                'text' => 'Setup Needed',
                'color' => 'hsl(0deg, 60%, 50%)',
                'special' => 'SETUP',
            ];
        }

        if($storage->canSetup()) {
            return [
                'online' => false,
                'text' => 'Upgrade Needed',
                'color' => 'hsl(0deg, 60%, 50%)',
                'special' => 'UPGRADE',
            ];
        }

        $setupStatusTxt = $storage->getSetupStatus();
        if($setupStatusTxt === 'UPGRADING') {
            return [
                'online' => false,
                'text' => 'Upgrading',
                'color' => 'hsl(60deg, 60%, 50%)',
                'special' => 'MAINTENANCE',
            ];
        }
        if($setupStatusTxt === 'DONE') {
            return [
                'online' => true,
                'text' => 'Online',
                'color' => 'hsl(110deg, 60%, 50%)',
                'special' => 'DONE',
            ];
        }
        if(!$setupStatusTxt) {
            $setupStatusTxt = 'Empty';
        }
        return [
            'online' => false,
            'text' => "Unknown ({$setupStatusTxt})",
            'color' => 'hsl(200deg, 60%, 50%)',
            'special' => 'ERROR',
        ];
    }

    public function eventSource(): callable {
        $lastInfo = null;
        $firstUpdated = false;

        return function() use(&$firstUpdated, &$lastInfo): array {
            if($firstUpdated) {
                // update every 1s
                \usleep(1_000_000);
            } else {
                $firstUpdated = true;
            }

            \clearstatcache();

            $newInfo = $this->stats();
            $newInfoCompare = $newInfo;
            unset($newInfoCompare['serverTime']);

            if($newInfoCompare === $lastInfo) {
                return [
                    "event" => "update"
                ];
            }
            $lastInfo = $newInfoCompare;

            return [
                "event" => "message",
                "data" => \json_encode($newInfo)
            ];
        };
    }
}