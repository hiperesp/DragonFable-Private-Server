<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ItemModel;
use hiperesp\server\models\ItemShopModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\projection\CharacterItemProjection;
use hiperesp\server\projection\ItemShopProjection;

class ItemShopController extends Controller {

    private UserModel $userModel;
    private CharacterModel $characterModel;
    private ItemShopModel $itemShopModel;
    private ItemModel $itemModel;
    private CharacterItemModel $characterItemModel;

    #[Request(
        endpoint: '/cf-shopload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $shop = $this->itemShopModel->getById((int)$input->intShopID);
        return ItemShopProjection::instance()->loaded($shop);

    }

    #[Request(
        endpoint: '/cf-itembuy.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function buy(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken($input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);

        $shop = $this->itemShopModel->getById((int)$input->intShopID);
        $item = $this->itemModel->getByShopAndId($shop, (int)$input->intItemID);

        if(!$char->canBuyItem($item)) {
            throw new DFException(DFException::MONEY_NOT_ENOUGH);
        }
        $charItem = $this->characterItemModel->addItemToChar($char, $item);
        $this->characterModel->chargeItem($charItem);

        return CharacterItemProjection::instance()->bought($charItem);
    }

    #[Request(
        endpoint: '/cf-itemsell.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function sell(\SimpleXMLElement $input): \SimpleXMLElement {

        $user = $this->userModel->getBySessionToken($input->strToken);
        $char = $this->characterModel->getByUserAndId($user, (int)$input->intCharID);
        $charItem = $this->characterItemModel->getByCharAndId($char, (int)$input->intCharItemID);

        $this->characterModel->sellItem($char, $charItem,
            quantity: (int)$input->intAmnt,
            returnPercent: (int)$input->intReturnPer
        );

        return CharacterItemProjection::instance()->sold($charItem);
    }

}