<?php
namespace hiperesp\server\vo;

class InterfaceVO extends ValueObject {

    public readonly int $id;
    public readonly string $name;
    public readonly string $swf;
    public readonly bool $loadUnder;

    public function asLoad(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<interface/>');
        $intrface = $xml->addChild('intrface');
        $intrface->addAttribute('InterfaceID', $this->id);
        $intrface->addAttribute('strName', $this->name);
        $intrface->addAttribute('strFileName', $this->swf);
        $intrface->addAttribute('bitLoadUnder', $this->loadUnder ? 1 : 0);
        return $xml;
    }
}
