<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\ItemModel;
use hiperesp\server\models\ShopModel;

class Shop extends Controller {

    private ShopModel $shopModel;
    private ItemModel $itemModel;

    #[Request(
        endpoint: '/cf-shopload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $shop = $this->shopModel->getById((int)$input->intShopID);
        return $shop->asLoadShopResponse($this->itemModel);

    }

}