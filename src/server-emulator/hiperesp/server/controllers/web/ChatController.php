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
        endpoint: '/chat/stream',
        inputType: Input::QUERY,
        outputType: Output::LOOP_EVENT_SOURCE
    )]
    public function stream(array $input): callable {
        $token = null;
        if(\array_key_exists('token', $input)) {
            $token = $input['token'];
        }

        return $this->chatService->eventSource($token);
    }

    #[Request(
        endpoint: '/chat/send-message',
        inputType: Input::JSON,
        outputType: Output::NONE
    )]
    public function sendMessage(array $input): void {
        $token = null;
        if(isset($input['token']) && $input['token']) {
            $token = $input['token'];
        }
        if(isset($input['message'])) {
            $this->chatService->addMessage($token, $input['message']);
        }
    }

}