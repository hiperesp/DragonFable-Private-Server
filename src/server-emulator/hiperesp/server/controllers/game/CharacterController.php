<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\CharacterProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\UserService;

class CharacterController extends Controller {

    #[Inject] private UserService $userService;
    #[Inject] private CharacterService $characterService;

    #[Request(
        endpoint: '/cf-characterload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        return CharacterProjection::instance()->loaded($char);
    }

    #[Request(
        endpoint: '/cf-characternew.asp',
        inputType: Input::FORM,
        outputType: Output::FORM
    )]
    public function new(array $input): array {
        $user = $this->userService->auth($input);

        $char = $this->userService->createChar($user, $input);

        return CharacterProjection::instance()->created();
    }

    #[Request(
        endpoint: '/cf-characterdelete.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function delete(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterService->delete($char);

        return CharacterProjection::instance()->deleted();
    }
	
	#[Request(
        endpoint: '/cf-npccharacternew.asp',
        inputType: Input::FORM,
        outputType: Output::FORM
    )]
    public function newSpecial(array $input): array {
        $user = $this->userService->auth($input);

        $char = $this->userService->createChar($user, $input);

        return CharacterProjection::instance()->created();
    }

    #[Request(
        endpoint: '/cf-dacheck.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function dragonAmuletCheck(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        return CharacterProjection::instance()->dragonAmuletCheck($char);
    }
	
	#[Request(
        endpoint: '/cf-goldsubtract.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function goldSubtract(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterService->subtractGold($char, goldCost: (int)$input->intGold);

        return CharacterProjection::instance()->goldSubtracted($char);
    }

    #[Request(
        endpoint: '/cf-statstrain.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function statsTrain(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterService->trainStats($char,
            wisdom: (int)$input->intWIS,
            charisma: (int)$input->intCHA,
            luck: (int)$input->intLUK,
            endurance: (int)$input->intEND,
            dexterity: (int)$input->intDEX,
            intelligence: (int)$input->intINT,
            strength: (int)$input->intSTR,
            goldCost: (int)$input->intCost
        );

        return CharacterProjection::instance()->statsTrained($char);
    }

    #[Request(
        endpoint: '/cf-statsuntrain.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function statsUntrain(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterService->untrainStats($char);

        return CharacterProjection::instance()->statsUntrained($char);
    }

    #[Request(
        endpoint: '/cf-bankload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function bankLoad(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        return CharacterProjection::instance()->bankLoaded($char);
    }

    #[Request(
        endpoint: '/cf-expsave.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function expSave(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $char = $this->characterService->applyExpSave($char,
            questId: (int)$input->intQuestID,
            experience: (int)$input->intExp,
            gems: (int)$input->intGems,
            gold: (int)$input->intGold,
            silver: (int)$input->intSilver
        );

        return CharacterProjection::instance()->expSaved($char);
    }

    #[Request(
        endpoint: '/cf-savequeststring.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function saveQuestString(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterService->setQuestString($char, (int)$input->intIndex, (int)$input->intValue);

        return CharacterProjection::instance()->questStringSaved();
    }

    #[Request(
        endpoint: '/cf-saveskillstring.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function saveSkillString(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterService->setSkillString($char, (int)$input->intIndex, (int)$input->intValue);

        return CharacterProjection::instance()->skillStringSaved();
    }
	
	#[Request(
        endpoint: '/cf-savearmorstring.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function saveArmorString(\SimpleXMLElement $input): \SimpleXMLElement {
        $char = $this->characterService->auth($input);

        $this->characterService->setArmorString($char, (int)$input->intIndex, (int)$input->intValue);

        return CharacterProjection::instance()->armorStringSaved();
    }

}