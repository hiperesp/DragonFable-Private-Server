<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class CharacterService extends Service {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private LogsModel $logsModel;

    private SettingsVO $settings;

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
                    $this->userModel->ban($char, 'Invalid gold cost for trainStats.', $actionLog);
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
        if($statPointsCost > $char->statPoints) {
            throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'trainStats', "Not enough stat points to train stats.", $char, $char, [
                'charStatPoints' => $char->statPoints,
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

}