<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\PvpModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class PvpService extends Service {
	
	#[Inject] private PvpModel $pvpModel;
    #[Inject] private LogsModel $logsModel;
	#[Inject] private SettingsVO $settings;

    public function loadRandom(CharacterVO $char, int $level, int $charId): ?CharacterVO {
		return $this->pvpModel->loadRandom($char, $level, $charId);
    }
	
	public function loadChar(int $charId): ?CharacterVO {
		return $this->pvpModel->loadChar($charId);
    }

}