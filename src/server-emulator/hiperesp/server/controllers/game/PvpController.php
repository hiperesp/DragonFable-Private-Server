<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\PvpProjection;
use hiperesp\server\services\CharacterService;
use hiperesp\server\services\PvpService;

class PvpController extends Controller {

	#[Inject] private CharacterService $characterService;
	#[Inject] private PvpService $pvpService;

	#[Request(
		endpoint: '/cf-loadpvprandom.asp',
		inputType: Input::NINJA2,
        outputType: Output::XML
	)]
	public function loadRandom(\SimpleXMLElement $input): \SimpleXMLElement {
		$char = $this->characterService->auth($input);
		
		$player = $this->pvpService->loadRandom($char, (int)$input->intActionID, (int)$input->intCharID);
		if ($player == null)
		{
			return new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><PvPChar xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		}
		
		return PvpProjection::instance()->loaded($player);
	}

	#[Request(
		endpoint: '/cf-loadpvpchar.asp',
		inputType: Input::NINJA2,
        outputType: Output::XML
	)]
	public function loadChar(\SimpleXMLElement $input): \SimpleXMLElement {		
		$player = $this->pvpService->loadChar((int)$input->intPVPCharID);
		if ($player == null)
		{
			return new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><PvPChar xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		}
		
		return PvpProjection::instance()->loaded($player);
	}

	#[Request(
		endpoint: '/cf-loadpvpdragon.asp',
		inputType: Input::NINJA2,
        outputType: Output::XML
	)]
	public function loadDragonRider(\SimpleXMLElement $input): \SimpleXMLElement {
		$char = $this->characterService->auth($input);

		$dragonRider = $this->pvpService->loadDragonRider($char);
		if ($dragonRider == null)
		{
			return new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><PvPDragon xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		}

		return PvpProjection::instance()->loadedDragonRider($dragonRider);
	}
}