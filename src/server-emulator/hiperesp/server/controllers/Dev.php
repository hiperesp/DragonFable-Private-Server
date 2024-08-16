<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\storage\Storage;

class Dev extends Controller {

    #[Request(
        endpoint: '/dev/clear-db',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function sandbox(string $input): string {
        $storage = Storage::getStorage();
        $storage->reset();
        return "Database cleared";
    }

}