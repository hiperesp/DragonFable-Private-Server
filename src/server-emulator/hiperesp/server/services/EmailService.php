<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\vo\SettingsVO;
use hiperesp\server\vo\UserVO;

class EmailService extends Service {

    #[Inject] private SettingsVO $settings;

    public function sendWelcomeEmail(UserVO $user): bool {
        return $this->sendEmail(isCritical: false, user: $user, template: 'welcome', params: [
            'servername' => $this->settings->serverName,
            'username' => $user->username,
        ]);
    }

    public function sendRecoverPassword(UserVO $user, string $code): true {
        return $this->sendEmail(isCritical: true, user: $user, template: 'recover-password', params: [
            'servername' => $this->settings->serverName,
            'username' => $user->username,
            'code0' => $code[0],
            'code1' => $code[1],
            'code2' => $code[2],
            'code3' => $code[3],
            'code4' => $code[4],
            'code5' => $code[5],
        ]);
    }

    private function sendEmail(bool $isCritical, UserVO $user, string $template, array $params): bool {
        $credentials = (object)[
            'url'   => $this->settings->emailApiUrl,
            'token' => $this->settings->emailApiToken,
            'from'  => [
                "name" => $this->settings->serverName,
                "email" => $this->settings->emailAddress,
            ],
        ];

        $subject     = $this->loadTemplate('subject.txt',   $template, $params);
        $messageText = $this->loadTemplate('template.txt',  $template, $params);
        $messageHtml = $this->loadTemplate('template.html', $template, $params);

        $ch = \curl_init();
        \curl_setopt_array($ch, [
            CURLOPT_URL => $credentials->url,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$credentials->token}",
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => \json_encode([
                "from" => $credentials->from,
                "to" => [[
                    "name" => $user->username,
                    "email" => $user->email,
                ]],
                "subject" => $subject,
                "text" => $messageText,
                "html" => $messageHtml,
                "category" => $template,
            ]),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $sentStatus = \curl_exec($ch);
        if ($sentStatus === false) {
            if($isCritical) {
                $exception = "Error sending email: [".\curl_errno($ch)."] ".\curl_error($ch);
                \curl_close($ch);
                throw new \Exception($exception);
            }
        }
        \curl_close($ch);

        return !!$sentStatus;
    }

    private function loadTemplate(string $type, string $template, array $params): string {
        global $base;

        $path = "{$base}/hiperesp/email/{$template}/{$type}";
        if(!\file_exists($path)) {
            return "";
        }
        $templateFile = \file_get_contents($path);
        foreach($params as $key => $value) {
            $templateFile = \str_replace("{{{$key}}}", (string)$value, $templateFile);
        }
        return \trim($templateFile);
    }


}