<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\HairShopProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\HairShopService;

class HairShopController extends Controller {

    #[Inject] private CharacterService $characterService;
    #[Inject] private HairShopService $hairShopService;

    #[Request(
        endpoint: '/cf-hairshopload.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $shop = $this->hairShopService->getShop((int)$input->intHairShopID);
        return HairShopProjection::instance()->loaded($shop, (string)$input->strGender);

    }

    #[Request(
        endpoint: '/cf-hairbuy.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function buy(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $hair = $this->hairShopService->buy($char, (int)$input->intHairID, (int)$input->intColorHair, (int)$input->intColorSkin);

        return HairShopProjection::instance()->bought($hair);
    }


}