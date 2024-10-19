<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\HouseShopProjection;
use hiperesp\server\services\HouseShopService;

class HouseShopController extends Controller {

    private HouseShopService $houseShopService;

    #[Request(
        endpoint: '/cf-houseshopload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $shop = $this->houseShopService->getShop((int)$input->intShopID);
        return HouseShopProjection::instance()->loaded($shop);
    }

    // #[Request(
    //     endpoint: '/cf-buyhouse.asp',
    //     inputType: Input::NINJA2,
    //     outputType: Output::XML
    // )]
    // public function buy(\SimpleXMLElement $input): \SimpleXMLElement {
    //     // <flash><intHouseID>1</intHouseID><intHouseShopID>1</intHouseShopID><strToken>f0ac492ae1c14d07118a54de7cbf46bb</strToken><intCharID>1</intCharID></flash>
    //     return new \SimpleXMLElement('<randomthing/>');
    // }

}