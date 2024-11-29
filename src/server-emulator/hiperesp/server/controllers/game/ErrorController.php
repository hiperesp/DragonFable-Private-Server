<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\SettingsVO;

class ErrorController extends Controller {

    #[Inject] private SettingsVO $settings;

    #[Request(
        endpoint: 'default',
        inputType: Input::RAW,
        outputType: Output::NONE
    )]
    public function default(string $input): void {
        if($this->settings->detailed404ClientError) {
            if(\preg_match('/^<ninja2>(.+?)<\/ninja2>/', $input, $matches)) {
                $output = Output::NINJA2XML;
            } else if(\preg_match('/^<flash>(.+?)<\/flash>/', $input, $matches)) {
                $output = Output::XML;
            } else if(\preg_match('/\&.+?/', $input, $matches)) {
                $output = Output::FORM;
            }

            if(isset($output)) {
                $endpoint = $_SERVER["PATH_INFO"];
                $exception = DFException::dynamicError("Unknown endpoint", "The requested endpoint was not found on this server.\n<font color=\"#ff0000\">{$endpoint}</font>", "None");

                $output->error($exception);
                return;
            }
        }

        \http_response_code(404);

        $output = Output::HTML;
        $output->display("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p><hr>{$_SERVER["SERVER_SIGNATURE"]}</body></html>");
        return;
    }


}