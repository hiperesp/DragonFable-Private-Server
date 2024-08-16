<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\storage\Storage;

class Dev extends Controller {

    #[Request(
        endpoint: '/dev/db/clear',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function dbClear(string $input): string {
        $storage = Storage::getStorage();
        $storage->reset();
        return "Database cleared";
    }
    #[Request(
        endpoint: '/dev/db/dd',
        inputType: Input::RAW,
        outputType: Output::HTML
    )]
    public function dbDebug(string $input): string {
        $storage = Storage::getStorage();

        $output = <<<HTML
<style>
body { background: black; color: white }
table { font-family: 'Fira Code', monospace; border-collapse: collapse; }
td, th { border: 1px solid #ddd; padding: 8px; }
.collection { background-color: #204020; color: white; }
tr:nth-child(even){background-color: #202020;}
tr:hover {background-color: #404040;}
th { padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #04AA6D; color: white; }
</style>
HTML;
        foreach($storage->getCollections() as $collection) {
            $data = $storage->select($collection, [], null);
            // output table html
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

}