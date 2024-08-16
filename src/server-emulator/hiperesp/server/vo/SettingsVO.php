<?php
namespace hiperesp\server\vo;

class SettingsVO extends ValueObject {

    public readonly string $news;

    public function __construct() {
        $this->news = "It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!\n\nIt's Togsday!\n\nCheck out the DNs for more info!";
    }

}