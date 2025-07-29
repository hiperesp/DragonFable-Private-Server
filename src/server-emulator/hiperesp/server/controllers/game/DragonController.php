<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\DragonProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\DragonService;

class DragonController extends Controller {

    #[Inject] private CharacterService $characterService;
    #[Inject] private DragonService $dragonService;

    #[Request(
        endpoint: '/cf-dragonhatch.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonHatch(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $dragon = $this->dragonService->hatchDragon($char);

        return DragonProjection::instance()->projectDragon($dragon);
    }

    #[Request(
        endpoint: '/cf-dragonfeed.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonFeed(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $dragon = $this->dragonService->feedDragon($char, (int)$input->intFoodID);

        return DragonProjection::instance()->dragonFed($dragon);
    }

    #[Request(
        endpoint: '/cf-dragontrain.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonTrain(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $dragon = $this->dragonService->trainDragon($char, (int)$input->intDebuff, (int)$input->intBuff, (int)$input->intMelee, (int)$input->intMagic, (int)$input->intHeal);

        return DragonProjection::instance()->dragonTrained($dragon);
    }

    #[Request(
        endpoint: '/cf-dragonuntrain.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonUntrain(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->dragonService->untrainDragon($char);

        return DragonProjection::instance()->dragonUntrained($char);
    }

    #[Request(
        endpoint: '/cf-dragonelement.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonElement(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $dragon = $this->dragonService->dragonElement($char, (int)$input->intElement);

        return DragonProjection::instance()->dragonElementChanged($dragon);
    }

    #[Request(
        endpoint: '/cf-dragongrow.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonGrow(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $dragon = $this->dragonService->growDragon($char);

        return DragonProjection::instance()->dragonGrown($dragon);
    }

    #[Request(
        endpoint: '/cf-dragoncustomize.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonCustomize(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $dragon = $this->dragonService->customizeDragon($char, (int)$input->intTails, (int)$input->intHeads, (int)$input->intWings, (int)$input->intColorHorn, (int)$input->intColorEye, (int)$input->intColorWing, (int)$input->intColorSkin, $input->strName);

        return DragonProjection::instance()->dragonCustomized($dragon);
    }
}