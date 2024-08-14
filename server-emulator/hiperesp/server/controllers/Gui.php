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
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intInterfaceID>12</intInterfaceID></flash>

        $interfaceID = (int)$input->intInterfaceID;

        if($interfaceID==1) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<interface xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <intrface InterfaceID="1" strName="Quest Log" strFileName="interfaces/_QL/interface-QuestLog9Aug24.swf" bitLoadUnder="0"/>
</interface>
XML);
        }
        if($interfaceID==12) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<interface xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <intrface InterfaceID="12" strName="DragonInterface" strFileName="interfaces/DragonInterfaceBase-r3.swf?ver=1" bitLoadUnder="0"/>
</interface>
XML);
        }
        if($interfaceID==13) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<interface xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <intrface InterfaceID="13" strName="BoLBanner" strFileName="interfaces/Banners/salesbanner-August2024wk2.swf" bitLoadUnder="0"/>
</interface>
XML);
        }
        if($interfaceID==14) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<interface xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <intrface InterfaceID="14" strName="Badge Board" strFileName="interfaces/BadgesInterface.swf?ver=2" bitLoadUnder="0"/>
</interface>
XML);
        }


        return \simplexml_load_string(<<<XML
<error>
    <info code="538.07" reason="Invalid Input!" message="Message" action="None"/>
</error>
XML);
    }

}