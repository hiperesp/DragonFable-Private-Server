<?php
namespace hiperesp\server\controllers;

class Auth extends Controller {
    
    public function entry(): void {
        var_dump($this->getInputXml());die;;
    }
}