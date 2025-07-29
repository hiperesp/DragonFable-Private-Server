<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\CharacterItemProjection;
use hiperesp\server\services\CharacterBagService;
use hiperesp\server\services\CharacterService;

class CharacterItemController extends Controller {

    #[Inject] private CharacterService $characterService;
    #[Inject] private CharacterBagService $characterBagService;

    #[Request(
        endpoint: '/cf-itemdestroy.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function destroy(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterBagService->destroyItem($char, (int)$input->intCharItemID);

        return CharacterItemProjection::instance()->destroyed();
    }

    #[Request(
        endpoint: '/cf-saveweaponconfig.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function saveWeaponConfig(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $itemArray = array_map('intval', explode(',', (string)$input->strItems));

        $this->characterBagService->saveWeaponConfig($char, $itemArray);

        return CharacterItemProjection::instance()->weaponConfigSaved();
    }

    #[Request(
        endpoint: '/cf-toCharFromBank.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function bankToChar(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterBagService->bankToChar($char, (int)$input->intCharItemID);

        return CharacterItemProjection::instance()->bankTransfer((int)$input->intCharItemID);
    }

    #[Request(
        endpoint: '/cf-toBank.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function charToBank(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterBagService->charToBank($char, (int)$input->intCharItemID);

        return CharacterItemProjection::instance()->bankTransfer((int)$input->intCharItemID);
    }

}