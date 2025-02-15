<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\UserModel;
use hiperesp\server\vo\UserVO;

class ChatService extends Service {

    #[Inject] private UserModel $userModel;

    private array $commands = [
        "/help" => [
            "onlyAdmin" => false,
            "method" => "commandHelp",
            "help" => "Show this help message",
        ],
        "/ping" => [
            "onlyAdmin" => false,
            "method" => "commandPing",
            "help" => "Pong!",
        ],
        "/pin" => [
            "onlyAdmin" => true,
            "method" => "commandPin",
            "help" => "<message> - Pin a message",
        ],
        "/pin" => [
            "onlyAdmin" => true,
            "method" => "commandPin",
            "help" => "<message> - Pin a message",
        ],
        "/pin_server" => [
            "onlyAdmin" => true,
            "method" => "commandPinServer",
            "help" => "<message> - Pin a message as the server",
        ],
        "/unpin" => [
            "onlyAdmin" => true,
            "method" => "commandUnpin",
            "help" => "Unpin the first pinned message",
        ],
        "/server" => [
            "onlyAdmin" => true,
            "method" => "commandServer",
            "help" => "<message> - Send a message as the server",
        ],
        "/clear" => [
            "onlyAdmin" => true,
            "method" => "commandClear",
            "help" => "Clear the chat",
        ],
    ];

    private function addUserMessage(?UserVO $user, string $message, ?UserVO $to = null, bool $pinned = false): void {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);

        $maxGlobalHistory = 64;
        $maxPinnedHistory = 1;
        $maxMessageLength = 200;

        if($user !== null && $user?->id != 1) {
            if(\strlen($message) > $maxMessageLength) {
                $message = \trim(\substr($message, 0, $maxMessageLength)).'...';
            }
        }

        $messageArrayKey = $pinned ? 'pinned' : 'global';

        $messages[$messageArrayKey][] = [
            'id' => \uniqid(),
            'time' => \time(),

            'type' => $user ? 'user' : 'system',
            'from' => [
                'id' => $user?->id,
                'username' => $user?->username,
            ],
            'to' => $to?->id,
            'message' => $message,
        ];

        if($pinned) {
            $messages[$messageArrayKey] = \array_slice($messages[$messageArrayKey], -$maxPinnedHistory);
        } else {
            $messages[$messageArrayKey] = \array_slice($messages[$messageArrayKey], -$maxGlobalHistory);
        }

        \file_put_contents($chatFile, \json_encode($messages));
    }

    private function invalidCommand(UserVO $user): void {
        $this->addUserMessage(
            user: null,
            to: $user,
            message: "Invalid command. Type /help for a list of commands"
        );
    }

    private function commandPing(UserVO $user): void {
        $this->addUserMessage(
            user: null,
            to: $user,
            message: 'Pong!'
        );
    }

    private function commandPin(UserVO $user, string $message): void {
        $this->addUserMessage(
            user: $user,
            message: $message,
            pinned: true
        );
    }

    private function commandPinServer(UserVO $user, string $message): void {
        $this->addUserMessage(
            user: null,
            message: $message,
            pinned: true
        );
    }

    private function commandUnpin(UserVO $user): void {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);
        \array_shift($messages['pinned']);
        \file_put_contents($chatFile, \json_encode($messages));
    }

    private function commandServer(UserVO $user, string $message): void {
        $this->addUserMessage(
            user: null,
            message: $message
        );
    }

    private function commandClear(UserVO $user): void {
        $chatFile = $this->getChatFile();
        \file_put_contents($chatFile, \json_encode([
            'global' => [],
            'pinned' => [],
        ]));
    }

    private function noPermission(UserVO $user): void {
        $this->addUserMessage(
            user: null,
            to: $user,
            message: 'You do not have permission to use this command'
        );
    }

    private function commandHelp(UserVO $user): void {
        $commands = \array_filter($this->commands, function($data) use ($user) {
            return $user->id == 1 || !$data['onlyAdmin'];
        });
        $this->addUserMessage(
            user: null,
            to: $user,
            message: \implode("\n", \array_map(function($command, $data) {
                return "{$command} - {$data['help']}";
            }, \array_keys($commands), $commands))
        );
    }

    public function addMessage(string $userToken, string $message): void {
        $user = $this->userModel->getBySessionToken($userToken);
        if(!$user) {
            throw new DFException('Invalid token');
        }

        $message = \trim($message);
        if(!$message) {
            return;
        }

        if($message[0] == '/') {
            $commandMessage = \explode(' ', $message, 2);
            if(\count($commandMessage) == 1) {
                $command = $commandMessage[0];
                $message = '';
            } else {
                $command = $commandMessage[0];
                $message = $commandMessage[1];
            }

            $command = \strtolower($command);

            $commands = $this->commands;

            if(isset($commands[$command])) {
                $commandDef = $commands[$command];

                if($commandDef['onlyAdmin'] && $user->id != 1) {
                    $this->noPermission($user);
                    return;
                }
                $this->{$commandDef['method']}($user, $message);
            } else {
                $this->invalidCommand($user, $message);
            }
            return;
        }

        $this->addUserMessage(
            user: $user,
            message: $message
        );
    }

    public function getMessages(?string $userToken): array {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);

        if($userToken) {
            $user = $this->userModel->getBySessionToken($userToken);
        } else {
            $user = null;
        }
        return \array_merge(\array_map(function(array $message) {
            $message["pinned"] = true;
            return $message;
        }, $messages["pinned"]), \array_map(function(array $message) use ($user) {
            if($user?->id == 1) {
                $message['from']['username'].= " [ID: {$message['from']['id']}]";
            }
            $message["pinned"] = false;
            return $message;
        }, \array_filter($messages["global"], function(array $message) use ($user) {
            if($message["to"] === null) {
                return true;
            }
            if($user) {
                if($message['to'] == $user->id) {
                    return true;
                }
            }
            return false;
        })));
    }

    private function getChatFile(): string {
        global $base;
        $chatFile = $base.'/data/chat.json';
        if(!\file_exists($chatFile)) {
            \file_put_contents($chatFile, \json_encode([
                'global' => [],
                'pinned' => [],
            ]));
        }
        return $chatFile;
    }
}