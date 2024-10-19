<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\CharacterItemProjection;
use hiperesp\server\services\CharacterBagService;
use hiperesp\server\services\CharacterService;

class CharacterItemController extends Controller {

    private CharacterService $characterService;
    private CharacterBagService $characterBagService;

    #[Request(
        endpoint: '/cf-itemdestroy.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function destroy(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterBagService->destroyItem($char, (int)$input->intCharItemID);

        return CharacterItemProjection::instance()->destroyed();
    }

}