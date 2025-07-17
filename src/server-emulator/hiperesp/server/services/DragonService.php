<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\DragonModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class DragonService extends Service {

    #[Inject] private DragonModel $dragonModel;
    #[Inject] private LogsModel $logsModel;
	#[Inject] private SettingsVO $settings;


	public function hatchDragon(CharacterVO $char): array {
		return $this->dragonModel->hatchDragon($char);
    }
	
	public function feedDragon(CharacterVO $char, int $foodId): array {
		return $this->dragonModel->feedDragon($char, $foodId);
    }
	
	public function trainDragon(CharacterVO $char, int $debuff, int $buff, int $melee, int $magic, int $heal): array {
		return $this->dragonModel->trainDragon($char, $debuff, $buff, $melee, $magic, $heal);
    }
	
	public function untrainDragon(CharacterVO $char): void {
		$this->dragonModel->untrainDragon($char);
    }
	
	public function dragonElement(CharacterVO $char, int $element): array {
		return $this->dragonModel->dragonElement($char, $element);
    }
	
	public function growDragon(CharacterVO $char): array {
		return $this->dragonModel->growDragon($char);
    }

}