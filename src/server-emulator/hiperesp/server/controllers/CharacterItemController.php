<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\projection\CharacterItemProjection;

class CharacterItemController extends Controller {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private CharacterItemModel $characterItemModel;

    #[Request(
        endpoint: '/cf-itemdestroy.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function destroy(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken((string)$input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $charItem = $this->characterItemModel->getByCharAndId($char, (int)$input->intCharItemID);

        $this->characterItemModel->destroy($charItem);

        return CharacterItemProjection::instance()->destroyed($charItem);
    }

}