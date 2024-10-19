<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\vo\CharacterVO;

class HeromartService extends Service {

    private ClassModel $classModel;
    private CharacterModel $characterModel;
    private LogsModel $logsModel;

    const AFFECT_CHANGE_GENDER  = 0;
    const AFFECT_CHANGE_NAME    = 1;
    const AFFECT_CHANGE_CLASS   = 2;
    const AFFECT_CHANGE_PRONOUN = 3;

    const AFFECT_CHANGE_GENDER_COST  = 1000; // must match with the value in the client
    const AFFECT_CHANGE_NAME_COST    = 1000; // must match with the value in the client
    const AFFECT_CHANGE_CLASS_COST   =  500; // must match with the value in the client
    const AFFECT_CHANGE_PRONOUN_COST =    0; // must match with the value in the client

    public function buyAffect(CharacterVO $char, int $affectId, int $action, string $command): CharacterVO {

        if($affectId == self::AFFECT_CHANGE_GENDER) {
            if($char->coins < self::AFFECT_CHANGE_GENDER_COST) {
                throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyAffect', 'Not enough coins to change gender', $char, $char, [
                    'affectId' => $affectId,
                    'action' => $action,
                    'command' => $command,
                    'coins' => self::AFFECT_CHANGE_GENDER_COST
                ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
            }

            $this->characterModel->chargeCoins($char, self::AFFECT_CHANGE_GENDER_COST);
            $this->characterModel->changeGender($char, $command);

            $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'buyAffect', 'Gender changed', $char, $char, [
                'affectId' => $affectId,
                'action' => $action,
                'command' => $command,
                'coins' => self::AFFECT_CHANGE_GENDER_COST
            ]);

            return $this->characterModel->refresh($char);
        }

        if($affectId == self::AFFECT_CHANGE_NAME) {
            if($char->coins < self::AFFECT_CHANGE_NAME_COST) {
                throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyAffect', 'Not enough coins to change name', $char, $char, [
                    'affectId' => $affectId,
                    'action' => $action,
                    'command' => $command,
                    'coins' => self::AFFECT_CHANGE_NAME_COST
                ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
            }

            $this->characterModel->chargeCoins($char, self::AFFECT_CHANGE_NAME_COST);
            $this->characterModel->changeName($char, $command);

            $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'buyAffect', 'Name changed', $char, $char, [
                'affectId' => $affectId,
                'action' => $action,
                'command' => $command,
                'coins' => self::AFFECT_CHANGE_NAME_COST
            ]);

            return $this->characterModel->refresh($char);
        }

        if($affectId == self::AFFECT_CHANGE_CLASS) {
            if($char->coins < self::AFFECT_CHANGE_CLASS_COST) {
                throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyAffect', 'Not enough coins to change class', $char, $char, [
                    'affectId' => $affectId,
                    'action' => $action,
                    'command' => $command,
                    'coins' => self::AFFECT_CHANGE_CLASS_COST
                ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
            }

            try {
                $class = $this->classModel->getById($action);
            } catch(DFException $e) {
                throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyAffect', 'Invalid class', $char, $char, [
                    'affectId' => $affectId,
                    'action' => $action,
                    'command' => $command
                ])->asException($e->getDFCode());
            }

            $this->characterModel->chargeCoins($char, self::AFFECT_CHANGE_CLASS_COST);
            $this->characterModel->changeClass($char, $class);

            $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'buyAffect', 'Class changed', $char, $char, [
                'affectId' => $affectId,
                'action' => $action,
                'command' => $command,
                'coins' => self::AFFECT_CHANGE_CLASS_COST
            ]);

            return $this->characterModel->refresh($char);
        }

        if($affectId == self::AFFECT_CHANGE_PRONOUN) {
            if($char->coins < self::AFFECT_CHANGE_PRONOUN_COST) {
                throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyAffect', 'Not enough coins to change pronoun', $char, $char, [
                    'affectId' => $affectId,
                    'action' => $action,
                    'command' => $command,
                    'coins' => self::AFFECT_CHANGE_PRONOUN_COST
                ])->asException(DFException::DRAGONCOINS_NOT_ENOUGH);
            }

            $this->characterModel->chargeCoins($char, self::AFFECT_CHANGE_PRONOUN_COST);
            $this->characterModel->changePronoun($char, $command);

            $this->logsModel->register(LogsModel::SEVERITY_ALLOWED, 'buyAffect', 'Pronoun changed', $char, $char, [
                'affectId' => $affectId,
                'action' => $action,
                'command' => $command,
                'coins' => self::AFFECT_CHANGE_PRONOUN_COST
            ]);

            return $this->characterModel->refresh($char);
        }

        throw $this->logsModel->register(LogsModel::SEVERITY_BLOCKED, 'buyAffect', 'Invalid affectId', $char, $char, [
            'affectId' => $affectId,
            'action' => $action,
            'command' => $command
        ])->asException(DFException::BAD_REQUEST);

    }

}