<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\MergeShopProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\MergeShopService;

class MergeShopController extends Controller {

    private CharacterService $characterService;
    private MergeShopService $mergeShopService;

    #[Request(
        endpoint: '/cf-mergeshopload.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $shop = $this->mergeShopService->getShop((int)$input->intMergeShopID);
        return MergeShopProjection::instance()->loaded($shop);

    }

    #[Request(
        endpoint: '/cf-itemmerge.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function merge(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $merge = $this->mergeShopService->getMerge((int)$input->intMergeID);
        $mergedItems = $this->mergeShopService->merge($char, $merge);

        $newCharItem  = $mergedItems['newCharItem'];
        $removedItem1 = $mergedItems['removedItem1'];
        $removedItem2 = $mergedItems['removedItem2'];

        return MergeShopProjection::instance()->merged($merge, $newCharItem, $removedItem1, $removedItem2);
    }

}