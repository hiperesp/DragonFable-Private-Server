<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\services\ApiService;

class RecoveryPasswordController extends Controller {

    #[Inject] private ApiService $apiService;

    #[Request(
        endpoint: '/api/recovery-password/1',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function step1(array $input): array {
        return $this->apiService->recoveryPassword((string)$input["email"]);
    }

    #[Request(
        endpoint: '/api/recovery-password/2',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function step2(array $input): array {
        return $this->apiService->recoveryPassword2((string)$input["email"], (string)$input["code"]);
    }

    #[Request(
        endpoint: '/api/recovery-password/3',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function step3(array $input): array {
        return $this->apiService->recoveryPassword3((string)$input["email"], (string)$input["code"], (string)$input["password"]);
    }

}