<?php declare(strict_types=1);
namespace hiperesp\server\attributes;

use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\exceptions\DFException;

#[\Attribute(\Attribute::TARGET_METHOD)]
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

    public function displayError(\Throwable $exception): void {
        $this->outputType->error($exception);
    }

}