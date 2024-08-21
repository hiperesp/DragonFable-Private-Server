<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\storage\Storage;

class Admin extends Controller {

    private Storage $storage;

    public function __construct() {
        parent::__construct();
        $this->storage = Storage::getStorage();
    }

    #[Request(
        endpoint: '/admin/',
        inputType: Input::RAW,
        outputType: Output::HTML
    )]
    public function index(string $input): string {
        return <<<HTML
<h1>Admin Panel</h1><hr>
<ul>
    <li><a href="char/list">Character List</a></li>
</ul>
HTML;
    }

    #[Request(
        endpoint: '/admin/char/list',
        inputType: Input::RAW,
        outputType: Output::HTML
    )]
    public function charList(string $input): string {
        $html = '<h1>Admin Panel</h1><hr><h2>Character List</h2><hr>';
        $html .= '<table border="1"><tr><th>ID</th><th>Name</th><th>Actions</th></tr>';
        $chars = $this->storage->select(CharacterModel::COLLECTION, []);
        foreach ($chars as $char) {
            $html .= '<tr>';
            $html .= '<td>' . $char['id'] . '</td>';
            $html .= '<td>' . $char['name'] . '</td>';
            $html .= '<td><a href="edit?id=' . $char['id'] . '">Edit</a></td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    #[Request(
        endpoint: '/admin/char/edit',
        inputType: Input::RAW,
        outputType: Output::HTML
    )]
    public function charEdit(string $input): string {
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            return 'Invalid ID';
        }
        $char = $this->storage->select(CharacterModel::COLLECTION, ['id' => $id]);
        if (empty($char)) {
            return 'Character not found';
        }
        $char = $char[0];

        $fields = '';
        foreach ($char as $key => $value) {
            if($key=='id') continue;
            $fields .= "<label for=\"{$key}\">{$key}</label><br>";
            $fields .= "<input type=\"text\" id=\"{$key}\" name=\"char[{$key}]\" value=\"{$value}\"><br>";
        }

        return <<<HTML
<h1>Admin Panel</h1><hr><h2>Edit Character</h2><hr>
<form action="save" method="post">
    <input type="hidden" name="char[id]" value="{$char['id']}">
    {$fields}
    <input type="submit" value="Save">
</form>
HTML;
    }

    #[Request(
        endpoint: '/admin/char/save',
        inputType: Input::FORM,
        outputType: Output::REDIRECT
    )]
    public function charSave(array $input): string {
        $char = $input['char'] ?? null;
        if ($char === null) {
            return 'list';
        }
        $this->storage->update(CharacterModel::COLLECTION, $char);
        return 'list';
    }

}