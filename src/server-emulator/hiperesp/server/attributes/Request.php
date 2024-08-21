<?php
namespace hiperesp\server\attributes;

use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

#[\Attribute]
class Request {
    public function __construct(
        private string $endpoint,
        private Input $inputType,
        private Output $outputType
    ) {
    }

    public function isEndpoint(string $endpointToTest): bool {
        return \strtolower($this->endpoint) === \strtolower($endpointToTest);
    }

    public function isDefaultEndpoint(): bool {
        return $this->isEndpoint('default');
    }

    public function getInput(): mixed {
        return $this->inputType->get();
    }

    public function displayOutput(mixed $output): void {
        $this->outputType->display($output);
    }
}