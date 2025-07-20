<?php
namespace hiperesp\server\projection;

use hiperesp\server\attributes\Inject;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class DragonProjection extends Projection {
	
	#[Inject] private SettingsVO $settings;
	
	public function projectDragon($dragon): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><DragonHatch xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		$dragonNode = $xml->addChild('dragon');

		$dragonNode->addAttribute('idCore_CharDragons', $dragon['id']);
		$dragonNode->addAttribute('strName', $dragon['name']);
		$dragonNode->addAttribute('dateLastFed', \date('Y-m-d\TH:i:s', \strtotime($dragon['lastFed'])));
		$dragonNode->addAttribute('intGrowthLevel', $dragon['growthLevel']);
		$dragonNode->addAttribute('intTotalStats', $dragon['totalStats']);
		$dragonNode->addAttribute('intHeal', $dragon['heal']);
		$dragonNode->addAttribute('intMagic', $dragon['magic']);
		$dragonNode->addAttribute('intMelee', $dragon['melee']);
		$dragonNode->addAttribute('intBuff', $dragon['buff']);
		$dragonNode->addAttribute('intDebuff', $dragon['debuff']);
		$dragonNode->addAttribute('intColorDskin', $dragon['colorDSkin']);
		$dragonNode->addAttribute('intColorDeye', $dragon['colorDEye']);
		$dragonNode->addAttribute('intColorDhorn', $dragon['colorDHorn']);
		$dragonNode->addAttribute('intColorDwing', $dragon['colorDWing']);
		$dragonNode->addAttribute('intHeadID', $dragon['headId']);
		$dragonNode->addAttribute('strHeadFilename', $dragon['headFileName']);
		$dragonNode->addAttribute('intWingID', $dragon['wingId']);
		$dragonNode->addAttribute('strWingFilename', $dragon['wingFileName']);
		$dragonNode->addAttribute('intTailID', $dragon['tailId']);
		$dragonNode->addAttribute('strTailFilename', $dragon['tailFileName']);
		$dragonNode->addAttribute('strFilename', $dragon['filename']);
		$dragonNode->addAttribute('intMin', $dragon['min']);
		$dragonNode->addAttribute('intMax', $dragon['max']);
		$dragonNode->addAttribute('strType', $dragon['type']);
		$dragonNode->addAttribute('strElement', $dragon['element']);
		$dragonNode->addAttribute('intColorDelement', $dragon['colorDElement']);

		return $xml;
	}
	
	public function dragonFed($dragon): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><DragonFeed xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		$dragonNode = $xml->addChild('dragon');

		$dragonNode->addAttribute('intTotalStats', $dragon['totalStats']);
		$dragonNode->addAttribute('dateLastFed', \date('Y-m-d\TH:i:s', \strtotime($dragon['lastFed'])));

		return $xml;
	}
	
	public function dragonTrained($dragon): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><DragonTrain xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		$dragonNode = $xml->addChild('dragon');

		$dragonNode->addAttribute('intHeal', $dragon['heal']);
		$dragonNode->addAttribute('intMagic', $dragon['magic']);
		$dragonNode->addAttribute('intMelee', $dragon['melee']);
		$dragonNode->addAttribute('intBuff', $dragon['buff']);
		$dragonNode->addAttribute('intDebuff', $dragon['debuff']);

		return $xml;
	}
	
	public function dragonUntrained(CharacterVO $char): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><DragonUntrain xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		$xml->addChild('status', 'SUCCESS');

		return $xml;
	}
	
	public function dragonElementChanged($dragon): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><DragonElement xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		$dragonNode = $xml->addChild('dragon');

		$dragonNode->addAttribute('strElement', $dragon['element']);
		$dragonNode->addAttribute('intColorDelement', $dragon['colorDElement']);

		return $xml;
	}
	
	public function dragonGrown($dragon): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><DragonGrow xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		$dragonNode = $xml->addChild('dragon');

		$dragonNode->addAttribute('intTotalStats', $dragon['totalStats']);
		$dragonNode->addAttribute('intGrowthLevel', $dragon['growthLevel']);

		return $xml;
	}
	
	public function dragonCustomized($dragon): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><DragonCustomize xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		$dragonNode = $xml->addChild('dragon');

		$dragonNode->addAttribute('strName', $dragon['name']);
		$dragonNode->addAttribute('intColorDskin', $dragon['colorDSkin']);
		$dragonNode->addAttribute('intColorDeye', $dragon['colorDEye']);
		$dragonNode->addAttribute('intColorDhorn', $dragon['colorDHorn']);
		$dragonNode->addAttribute('intColorDwing', $dragon['colorDWing']);
		$dragonNode->addAttribute('intHeadID', $dragon['headId']);
		$dragonNode->addAttribute('strHeadFilename', $dragon['headFileName']);
		$dragonNode->addAttribute('intWingID', $dragon['wingId']);
		$dragonNode->addAttribute('strWingFilename', $dragon['wingFileName']);
		$dragonNode->addAttribute('intTailID', $dragon['tailId']);
		$dragonNode->addAttribute('strTailFilename', $dragon['tailFileName']);

		return $xml;
	}
}