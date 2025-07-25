<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\ItemModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\models\QuestMergeModel;
use hiperesp\server\models\QuestModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\vo\CharacterItemVO;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\ClassVO;
use hiperesp\server\vo\QuestVO;
use hiperesp\server\vo\SettingsVO;

class CharacterService extends Service {

    #[Inject] private UserService $userService;
    #[Inject] private ClassModel $classModel;
    #[Inject] private UserModel $userModel;
    #[Inject] private CharacterModel $characterModel;
    #[Inject] private ItemModel $itemModel;
    #[Inject] private CharacterItemModel $characterItemModel;
    #[Inject] private QuestMergeModel $questMergeModel;
    #[Inject] private QuestModel $questModel;
    #[Inject] private LogsModel $logsModel;
    #[Inject] private SettingsVO $settings;

    public function auth(\SimpleXMLElement|array|string $inputOrUserToken, int|string|null $charId = null): CharacterVO {
        if(\is_array($inputOrUserToken)) {
            if(!isset($inputOrUserToken['strToken']) && !isset($inputOrUserToken['intCharID'])) {
                throw new DFException(DFException::BAD_REQUEST);
            }
            $userToken = (string)$inputOrUserToken['strToken'];
            $charId = (int)$inputOrUserToken['intCharID'];
        } else if($inputOrUserToken instanceof \SimpleXMLElement) {
            if(!isset($inputOrUserToken->strToken) && !isset($inputOrUserToken->intCharID)) {
                throw new DFException(DFException::BAD_REQUEST);
            }
            $userToken = (string)$inputOrUserToken->strToken;
            $charId = (int)$inputOrUserToken->intCharID;
        } else {
            $userToken = $inputOrUserToken;
            $charId = (int)$charId;
        }

        $user = $this->userModel->getBySessionToken((string)$userToken);

        try {
            $char = $this->characterModel->getByUserAndId($user, $charId);
        } catch(DFException $e) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'auth', "Character not found.", $user, $user, [
                'charId' => $charId
            ])->asException(DFException::CHARACTER_NOT_FOUND);
        }

        return $char;
    }

    public function delete(CharacterVO $char): void {
        $this->characterModel->delete($char);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'delete', 'Character deleted', $char, $char, []);
    }

    public function trainStats(CharacterVO $char, int $wisdom, int $charisma, int $luck, int $endurance, int $dexterity, int $intelligence, int $strength, int $goldCost): void {
        if($this->settings->revalidateClientValues) {
            if($char->dragonAmulet) {
                $newGoldCost = 0;
            } else {
                $pricePerTrain = 20;
                $newGoldCost = $pricePerTrain * ($wisdom + $charisma + $luck + $endurance + $dexterity + $intelligence + $strength);
            }
            if($newGoldCost != $goldCost) {
                if($this->settings->banInvalidClientValues) {
                    $actionLog = $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'trainStats', "Invalid gold cost for trainStats. Should be {$newGoldCost}.", $char, $char, [
                        'cost' => $goldCost
                    ]);
                    $this->userService->ban($char, 'Invalid gold cost for trainStats.', $actionLog);
                }
            }
            $goldCost = $newGoldCost;
        }

        if($goldCost > $char->gold) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'trainStats', "Not enough gold to train stats.", $char, $char, [
                'gold' => $char->gold,
                'cost' => $goldCost
            ])->asException(DFException::GOLD_NOT_ENOUGH);
        }
        if($goldCost < 0) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'trainStats', "Negative gold cost for train stats.", $char, $char, [
                'cost' => $goldCost
            ])->asException(DFException::BAD_REQUEST);
        }

        $statPointsCost = $wisdom + $charisma + $luck + $endurance + $dexterity + $intelligence + $strength;
        if($statPointsCost > $char->getStatPoints()) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'trainStats', "Not enough stat points to train stats.", $char, $char, [
                'charStatPoints' => $char->getStatPoints(),
                'requiredStatPoints' => $statPointsCost
            ])->asException(DFException::STATS_POINTS_NOT_ENOUGH);
        }

        $this->characterModel->trainStats($char, $wisdom, $charisma, $luck, $endurance, $dexterity, $intelligence, $strength, $goldCost);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'trainStats', 'Stats trained', $char, $char, []);
    }

    public function untrainStats(CharacterVO $char): void {
        if($char->dragonAmulet) {
            $goldCost = 0;
        } else {
            $goldCost = 1000;
        }

        if($char->gold < $goldCost) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'untrainStats', "Not enough gold to untrain stats.", $char, $char, [
                'gold' => $char->gold,
                'cost' => $goldCost
            ])->asException(DFException::GOLD_NOT_ENOUGH);
        }

        $this->characterModel->untrainStats($char, $goldCost);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'untrainStats', 'Stats untrained', $char, $char, []);
    }

	public function buyBankSlots1(CharacterVO $char, int $slots): void {
		if($slots > (10 - $char->bankSlots)) {
			throw new DFException(DFException::BAD_REQUEST);
		}
		
		$cost = 100 * $slots;
		if($char->coins < $cost) {
			throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'bugBankSlots1', "Not enough dragon coins to buy slot(s).", $char, $char, [
                'coins' => $char->coins,
                'cost' => $cost
            ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
		}
		
		$this->characterModel->buyBankSlots($char, $slots, $cost);
	}

	public function buyBankSlots2(CharacterVO $char, int $slots): void {
		if($char->bankSlots < 10 || $slots > (15 - $char->bankSlots)) {
			throw new DFException(DFException::BAD_REQUEST);
		}
		
		$cost = 300 * $slots;
		if($char->coins < $cost) {
			throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'bugBankSlots2', "Not enough dragon coins to buy slot(s).", $char, $char, [
                'coins' => $char->coins,
                'cost' => $cost
            ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
		}
		
		$this->characterModel->buyBankSlots($char, $slots, $cost);
	}

	public function buyBankSlots3(CharacterVO $char, int $slots): void {
		if($char->bankSlots < 15 || $slots > (100 - $char->bankSlots)) {
			throw new DFException(DFException::BAD_REQUEST);
		}
		
		$cost = 500 * $slots;
		if($char->coins < $cost) {
			throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'bugBankSlots3', "Not enough dragon coins to buy slot(s).", $char, $char, [
                'coins' => $char->coins,
                'cost' => $cost
            ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
		}
		
		$this->characterModel->buyBankSlots($char, $slots, $cost);
	}

	public function buyBagSlots1(CharacterVO $char, int $slots): void {
		$defaultBagSlots = 30;
		if($char->dragonAmulet) {
			$defaultBagSlots = 50;
		}
		
		if(($slots + $char->bagSlots) > ($defaultBagSlots + 10)) {
			throw new DFException(DFException::BAD_REQUEST);
		}

		$cost = 100 * $slots;
		if($char->coins < $cost) {
			throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'bugBagSlots1', "Not enough dragon coins to buy slot(s).", $char, $char, [
                'coins' => $char->coins,
                'cost' => $cost
            ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
		}
		
		$this->characterModel->buyBagSlots($char, $slots, $cost);
	}

	public function buyBagSlots2(CharacterVO $char, int $slots): void {
		$defaultBagSlots = 30;
		if($char->dragonAmulet) {
			$defaultBagSlots = 50;
		}
		
		if(($slots + $char->bagSlots) > ($defaultBagSlots + 15)) {
			throw new DFException(DFException::BAD_REQUEST);
		}

		$cost = 300 * $slots;
		if($char->coins < $cost) {
			throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'bugBagSlots1', "Not enough dragon coins to buy slot(s).", $char, $char, [
                'coins' => $char->coins,
                'cost' => $cost
            ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
		}
		
		$this->characterModel->buyBagSlots($char, $slots, $cost);
	}

	public function buyBagSlots3(CharacterVO $char, int $slots): void {
		$defaultBagSlots = 30;
		if($char->dragonAmulet) {
			$defaultBagSlots = 50;
		}
		
		if(($slots + $char->bagSlots) > ($defaultBagSlots + 180)) {
			throw new DFException(DFException::BAD_REQUEST);
		}

		$cost = 500 * $slots;
		if($char->coins < $cost) {
			throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'bugBagSlots1', "Not enough dragon coins to buy slot(s).", $char, $char, [
                'coins' => $char->coins,
                'cost' => $cost
            ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
		}
		
		$this->characterModel->buyBagSlots($char, $slots, $cost);
	}

	public function subtractGold(CharacterVO $char, int $goldCost): void {
        if($goldCost > $char->gold) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'subtractGold', "Not enough gold.", $char, $char, [
                'gold' => $char->gold,
                'cost' => $goldCost
            ])->asException(DFException::GOLD_NOT_ENOUGH);
        }
        if($goldCost < 0) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'subtractGold', "Negative gold cost.", $char, $char, [
                'cost' => $goldCost
            ])->asException(DFException::BAD_REQUEST);
        }

        $this->characterModel->chargeGold($char, $goldCost);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'subtractGold', 'Gold subtracted', $char, $char, []);
    }

    public function changeClass(CharacterVO $char, int $newClassId): ClassVO {
        try {
            $newClass = $this->classModel->getById($newClassId);
        } catch(DFException $e) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'changeClass', 'Invalid class', $char, $char, [])->asException($e->getDFCode());
        }

        $this->characterModel->changeClass($char, $newClass);

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'changeClass', 'Class changed', $char, $char, [
            'newClassId' => $newClassId
        ]);

        return $newClass;
    }

    public function loadClass(CharacterVO $char, int $newClassId): ClassVO {
        try {
            $class = $this->classModel->getById($newClassId);
        } catch(DFException $e) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'loadClass', 'Invalid class', $char, $char, [])->asException($e->getDFCode());
        }

        $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'loadClass', 'Class loaded', $char, $char, [
            'newClassId' => $newClassId
        ]);

        return $class;
    }

    public function applyExpSave(CharacterVO $char, int $questId, int $experience, int $gems, int $gold, int $silver): CharacterVO {
        $quest = $this->questModel->getById($questId);

        $this->characterModel->applyExpSave($char, $quest, [
            'experience' => $experience,
            'gems'       => $gems,
            'gold'       => $gold,
            'silver'     => $silver
        ]);

        return $this->characterModel->refresh($char);
    }

    public function applyQuestRewards(CharacterVO $char, QuestVO $quest, array $rewards): CharacterVO {
        $this->characterModel->applyQuestRewards($char, $quest, $rewards);
        return $this->characterModel->refresh($char);
    }

    public function applyQuestItemRewards(CharacterVO $char, int $newItemID): CharacterItemVO {
        $item = $this->itemModel->getById($newItemID);
        $charItem = $this->characterItemModel->addItemToChar($char, $item);
        return $charItem;
    }
	
	public function mergeQuest(CharacterVO $char, int $mergeId): array {
		$questMerge = $this->questMergeModel->getById($mergeId);
		
		switch($questMerge['stringType']) {
			case 0:
				$this->setQuestString($char, $questMerge['stringIndex'], $questMerge['stringValue']);
			break;
			case 1:
				$this->setSkillString($char, $questMerge['stringIndex'], $questMerge['stringValue']);
			break;
			case 2:
				$this->setArmorString($char, $questMerge['stringIndex'], $questMerge['stringValue']);
			break;
		}

		$item = $this->itemModel->getById($questMerge['itemId']);
		$charItem = $this->characterItemModel->removeItemFromChar($char, $item, $questMerge['itemQty']);
		$questMerge['charItemId'] = $charItem->id;
		return $questMerge;
	}

    public function setQuestString(CharacterVO $char, int $index, int $value): void {
        $this->characterModel->setQuestString($char, $index, $value);
    }

    public function setSkillString(CharacterVO $char, int $index, int $value): void {
        $this->characterModel->setSkillString($char, $index, $value);
    }
	
	public function setArmorString(CharacterVO $char, int $index, int $value): void {
        $this->characterModel->setArmorString($char, $index, $value);
    }
	
	public function changeArmorColor(CharacterVO $char, int $colorTrim, int $colorBase): void {
		if(!$char->dragonAmulet) {
			throw new DFException(DFException::BAD_REQUEST);
		}
		if($char->gold < 100) {
			throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'subtractGold', "Not enough gold.", $char, $char, [
                'gold' => $char->gold,
                'cost' => $goldCost
            ])->asException(DFException::GOLD_NOT_ENOUGH);
		}
		$this->characterModel->changeArmorColor($char, $colorTrim, $colorBase);
	}

}