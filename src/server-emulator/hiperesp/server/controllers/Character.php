<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\projection\CharacterProjection;

class Character extends Controller {

    private UserModel $userModel;
    private CharacterModel $characterModel;

    #[Request(
        endpoint: '/cf-characterload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken($input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);

        return CharacterProjection::instance()->loaded($char, $user);
    }

    #[Request(
        endpoint: '/cf-characternew.asp',
        inputType: Input::FORM,
        outputType: Output::FORM
    )]
    public function new(array $input): array {

        $user = $this->userModel->getBySessionToken($input['strToken']);
        $char = $this->characterModel->create($user, $input); // in case of error, a exception will be thrown

        return CharacterProjection::instance()->created();
    }

    #[Request(
        endpoint: '/cf-characterdelete.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function delete(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $this->characterModel->delete($char);

        return CharacterProjection::instance()->deleted();
    }

    #[Request(
        endpoint: '/cf-dacheck.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonAmuletCheck(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);

        return CharacterProjection::instance()->dragonAmuletCheck($char);
    }

}