<?php
namespace hiperesp\server\projection;

use hiperesp\server\vo\InterfaceVO;

class InterfaceProjection extends Projection {

    public function loaded(InterfaceVO $interface): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<interface/>');
        $interfaceEl = $xml->addChild('intrface');
        $interfaceEl->addAttribute('InterfaceID', $interface->id);
        $interfaceEl->addAttribute('strName', $interface->name);
        $interfaceEl->addAttribute('strFileName', $interface->swf);
        $interfaceEl->addAttribute('bitLoadUnder', $interface->loadUnder ? 1 : 0);
        return $xml;
    }

}