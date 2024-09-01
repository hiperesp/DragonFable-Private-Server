<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\HouseShopModel;
use hiperesp\server\projection\HouseShopProjection;

class HouseShopController extends Controller {

    private HouseShopModel $houseShopModel;

    #[Request(
        endpoint: '/cf-houseshopload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $shop = $this->houseShopModel->getById((int)$input->intShopID);
        return HouseShopProjection::instance()->loaded($shop);
    }

    // [WIP]
    #[Request(
        endpoint: '/cf-buyhouse.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function buy(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intHouseID>1</intHouseID><intHouseShopID>1</intHouseShopID><strToken>f0ac492ae1c14d07118a54de7cbf46bb</strToken><intCharID>1</intCharID></flash>
        return new \SimpleXMLElement('<randomthing/>');
    }

}