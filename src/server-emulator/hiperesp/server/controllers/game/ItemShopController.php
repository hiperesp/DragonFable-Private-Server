<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\CharacterItemProjection;
use hiperesp\server\projection\ItemShopProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\ItemShopService;

class ItemShopController extends Controller {

    #[Inject] private CharacterService $characterService;
    #[Inject] private ItemShopService $itemShopService;

    #[Request(
        endpoint: '/cf-shopload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $shop = $this->itemShopService->getShop((int)$input->intShopID);
		if($shop == null)
		{
			return new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><shop xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		}
        return ItemShopProjection::instance()->loaded($shop);

    }

    #[Request(
        endpoint: '/cf-itembuy.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function buy(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $charItem = $this->itemShopService->buy($char, (int)$input->intShopID, (int)$input->intItemID);

        return CharacterItemProjection::instance()->bought($charItem);
    }

    #[Request(
        endpoint: '/cf-itemsell.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function sell(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->itemShopService->sell($char,
            charItemId: (int)$input->intCharItemID,
            quantity: (int)$input->intAmnt,
            returnPercent: (int)$input->intReturnPer
        );

        return CharacterItemProjection::instance()->sold();
    }

}