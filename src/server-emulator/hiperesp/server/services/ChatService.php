<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\ChatCommand;
use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\UserModel;
use hiperesp\server\vo\UserVO;

class ChatService extends Service {

    #[Inject] private UserModel $userModel;

    #[ChatCommand("/ping", "Display pong")]
    public function commandPing(UserVO $user): void {
        $this->addUserMessage(
            user: null,
            to: $user,
            message: 'Pong!'
        );
    }

    #[ChatCommand("/pin", "Pin a message", true)]
    public function commandPin(UserVO $user, string $message): void {
        $this->addUserMessage(
            user: $user,
            message: $message,
            pinned: true
        );
    }

    #[ChatCommand("/pin_server", "Pin a message as the server", true)]
    public function commandPinServer(UserVO $user, string $message): void {
        $this->addUserMessage(
            user: null,
            message: $message,
            pinned: true
        );
    }

    #[ChatCommand("/unpin", "Unpin the first pinned message", true)]
    public function commandUnpin(UserVO $user): void {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);
        \array_shift($messages['pinned']);
        \file_put_contents($chatFile, \json_encode($messages));
    }

    #[ChatCommand("/server", "Send a message as the server", true)]
    public function commandServer(UserVO $user, string $message): void {
        $this->addUserMessage(
            user: null,
            message: $message
        );
    }

    #[ChatCommand("/vanish", "Clear all messages sent from you")]
    public function commandVanish(UserVO $user): void {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);
        $messages['global'] = \array_filter($messages['global'], fn($m) => $m['from']['id'] != $user->id);
        \file_put_contents($chatFile, \json_encode($messages));
    }

    #[ChatCommand("/clear_user", "Clear all messages from a specific user (by ID)", true)]
    public function commandClearUser(UserVO $user, int $userId): void {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);
        $messages['global'] = \array_filter($messages['global'], fn($m) => $m['from']['id'] != $userId);
        \file_put_contents($chatFile, \json_encode($messages));
    }

    #[ChatCommand("/clear", "Clear all messages, but keep pinned messages", true)]
    public function commandClearGlobal(UserVO $user): void {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);
        $messages['global'] = [];
        \file_put_contents($chatFile, \json_encode($messages));
    }

    #[ChatCommand("/clear_pinned", "Clear the pinned messages", true)]
    public function commandClearAll(UserVO $user): void {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);
        $messages['pinned'] = [];
        \file_put_contents($chatFile, \json_encode($messages));
    }

    #[ChatCommand("/help")]
    public function commandHelp(UserVO $user): void {
        $helpText = "Available commands:\n";

        $rClass = new \ReflectionClass($this);
        foreach($rClass->getMethods() as $rMethod) {
            foreach($rMethod->getAttributes(ChatCommand::class) as $rAttribute) {
                $attribute = $rAttribute->newInstance();
                if(!$attribute->canCall($user)) {
                    continue;
                }
                if(!$attribute->helpText) {
                    continue;
                }
                $helpText.= "{$attribute->getUsage($rMethod)} - {$attribute->helpText}\n";
            }
        }

        $this->addUserMessage(
            user: null,
            to: $user,
            message: $helpText
        );
    }

    #[ChatCommand("/msg")]
    public function message(UserVO $user, string $message): void {
        $maxMessageLength = 256;

        if($user !== null && $user?->id != 1) {
            if(\strlen($message) > $maxMessageLength) {
                $message = \trim(\substr($message, 0, $maxMessageLength)).'...';
            }
        }

        $this->addUserMessage(
            user: $user,
            message: $message
        );
    }

    private function syntaxError(UserVO $user, string $message): void {
        $this->addUserMessage(
            user: null,
            message: $message,
            to: $user
        );
    }

    private function invalidCommand(UserVO $user): void {
        $this->addUserMessage(
            user: null,
            to: $user,
            message: "Invalid command. Type /help for a list of commands"
        );
    }

    private function addUserMessage(?UserVO $user, string $message, ?UserVO $to = null, bool $pinned = false): void {
        $chatFile = $this->getChatFile();
        $messages = \json_decode(\file_get_contents($chatFile), true);

        $maxGlobalHistory = 64;
        $maxPinnedHistory = 1;

        $messageArrayKey = $pinned ? 'pinned' : 'global';

        $messages[$messageArrayKey][] = [
            'id' => \uniqid(),
            'time' => \time(),

            'type' => $user ? 'user' : 'system',
            'from' => [
                'id' => $user?->id,
                'username' => $user?->username,
                'isAdmin' => $user?->id == 1,
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

    public function addMessage(string $userToken, string $message): void {
        $user = $this->userModel->getBySessionToken($userToken);
        if(!$user) {
            throw new DFException('Invalid token');
        }

        $message = \trim($message);

        if(\substr($message, 0, 1) != '/') {
            $message = "/msg {$message}";
        }

        $rClass = new \ReflectionClass($this);
        foreach($rClass->getMethods() as $rMethod) {
            foreach($rMethod->getAttributes(ChatCommand::class) as $rAttribute) {
                $attribute = $rAttribute->newInstance();
                try {
                    $arguments = $attribute->call($rMethod, $user, $message);
                    if($arguments===null) {
                        continue;
                    }
                    $rMethod->invokeArgs($this, $arguments);
                } catch(\InvalidArgumentException|\TypeError $e) {
                    $this->syntaxError($user, $attribute->getUsage($rMethod));
                }
                return;
            } 
        }

        $this->invalidCommand($user);
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
        $chatFile = \sys_get_temp_dir().\DIRECTORY_SEPARATOR.'df-chat.json';
        if(!\file_exists($chatFile)) {
            \file_put_contents($chatFile, \json_encode([
                'global' => [],
                'pinned' => [],
            ]));
        }
        return $chatFile;
    }

    public function eventSource(?string $userToken): callable {
        $chatFile = $this->getChatFile();
        $lastTime = null;
        $fetchedMessages = [];
        $firstUpdated = false;

        return function() use($userToken, $chatFile, &$firstUpdated, &$fetchedMessages, &$lastTime): array {
            if($firstUpdated) {
                // update every 100ms
                \usleep(100_000);
            } else {
                $firstUpdated = true;
            }

            \clearstatcache();
            $newTime = \filemtime($chatFile);
            if($newTime == $lastTime) {
                return [
                    "event" => "update"
                ];
            }

            $lastTime = $newTime;
            $newMessages = [];

            $allMessages = $this->getMessages($userToken);
            foreach($allMessages as $message) {
                if(\in_array($message['id'], $fetchedMessages)) {
                    continue;
                }
                $newMessages[] = $message;
                $fetchedMessages[] = $message['id'];
            }

            $removedMessages = [];
            foreach($fetchedMessages as $key => $messageId) {
                if(\in_array($messageId, \array_column($allMessages, 'id'))) {
                    continue;
                }
                $removedMessages[] = $messageId;
                unset($fetchedMessages[$key]);
            }
            $fetchedMessages = \array_values($fetchedMessages);

            return [
                "event" => "message",
                "data" => \json_encode([
                    "new" => $newMessages,
                    "removed" => $removedMessages
                ])
            ];
        };
    }

}