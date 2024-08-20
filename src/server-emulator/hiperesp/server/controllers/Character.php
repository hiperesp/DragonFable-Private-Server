<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\QuestModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\SettingsModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\models\ArmorModel;
use hiperesp\server\models\WeaponModel;
use hiperesp\server\models\HairModel;

class Character extends Controller {

    private SettingsModel $settingsModel;
    private UserModel $userModel;
    private CharacterModel $characterModel;
    private ClassModel $classModel;
    private RaceModel $raceModel;
    private QuestModel $questModel;
    private ArmorModel $armorModel;
    private WeaponModel $weaponModel;
    private HairModel $hairModel;

    #[Request(
        endpoint: '/cf-characterload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken($input->strToken);
        $character = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);

        return $character->asLoadResponse($user, $this->raceModel, $this->questModel, $this->classModel, $this->armorModel, $this->weaponModel, $this->hairModel);
    }

    #[Request(
        endpoint: '/cf-characternew.asp',
        inputType: Input::FORM,
        outputType: Output::FORM
    )]
    public function new(array $input): array {

        $user = $this->userModel->getBySessionToken($input['strToken']);
        $characterVo = $this->characterModel->create($user, $input); // in case of error, a exception will be thrown

        return $characterVo->asCreatedResponse();
    }

    #[Request(
        endpoint: '/cf-characterdelete.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function delete(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $character = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $this->characterModel->delete($character);

        return $character->asDeleteResponse();
    }

    #[Request(
        endpoint: '/cf-dacheck.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonAmuletCheck(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $character = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);

        return $character->asDragonAmuletCheckResponse();
    }

}