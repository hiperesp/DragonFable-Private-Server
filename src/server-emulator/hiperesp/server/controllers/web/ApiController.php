<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\services\WebApiService;

class ApiController extends Controller {

    #[Inject] private WebApiService $webApiService;

    #[Request(
        endpoint: '/api/web-stats.json',
        inputType: Input::NONE,
        outputType: Output::JSON
    )]
    public function webStats(): array {
        return $this->webApiService->stats();
    }

}