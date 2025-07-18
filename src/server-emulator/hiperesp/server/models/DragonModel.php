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
		
		$lastFedDate = (new \DateTime($dragon['lastFed']))->format('Y-m-d');
		$nowDate = (new \DateTime())->format('Y-m-d');

		if ($nowDate !== $lastFedDate) {
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
		else if ($dragon['growthLevel'] == 0 && (int)base_convert($skillString[11], 36, 10) < 1)
		{
			throw new DFException(DFException::BAD_REQUEST);
		}
		else if (((int)base_convert($questString[43], 36, 10) <= 19 || (int)base_convert($questString[33], 36, 10) <= 13 || (int)base_convert($questString[40], 36, 10) <= 12) && $dragon['growthLevel'] == 1)
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
	
	public function formatPart(string $type, int $id): string {
		return sprintf('dragons/%s/%s%02d.swf', $type, rtrim($type, 's'), $id);
	}

	public function customizeDragon(CharacterVO $char, int $tailId, int $headId, int $wingId, int $colorHorn, int $colorEye, int $colorWing, int $colorSkin, $name): array {
		$headOverrides = [
			47 => 'dragons/heads/head47-doom.swf',
			48 => 'dragons/heads/head48-doom.swf',
			49 => 'dragons/heads/head49-kathool.swf',
			50 => 'dragons/heads/head50-mech.swf',
			51 => 'dragons/heads/head51-mech.swf',
			52 => 'dragons/heads/head52-mech.swf',
			53 => 'dragons/heads/head53-sandsea.swf',
			54 => 'dragons/heads/head54-GoatDragon.swf',
			55 => 'dragons/heads/head55-OgretailThing.swf',
			56 => 'dragons/heads/head56-RoundBallMeep.swf',
			57 => 'dragons/heads/head57-guardian.swf',
			58 => 'dragons/heads/head58-corvus.swf',
			59 => 'dragons/heads/head59-stalagbite.swf',
			60 => 'dragons/heads/head60-glaisaurus.swf',
			61 => 'dragons/heads/head61-skweel.swf',
			62 => 'dragons/heads/head62-reigndragon.swf',
			63 => 'dragons/heads/head63-entropy.swf',
			64 => 'dragons/heads/head64-akrilothjr.swf',
			65 => 'dragons/heads/head65-creatioux.swf'
		];

		$wingOverrides = [
			15 => 'dragons/wings/wing15-doom.swf',
			16 => 'dragons/wings/wing16-doom.swf',
			17 => 'dragons/wings/wing17-kathool.swf',
			18 => 'dragons/wings/wing18-mech.swf',
			19 => 'dragons/wings/wing19-mech.swf',
			20 => 'dragons/wings/wing20-mech.swf',
			21 => 'dragons/wings/wing21-sandsea.swf',
			22 => 'dragons/wings/wing22-goatdragon.swf',
			23 => 'dragons/wings/wing23-corvus.swf',
			24 => 'dragons/wings/wing24-glaisaurus.swf',
			25 => 'dragons/wings/wing25-skweel.swf',
			26 => 'dragons/wings/wing26-reigndragon.swf',
			27 => 'dragons/wings/wing27-entropy.swf',
			28 => 'dragons/wings/wing28-akrilothjr.swf',
			29 => 'dragons/wings/wing29-creatioux.swf'
		];

		$tailOverrides = [
			26 => 'dragons/tails/tail26-doom.swf',
			27 => 'dragons/tails/tail27-doom.swf',
			28 => 'dragons/tails/tail28-kathool.swf',
			29 => 'dragons/tails/tail29-mech.swf',
			30 => 'dragons/tails/tail30-mech.swf',
			31 => 'dragons/tails/tail31-mech.swf',
			32 => 'dragons/tails/tail32-sandsea.swf',
			33 => 'dragons/tails/tail33-goatdragon.swf',
			34 => 'dragons/tails/tail34-stalagbite.swf',
			35 => 'dragons/tails/tail35-corvus.swf',
			36 => 'dragons/tails/tail36-skweel.swf',
			37 => 'dragons/tails/tail37-entropy.swf',
			38 => 'dragons/tails/tail38-akrilothjr.swf',
			39 => 'dragons/tails/tail39-creatioux.swf'
		];
		
		$dragon = $this->getByChar($char);
		$dragon['headId'] = $headId;
		$dragon['wingId'] = $wingId;
		$dragon['tailId'] = $tailId;
		$dragon['headFileName'] = $headOverrides[$headId] ?? $this->formatPart('heads', $headId);
		$dragon['wingFileName'] = $wingOverrides[$wingId] ?? $this->formatPart('wings', $wingId);
		$dragon['tailFileName'] = $tailOverrides[$tailId] ?? $this->formatPart('tails', $tailId);
		$dragon['colorDHorn'] = $colorHorn;
		$dragon['colorDEye'] = $colorEye;
		$dragon['colorDWing'] = $colorWing;
		$dragon['colorDSkin'] = $colorSkin;
		$dragon['name'] = $name;
		$this->storage->update(self::COLLECTION, $dragon);		
		
		return $dragon;
	}

}
