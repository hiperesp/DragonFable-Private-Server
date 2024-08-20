<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\InterfaceModel;

class GameInterface extends Controller {

    private InterfaceModel $interfaceModel;

    #[Request(
        endpoint: '/cf-interfaceload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $interfaceID = (int)$input->intInterfaceID;
        $interface = $this->interfaceModel->getById($interfaceID);

        return $interface->asLoad();
    }

}