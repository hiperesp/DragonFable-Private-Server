<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class Settings extends Controller {

    #[Request(
        method: '/DFversion.asp',
        inputType: Input::RAW,
        outputType: Output::FORM
    )]
    public function version(string $input): array {
        return [
            "gamemovie"     => "game15_8_05-patched.swf",
            "server"        => "http://localhost:40000/server-emulator/server.php/",
            "gamefilesPath" => "http://localhost:40000/cdn/gamefiles/",
            "end"           => "here",
        ];
    }

}