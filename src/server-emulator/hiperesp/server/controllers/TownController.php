<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\TownModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\projection\TownProjection;

class TownController extends Controller {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private TownModel $townModel;

    #[Request(
        endpoint: '/cf-loadtowninfo.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $town = $this->townModel->getById((int)$input->intTownID);

        return TownProjection::instance()->loaded($town);
    }

    #[Request(
        endpoint: '/cf-changehometown.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function changeHome(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $town = $this->townModel->getById((int)$input->intTownID);
        $this->characterModel->changeHomeTown($char, $town);

        return TownProjection::instance()->changedHome($town);
    }

}