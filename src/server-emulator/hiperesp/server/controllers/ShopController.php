<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ItemModel;
use hiperesp\server\models\ShopModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\projection\ShopProjection;

class ShopController extends Controller {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private ShopModel $shopModel;
    private ItemModel $itemModel;

    #[Request(
        endpoint: '/cf-shopload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $shop = $this->shopModel->getById((int)$input->intShopID);
        return ShopProjection::instance()->loaded($shop);

    }

    // [WIP]
    #[Request(
        endpoint: '/cf-itembuy.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function buy(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken($input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);

        $shop = $this->shopModel->getById((int)$input->intShopID);
        $item = $this->itemModel->getByShopAndId($shop, (int)$input->intItemID);

        $this->characterModel->buyItem($char, $item);

        return new \SimpleXMLElement('<shopItem xmlns:sql="urn:schemas-microsoft-com:xml-sql"><CharItemID>783181406</CharItemID><Bank>0</Bank><BankCount>1</BankCount></shopItem>');
    }

}