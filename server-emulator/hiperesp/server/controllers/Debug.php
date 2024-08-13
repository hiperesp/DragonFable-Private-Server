<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class Debug extends Controller {

    #[Request(
        method: '/debug',
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

    #[Request(
        method: 'default',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function default(string $input): string {
        $requestMethod = $_SERVER['PATH_INFO'];
        $methodName = \preg_replace('/[^a-zA-Z0-9\_]/', '', $requestMethod);

        $inputExample = null;
        $inputType = null;
        $inputCast = null;

        if(\preg_match('/<ninja2>(.+?)<\/ninja2>/', $input, $matches)) {
            $inputType = "Input::NINJA2";
            $inputCast = "\SimpleXMLElement";
            $inputExample = $this->crypto2->decrypt($matches[1]);
        } else if(\preg_match('/^</', $input)) {
            $inputType = "Input::XML";
            $inputCast = "\SimpleXMLElement";
            $inputExample = $input;
        } else {
            $inputType = "Input::FORM";
            $inputCast = "array";
            \parse_str($input, $inputExample);
            $inputExample = \json_encode($inputExample);
        }

        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, "https://dragonfable.battleon.com/game{$requestMethod}");
        \curl_setopt($ch, CURLOPT_POST, 1);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = \curl_exec($ch);
        \curl_close($ch);

        if($output===false) {
            throw new \Exception("Curl error: " . \curl_error($ch));
        }

        $outputType = null;
        $outputMethod = null;
        if(\preg_match('/<ninja2>(.+?)<\/ninja2>/', $output, $matches)) {
            $outputType = "Output::NINJA2XML";
            $outputMethod = "\SimpleXMLElement";
            $outputExample = <<<OUTPUT
            return \simplexml_load_string(<<<XML
            {$this->crypto2->decrypt($matches[1])}
            XML);
            OUTPUT;
        } else if(\preg_match('/^</', $output)) {
            $outputType = "Output::XML";
            $outputMethod = "\SimpleXMLElement";
            $outputExample = <<<OUTPUT
            return \simplexml_load_string(<<<XML
            $output
            XML);
            OUTPUT;
        } else {
            $outputType = "Output::FORM";
            $outputMethod = "array";
            \parse_str($output, $outputExample);
            $outputExample = \json_encode($outputExample);

            $outputExample = <<<OUTPUT
            return {$outputExample};
            OUTPUT;
        }

        $code = <<<PHP
            #[Request(
                method: '{$requestMethod}',
                inputType: {$inputType},
                outputType: {$outputType}
            )]
            public function {$methodName}({$inputCast} \$input): {$outputMethod} {
                // {$inputExample}
                {$outputExample}
            }
        
            //[dont-remove-this]
        PHP;

        $classFile = \file_get_contents(__DIR__.'/Unverified.php');
        $classFile = \preg_replace('/\/\/\[dont-remove-this\]/', \trim($code), $classFile);
        \file_put_contents(__DIR__.'/Unverified.php', $classFile);

        return $output;

    }


}