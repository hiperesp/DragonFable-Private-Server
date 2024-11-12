<?php declare(strict_types=1);
namespace hiperesp\newtests;

class Test {

    public function __construct(
        private array $context = []
    ) {}

    final protected function set(string $key, $value): void {
        $this->context[$key] = $value;
    }
    final protected function get(string $key) {
        return $this->context[$key];
    }

}