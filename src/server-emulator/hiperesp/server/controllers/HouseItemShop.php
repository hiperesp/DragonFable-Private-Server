<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class HouseItemShop extends Controller {

    // [WIP]
    #[Request(
        endpoint: '/cf-loadhouseitemshop.asp',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function load(string $input): string {
        return "";
    }

}