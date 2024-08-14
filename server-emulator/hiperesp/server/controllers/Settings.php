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
            "server"        => "http://localhost:8888/GabStep/DragonFable2024/server-emulator/server.php/",
            "gamefilesPath" => "http://localhost:8888/GabStep/DragonFable2024/another-server/cdn/custom-gamefiles-path/",
            "end"           => "here",
        ];
    }

}