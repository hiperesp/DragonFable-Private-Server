<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\ClassProjection;
use hiperesp\server\services\CharacterService;

class ClassController extends Controller {

    #[Inject] private CharacterService $characterService;

    #[Request(
        endpoint: '/cf-changeclass.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function changeClass(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $class = $this->characterService->changeClass($char, (int)$input->intClassID);

        return ClassProjection::instance()->changed($class);
    }

    #[Request(
        endpoint: '/cf-classload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function loadClass(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $class = $this->characterService->loadClass($char, (int)$input->intClassID);

        return ClassProjection::instance()->loaded($class);
    }

}