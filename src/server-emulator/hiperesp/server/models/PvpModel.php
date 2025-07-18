<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\interfaces\Purchasable;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class PvpModel extends Model {

    const COLLECTION = 'char';

    #[Inject] private SettingsVO $settings;
	
	public function loadRandom(CharacterVO $char, int $level, int $charId): ?CharacterVO {
		$gap = 1;
		$maxGap = 30;

		$userId = $char->userId;

		while ($gap <= $maxGap) {
			$minLevel = max(1, $level - $gap);
			$maxLevel = $level + $gap;

			$matches = $this->storage->select(self::COLLECTION, [
				'level' => ['BETWEEN' => [$minLevel, $maxLevel]],
				'userId' => ['!=' => $userId] // exclude own characters
			], 1000);

			if (!empty($matches)) {
				$matches = array_values($matches);
				$player = $matches[array_rand($matches)];
				return new CharacterVO($player);
			}

			$gap++;
		}

		return null; // handled by game client - no character found
	}
	
	public function loadChar(int $charId): ?CharacterVO {		
		$player = $this->storage->select(self::COLLECTION, ['id' => $charId]);
		
		if(isset($player[0]) && $player = $player[0]) {
			return new CharacterVO($player);
		}
		
		return null; //handled by the game client - Invalid ID - No character found..
	}

}
