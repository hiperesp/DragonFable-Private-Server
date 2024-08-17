<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\storage\Storage;

class Dev extends Controller {

    #[Request(
        endpoint: '/dev/',
        inputType: Input::RAW,
        outputType: Output::HTML
    )]
    public function dev(string $input): string {
        $output = <<<HTML
<h1>Dev</h1>
<hr>
<div style="display: flex;">
    <fieldset>
        <legend>General</legend>
        <form action="ninja2decrypt">
            <button>Ninja2 Decrypt</button>
        </form>
        <form action="sandbox">
            <button>Sandbox</button>
        </form>
    </fieldset>
    <fieldset>
        <legend>Database</legend>
        <form action="database/reset">
            <button>Clear</button>
        </form>
        <form action="database">
            <button>Debug</button>
        </form>
    </fieldset>
</div>
HTML;
// create a group with legend with the name of the group and the endpoints
        
        return $output;
    }

    #[Request(
        endpoint: '/dev/sandbox',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function sandbox(string $input): string {
        $storage = Storage::getStorage();
        var_dump($storage->select('user', ['username' => 'user'], null));die;
        return "";
    }

    #[Request(
        endpoint: '/dev/database',
        inputType: Input::RAW,
        outputType: Output::HTML
    )]
    public function database(string $input): string {
        $storage = Storage::getStorage();

        $output = "<style> body { background: black; color: white } table { font-family: 'Fira Code', monospace; border-collapse: collapse; } td, th { border: 1px solid #ddd; padding: 8px; } .collection { background-color: #204020; color: white; } tr:nth-child(even){background-color: #202020;} tr:hover {background-color: #404040;} th { padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #04AA6D; color: white; } </style>";
        foreach($storage->getCollections() as $collection) {
            $data = $storage->select($collection, [], null);
            if($data) {
                $header = \array_keys($data[0]);
                $headerLength = \count($header);
                $output .= "<table>";
                $output .= "<tr><th class=\"collection\" colspan=\"{$headerLength}\">{$collection}</th></tr>";
                $output .= "<tr>";
                foreach($header as $field) {
                    $output .= "<th>{$field}</th>";
                }
                $output .= "</tr>";
                foreach($data as $document) {
                    $output .= "<tr>";
                    foreach($header as $field) {
                        $output .= "<td>{$document[$field]}</td>";
                    }
                    $output .= "</tr>";
                }
                $output .= "</table>";
            } else {
                $output .= "<table>";
                $output .= "<tr><th class=\"collection\">{$collection}</th></tr>";
                $output .= "<tr><td>Empty</td></tr>";
                $output .= "</table>";
            }
            $output.= "<br>";
        }
        return $output;
    }

    #[Request(
        endpoint: '/dev/database/reset',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function databaseReset(string $input): string {
        $storage = Storage::getStorage();
        $storage->reset();
        return "Database cleared";
    }

    #[Request(
        endpoint: '/dev/ninja2decrypt',
        inputType: Input::FORM,
        outputType: Output::HTML
    )]
    public function ninja2decrypt(array $input): string {
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