<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\WarProjection;
use hiperesp\server\services\CharacterService;

class WarController extends Controller {

    private CharacterService $characterService;

    #[Request(
        endpoint: '/cf-loadwarvars.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        return WarProjection::instance()->loaded();
    }

}