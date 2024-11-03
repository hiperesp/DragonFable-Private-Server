<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\CharacterProjection;
use hiperesp\server\services\WebApiService;

class WebApiController extends Controller {

    private WebApiService $webApiService;

    #[Request(
        endpoint: '/DFversion.asp',
        inputType: Input::NONE,
        outputType: Output::FORM
    )]
    public function version(): array {
        return $this->webApiService->version();
    }

    #[Request(
        endpoint: '/custom/character-data',
        inputType: Input::QUERY,
        outputType: Output::FORM
    )]
    public function characterData(array $input): array {
        $char = $this->webApiService->characterData((int)$input["id"]);
        return CharacterProjection::instance()->characterPage($char);
    }

}