<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\HeromartProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\HeromartService;

class HeromartController extends Controller {

    private CharacterService $characterService;
    private HeromartService $heromartService;

    #[Request(
        endpoint: '/cf-dcBuy.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function specialActionsBuy(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $char = $this->heromartService->buyAffect(
            char: $char,
            affectId: (int)$input->intBuyID,
            action: (int)$input->intAction,
            command: (string)$input->strCommand
        );

        return HeromartProjection::instance()->success();
    }

}