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
		
		$allChars = $this->storage->select(self::COLLECTION, [], 1000000);

		while ($gap <= $maxGap) {
			$minLevel = max(1, $level - $gap);
			$maxLevel = $level + $gap;
			
			$matches = array_filter($allChars, function ($player) use ($minLevel, $maxLevel, $userId) {
				return isset($player['level'], $player['id']) && $player['level'] >= $minLevel && $player['level'] <= $maxLevel && $player['userId'] !== $userId; //your own characters are excluded
			});

			if (!empty($matches)) {
				$matches = array_values($matches);
				$player = $matches[array_rand($matches)];
				return new CharacterVO($player);
			}

			$gap++;
		}
		
		return null; //handled by the game client - Invalid ID - No character found..
	}
	
	public function loadChar(int $charId): ?CharacterVO {		
		$player = $this->storage->select(self::COLLECTION, ['id' => $charId]);
		
		if(isset($player[0]) && $player = $player[0]) {
			return new CharacterVO($player);
		}
		
		return null; //handled by the game client - Invalid ID - No character found..
	}

}
