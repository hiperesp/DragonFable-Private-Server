<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class Gui extends Controller {

    #[Request(
        method: '/cf-interfaceload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function interfaceLoad(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intInterfaceID>12</intInterfaceID></flash>

        return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<interface xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <intrface InterfaceID="12" strName="DragonInterface" strFileName="interfaces/DragonInterfaceBase-r3.swf?ver=1" bitLoadUnder="0"/>
</interface>
XML);
    }

}