<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\DragonModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class DragonService extends Service {

    #[Inject] private CharacterModel $characterModel;
    #[Inject] private DragonModel $dragonModel;
    #[Inject] private LogsModel $logsModel;
	#[Inject] private SettingsVO $settings;


	public function hatchDragon(CharacterVO $char): array {
		$this->characterModel->addDragon($char);
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

	public function customizeDragon(CharacterVO $char, int $tailId, int $headId, int $wingId, int $colorHorn, int $colorEye, int $colorWing, int $colorSkin, $name): array {
		return $this->dragonModel->customizeDragon($char, $tailId, $headId, $wingId, $colorHorn, $colorEye, $colorWing, $colorSkin, $name);
    }

}