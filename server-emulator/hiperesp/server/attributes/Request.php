<?php
namespace hiperesp\server\attributes;

use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

#[\Attribute]
class Request {
    public function __construct(
        private string $method,
        private Input $inputType,
        private Output $outputType
    ) {
    }

    public function getMethod(): string {
        return \strtolower($this->method);
    }

    public function getInputType(): Input {
        return $this->inputType;
    }

    public function getOutputType(): Output {
        return $this->outputType;
    }
}