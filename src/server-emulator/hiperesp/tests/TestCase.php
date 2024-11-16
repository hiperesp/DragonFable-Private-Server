<?php declare(strict_types=1);
namespace hiperesp\newtests;

#[\Attribute]
class TestCase {

    public function __construct(
        public string $name
    ) {}

}