<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\storage\Storage;

class Sandbox extends Controller {

    #[Request(
        endpoint: '/sandbox',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function sandbox(string $input): string {
        $storage = Storage::getStorage();
        var_dump($storage->select('user', ['username' => 'user'], null));die;
        return "";
    }

}