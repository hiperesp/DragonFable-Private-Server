<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\CharacterProjection;
use hiperesp\server\services\WebApiService;

class WebApiController extends Controller {

    #[Inject] private WebApiService $webApiService;

    #[Request(
        endpoint: '/DFversion.asp',
        inputType: Input::NONE,
        outputType: Output::FORM
    )]
    public function version(): array {
        return $this->webApiService->version();
    }

    #[Request(
        endpoint: '/custom/character-data',
        inputType: Input::QUERY,
        outputType: Output::FORM
    )]
    public function characterData(array $input): array {
        $char = $this->webApiService->characterData((int)$input["id"]);
        return CharacterProjection::instance()->characterPage($char);
    }

    #[Request(
        endpoint: '/custom/activate-account',
        inputType: Input::FORM,
        outputType: Output::FORM
    )]
    public function activateAccount(array $input): array {
        return [
            "title" => "Not Implemented!",
            "description" => "If you want to activate your account, please contact support for assistance.",
            "gameAction" => "continue"
        ];

        $isActivated = false;

        if(!$isActivated) {
            return [
                "title" => "Activation Email Sent",
                "description" => "An email has been sent to you with further instructions. Please check your inbox and follow the steps to complete the activation. If you already activated your account, please click the \"Activate\" button again.",
                "gameAction" => "continue"
            ];
        }
        return [
            "title" => "Activation Complete!",
            "description" => "Your account has been successfully activated. You may now log in again and continue playing.",
            "gameAction" => "none"
        ];
    }

}