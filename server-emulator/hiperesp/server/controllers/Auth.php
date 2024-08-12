<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class Auth extends Controller {

    #[Request(
        method: '/cf-userlogin.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function userLogin(\SimpleXMLElement $input): \SimpleXMLElement {
        $username = (string)$input->strUsername;
        $password = (string)$input->strPassword;

        if($username=='admin' && $password=='admin') {
            return \simplexml_load_string(<<<XML
<characters xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <user UserID="40346341" intCharsAllowed="3" intAccessLevel="0" intUpgrade="0" intActivationFlag="5" bitOptin="0" strToken="LOGINTOKENSTRNG" strNews="It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!&#10;&#10;It's Togsday!&#10;&#10;Check out the DNs for more info!" bitAdFlag="0" dateToday="2024-08-10T18:31:35.920">
        <characters CharID="12345678" strCharacterName="hiperesp" intLevel="1" intAccessLevel="1" intDragonAmulet="0" strClassName="Mage" strRaceName="Human" orgClassID="3"/>
    </user>
</characters>
XML);
        }
        return  \simplexml_load_string(<<<XML
<error>
    <info code="526.14" reason="User Not Found or Wrong Password" message="The username or password you typed was not correct. Please check the exact spelling and try again." action="None"/>
</error>
XML);
    }

}