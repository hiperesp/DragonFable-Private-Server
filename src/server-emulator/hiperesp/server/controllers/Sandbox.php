<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\storage\Storage;

class Sandbox extends Controller {

    #[Request(
        method: '/sandbox',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function sandbox(string $input): string {
        var_dump(Storage::getStorage());die;
        return "";
    }

}