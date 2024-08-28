<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class ErrorController extends Controller {

    #[Request(
        endpoint: 'default',
        inputType: Input::NONE,
        outputType: Output::HTML
    )]
    public function default(): string {
        \http_response_code(404);
        return "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p><hr>{$_SERVER["SERVER_SIGNATURE"]}</body></html>";
    }


}