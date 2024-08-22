<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\QuestModel;
use hiperesp\server\storage\Storage;
use hiperesp\server\util\DragonFableNinja2;

class Dev extends Controller {

    #[Request(
        endpoint: '/dev/sandbox',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function sandbox(string $input): string {

        return \implode("\n", []);
    }

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
        <legend>Tools</legend>
        <form action="ninja2decrypt">
            <button>Ninja2 Decrypt</button>
        </form>
        <form action="ninja2encrypt">
            <button>Ninja2 Encrypt</button>
        </form>
        <form action="sandbox">
            <button>Sandbox</button>
        </form>
    </fieldset>
    <fieldset>
        <legend>Util</legend>
        <form action="sandbox">
            <button>Sandbox</button>
        </form>
    </fieldset>
    <fieldset>
        <legend>Database</legend>
        <form action="database/setup">
            <button>Setup data</button>
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
        endpoint: '/dev/database',
        inputType: Input::RAW,
        outputType: Output::HTML
    )]
    public function database(string $input): string {
        $storage = Storage::getStorage(false);

        $output = "<style> body { background: black; color: white } table { font-family: 'Fira Code', monospace; border-collapse: collapse; } td, th { border: 1px solid #ddd; padding: 8px; } .collection { background-color: #204020; color: white; } tr:nth-child(even){background-color: #202020;} tr:hover {background-color: #404040;} th { padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #04AA6D; color: white; } </style>";
        foreach($storage->getCollections() as $collection) {
            $data = $storage->select($collection, [], $limit = 100);
            $count = \count($data);
            if($data) {
                $header = \array_keys($data[0]);
                $headerLength = \count($header);
                $output .= "<table>";
                $output .= "<tr><th class=\"collection\" colspan=\"{$headerLength}\">{$collection} <small>(showing {$count}, limited to {$limit}) <form action=\"database/clear\" style=\"display:inline-block;margin:0\" method=\"post\"><input type=\"hidden\" name=\"collection\" value=\"{$collection}\"><button>Clear</button></form></small></th></tr>";
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
        endpoint: '/dev/database/clear',
        inputType: Input::FORM,
        outputType: Output::REDIRECT
    )]
    public function databaseClear(array $input): string {
        $collection = $input['collection'];
        $storage = Storage::getStorage(false);
        $storage->drop($collection);
        return "database/debug";
    }

    #[Request(
        endpoint: '/dev/database/setup',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function databaseSetup(string $input): string {
        \ini_set('memory_limit', '16G');
        \set_time_limit(0);
        $storage = Storage::getStorage(false);
        $storage->setup();
        return "Database setup OK!";
    }

    #[Request(
        endpoint: '/dev/ninja2decrypt',
        inputType: Input::FORM,
        outputType: Output::HTML
    )]
    public function ninja2decrypt(array $input): string {
        $ninja2 = new DragonFableNinja2;

        $outputTxt = "";
        if(isset($input['input'])) {
            $outputTxt = \htmlspecialchars("{$ninja2->decrypt($input['input'])}");
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
        endpoint: '/dev/ninja2encrypt',
        inputType: Input::FORM,
        outputType: Output::HTML
    )]
    public function ninja2encrypt(array $input): string {
        $ninja2 = new DragonFableNinja2;

        $outputTxt = "";
        if(isset($input['input'])) {
            $outputTxt = \htmlspecialchars("{$ninja2->encrypt($input['input'])}");
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