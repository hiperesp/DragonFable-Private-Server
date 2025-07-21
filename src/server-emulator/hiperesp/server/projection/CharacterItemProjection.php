<?php
namespace hiperesp\server\projection;

use hiperesp\server\vo\CharacterItemVO;

class CharacterItemProjection extends Projection {

    public function bought(CharacterItemVO $characterItemVO): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<shopItem/>');
        $xml->addChild('CharItemID', $characterItemVO->id);
        $xml->addChild('Bank', 0);
        $xml->addChild('BankCount', 1);

        return $xml;
    }

    public function destroyed(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<ItemDestroy/>');
        $xml->addChild('status', 'SUCCESS');

        return $xml;
    }

    public function sold(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<shopItem/>');
        $xml->addChild('status', 'SUCCESS');

        return $xml;
    }

	public function weaponConfigSaved(): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<SaveWeaponConfig/>');
		$xml->addChild('status', 'SUCCESS');

		return $xml;
	}
	
	public function bankTransfer(int $itemId): \SimpleXMLElement {
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><BankItem xmlns:sql="urn:schemas-microsoft-com:xml-sql"/>');
		$xml->addChild('CharItemID', (string)$itemId);
		
		return $xml;
	}

}