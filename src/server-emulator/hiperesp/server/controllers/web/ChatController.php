<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web;

use hiperesp\server\attributes\Inject;
use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\services\ChatService;

class ChatController extends Controller {

    #[Inject] private ChatService $chatService;

    #[Request(
        endpoint: '/chat',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function chat(array $input): array {
        $token = null;
        if(isset($input['token']) && $input['token']) {
            $token = $input['token'];
        }
        if(isset($input['message'])) {
            $this->chatService->addMessage($token, $input['message']);
        }
        return $this->chatService->getMessages($token);
    }

}