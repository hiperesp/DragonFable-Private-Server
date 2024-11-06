<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\services\ApiService;

class ApiController extends Controller {

    #[Inject] private ApiService $apiService;

    #[Request(
        endpoint: '/api/web-stats.json',
        inputType: Input::NONE,
        outputType: Output::JSON
    )]
    public function webStats(): array {
        return $this->apiService->getWebStats();
    }

}