<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\TownProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\TownService;

class TownController extends Controller {

    #[Inject] private CharacterService $characterService;
    #[Inject] private TownService $townService;

    #[Request(
        endpoint: '/cf-loadtowninfo.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $town = $this->townService->load((int)$input->intTownID);

        return TownProjection::instance()->loaded($town);
    }

    #[Request(
        endpoint: '/cf-changehometown.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function changeHome(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $town = $this->townService->load((int)$input->intTownID);
        $this->townService->changeHome($char, $town);

        return TownProjection::instance()->changedHome($town);
    }

}