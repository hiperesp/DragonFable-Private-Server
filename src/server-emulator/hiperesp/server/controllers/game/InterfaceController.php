<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\InterfaceProjection;
use hiperesp\server\services\InterfaceService;

class InterfaceController extends Controller {

    #[Inject] private InterfaceService $interfaceService;

    #[Request(
        endpoint: '/cf-interfaceload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        $interface = $this->interfaceService->load((int)$input->intInterfaceID);

        return InterfaceProjection::instance()->loaded($interface);
    }

}