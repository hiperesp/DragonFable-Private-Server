<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\services\SettingsService;

class SettingsController extends Controller {

    private SettingsService $settingsService;

    #[Request(
        endpoint: '/DFversion.asp',
        inputType: Input::NONE,
        outputType: Output::FORM
    )]
    public function version(): array {
        return $this->settingsService->version();
    }

}