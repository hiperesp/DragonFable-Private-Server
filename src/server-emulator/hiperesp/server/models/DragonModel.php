<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\interfaces\Purchasable;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\ItemModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class DragonModel extends Model {

    const COLLECTION = 'char_dragon';
	
	#[Inject] private SettingsVO $settings;
	#[Inject] private CharacterItemModel $characterItemModel;
	#[Inject] private ItemModel $itemModel;
	
	public function getById(int $charId): array {
        $dragon = $this->storage->select(self::COLLECTION, ['charId' => $charId]);
        if(isset($dragon[0]) && $dragon = $dragon[0]) {
            return $dragon;
        }
        return [];
    }

    public function getByChar(CharacterVO $char): array {
        return $this->getById($char->id);
    }

	public function hatchDragon(CharacterVO $char): array {
        $dragonData = [
			'charId' => (int) $char->id
		];
		$dragon = $this->storage->insert(self::COLLECTION, $dragonData);
		return $dragon;
    }

	public function feedDragon(CharacterVO $char, int $foodId): array {
        $statPoints = 1;
		switch($foodId)
		{
			case 879:
				$statPoints = 1;
			break;
			case 880:
				$statPoints = 2;
			break;
			case 907:
				$statPoints = 5;
			break;
			case 3456:
				$statPoints = 5;
			break;
			case 15360:
				$statPoints = 10;
			break;
			case 15361:
				$statPoints = 20;
			break;
		}
		$dragon = $this->getByChar($char);
		
		$lastFed = new \DateTime($dragon['lastFed']);
		$now = new \DateTime();

		$interval = $now->diff($lastFed);

		if ($interval->days >= 1) {
			$dragon['totalStats'] = min(600, $dragon['totalStats'] + $statPoints);
			$dragon['lastFed'] = date('Y-m-d H:i:s');
			
			$item1 = $this->itemModel->getById($foodId);
			$this->characterItemModel->removeItemFromChar($char, $item1, 1);
			
			$this->storage->update(self::COLLECTION, $dragon);
			return $dragon;
		}
		else{
			throw new DFException(DFException::BAD_REQUEST);
		}
    }
	
	public function trainDragon(CharacterVO $char, int $debuff, int $buff, int $melee, int $magic, int $heal): array {
		$dragon = $this->getByChar($char);
		$dragon['debuff'] = min(200, $dragon['debuff'] + $debuff);
		$dragon['buff'] = min(200, $dragon['buff'] + $buff);
		$dragon['melee'] = min(200, $dragon['melee'] + $melee);
		$dragon['magic'] = min(200, $dragon['magic'] + $magic);
		$dragon['heal'] = min(200, $dragon['heal'] + $heal);
		$this->storage->update(self::COLLECTION, $dragon);
		
		$dragon = $this->getByChar($char);

		$newDebuff = min(200, $dragon['debuff'] + $debuff);
		$newBuff   = min(200, $dragon['buff'] + $buff);
		$newMelee  = min(200, $dragon['melee'] + $melee);
		$newMagic  = min(200, $dragon['magic'] + $magic);
		$newHeal   = min(200, $dragon['heal'] + $heal);

		$total = $newDebuff + $newBuff + $newMelee + $newMagic + $newHeal;

		if ($total > 600) {
			throw new DFException(DFException::BAD_REQUEST);
		}

		$dragon['debuff'] = $newDebuff;
		$dragon['buff']   = $newBuff;
		$dragon['melee']  = $newMelee;
		$dragon['magic']  = $newMagic;
		$dragon['heal']   = $newHeal;

		$this->storage->update(self::COLLECTION, $dragon);		
		
		return $dragon;
    }
	
	public function untrainDragon(CharacterVO $char): void {
		$dragon = $this->getByChar($char);
		$dragon['debuff'] = 0;
		$dragon['buff'] = 0;
		$dragon['melee'] = 0;
		$dragon['magic'] = 0;
		$dragon['heal'] = 0;
		$this->storage->update(self::COLLECTION, $dragon);
    }
	
	public function dragonElement(CharacterVO $char, int $element): array {
		$dragon = $this->getByChar($char);
		switch($element)
		{
			case 5:
				$dragon['element'] = "Fire";
				$dragon['colorDElement'] = 16292121;
			break;
			case 6:
				$dragon['element'] = "Water";
				$dragon['colorDElement'] = 4149907;
			break;
			case 7:
				$dragon['element'] = "Ice";
				$dragon['colorDElement'] = 8383210;
			break;
			case 8:
				$dragon['element'] = "Wind";
				$dragon['colorDElement'] = 8815491;
			break;
			case 9:
				$dragon['element'] = "Energy";
				$dragon['colorDElement'] = 9802309;
			break;
			case 10:
				$dragon['element'] = "Light";
				$dragon['colorDElement'] = 16292121;
			break;
			case 11:
				$dragon['element'] = "Darkness";
				$dragon['colorDElement'] = 4802119;
			break;
			case 18:
				$dragon['element'] = "Nature";
				$dragon['colorDElement'] = 6736947;
			break;
			default:
				$dragon['element'] = "Fire";
				$dragon['colorDElement'] = 16292121;
			break;
		}
		$this->storage->update(self::COLLECTION, $dragon);
		return $dragon;
    }
	
	public function growDragon(CharacterVO $char): array {
		$dragon = $this->getByChar($char);
		$skillString = $char->skills;
		$questString = $char->quests;
		if ($dragon['growthLevel'] >= 2)
		{
			throw new DFException(DFException::BAD_REQUEST);
		}
		else if ($dragon['growthLevel'] == 0 && $skillString[11] < \strtoupper(\dechex(1)))
		{
			throw new DFException(DFException::BAD_REQUEST);
		}
		else if (($questString[43] <= \strtoupper(\dechex(19)) || $questString[33] <= \strtoupper(\dechex(13)) || $questString[40] <= \strtoupper(\dechex(12))) && $dragon['growthLevel'] == 1)
		{
			throw new DFException(DFException::BAD_REQUEST);
		}
		else
		{
			$dragon['totalStats'] = min(600, $dragon['totalStats'] + 100);
			$dragon['growthLevel'] = min(2, $dragon['growthLevel'] + 1);
			$this->storage->update(self::COLLECTION, $dragon);
			return $dragon;
		}
    }

}
