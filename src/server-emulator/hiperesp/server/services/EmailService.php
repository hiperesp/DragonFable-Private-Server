<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\vo\UserVO;

class EmailService extends Service {

    public function sendWelcomeEmail(UserVO $user): bool {
        return $this->sendEmail(user: $user, template: 'welcome', params: [
            'username' => $user->username,
        ]);
    }

    public function sendRecoverPassword(UserVO $user): bool {
        return $this->sendEmail(user: $user, template: 'recover-password', params: [
            'email' => $user->email,
            'code1' => \random_int(0, 9),
            'code2' => \random_int(0, 9),
            'code3' => \random_int(0, 9),
            'code4' => \random_int(0, 9),
            'code5' => \random_int(0, 9),
            'code6' => \random_int(0, 9),
        ]);
    }

    private function sendEmail(UserVO $user, string $template, array $params): bool {
        try {
            $credentials = (object)[
                'protocol'  => 'smtp',
                'host'      => 'sandbox.smtp.mailtrap.io',
                'port'      => 587,
                'username'  => '4bf70efe6be20c',
                'password'  => '',
                'from'      => "DragonFable <welcome@dragonfable.hiper.esp.br>"
            ];

            $subject = $this->loadTemplate('subject.txt', $template, $params);
            $messageText = $this->loadTemplate('template.txt', $template, $params);
            $messageHtml = $this->loadTemplate('template.html', $template, $params);

            $to = \filter_var($user->email, FILTER_SANITIZE_EMAIL);
            $to = \filter_var("{$user->username}", FILTER_SANITIZE_FULL_SPECIAL_CHARS)." <{$to}>";

            $emailFile = $this->createEmailFile($credentials->from, $to, $subject, $messageText, $messageHtml);

            $ch = \curl_init();
            \curl_setopt_array($ch, [
                CURLOPT_URL => "{$credentials->protocol}://{$credentials->host}:{$credentials->port}",
                CURLOPT_MAIL_FROM => $credentials->from,
                CURLOPT_MAIL_RCPT => [$to],
                CURLOPT_USERNAME => $credentials->username,
                CURLOPT_PASSWORD => $credentials->password,
                CURLOPT_INFILE => $emailFile,
                CURLOPT_UPLOAD => true,
                CURLOPT_VERBOSE => true,
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2, // Specify the SSL version
            ]);

            $sentStatus = \curl_exec($ch);
            if ($sentStatus === false) {
                // echo \curl_errno($ch).' = '.\curl_error($ch).PHP_EOL;
                // die;
            }
            \curl_close($ch);
            \fclose($emailFile);

            return $sentStatus;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createEmailFile(string $from, string $to, string $subject, string $messageText, $messageHtml) {
        $boundary = $this->generateBoundary($messageText, $messageHtml);

        if(!$messageText && !$messageHtml) {
            throw new \InvalidArgumentException("No message content provided");
        }

        $emailFile = \tmpfile();
        \fwrite($emailFile, "From: \"{$from}\"\r\n");
        \fwrite($emailFile, "To: \"{$to}\"\r\n");
        \fwrite($emailFile, "Subject: {$subject}\r\n");
        \fwrite($emailFile, "MIME-Version: 1.0\r\n");
        \fwrite($emailFile, "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n");
        \fwrite($emailFile, "\r\n");
        if($messageText) {
            \fwrite($emailFile, "--{$boundary}\r\n");
            \fwrite($emailFile, "Content-Type: text/plain; charset=\"utf-8\"\r\n");
            \fwrite($emailFile, "Content-Transfer-Encoding: quoted-printable\r\n");
            \fwrite($emailFile, "Content-Disposition: inline\r\n");
            \fwrite($emailFile, "\r\n");
            \fwrite($emailFile, \quoted_printable_encode($messageText));
            \fwrite($emailFile, "\r\n");
        }
        if($messageHtml) {
            \fwrite($emailFile, "--{$boundary}\r\n");
            \fwrite($emailFile, "Content-Type: text/html; charset=\"utf-8\"\r\n");
            \fwrite($emailFile, "Content-Transfer-Encoding: quoted-printable\r\n");
            \fwrite($emailFile, "Content-Disposition: inline\r\n");
            \fwrite($emailFile, "\r\n");
            \fwrite($emailFile, \quoted_printable_encode($messageHtml));
            \fwrite($emailFile, "\r\n");
        }
        \fwrite($emailFile, "--{$boundary}--\r\n");
        \rewind($emailFile);

        return $emailFile;
    }

    private function generateBoundary(string ...$content): string {
        do {
            $boundary = \bin2hex(\random_bytes(16));
        } while(\str_contains(\implode('', $content), $boundary));
        return $boundary;
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