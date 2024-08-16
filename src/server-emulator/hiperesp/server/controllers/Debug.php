<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class Debug extends Controller {

    #[Request(
        endpoint: '/debug',
        inputType: Input::FORM,
        outputType: Output::HTML
    )]
    public function debug(array $input): string {
        $outputTxt = "";
        if(isset($input['input'])) {
            $outputTxt = \htmlspecialchars("{$this->crypto2->decrypt($input['input'])}");
        }
        return <<<HTML
        <pre>{$outputTxt}</pre>
        <form method='post'>
            <textarea name='input'></textarea><br>
            <button>Submit</button>
        </form>
        HTML;
    }

}