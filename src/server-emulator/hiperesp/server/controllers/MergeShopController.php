<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\MergeShopService;

class MergeShopController extends Controller {

    private CharacterService $characterService;
    private MergeShopService $mergeShopService;

    #[Request(
        endpoint: '/cf-mergeshopload.asp',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function load(string $input): string {
        return "";
    }

}