<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\vo\SettingsVO;

class ApiService extends Service {

    #[Inject] private UserService $userService;
    #[Inject] private CharacterModel $characterModel;
    #[Inject] private EmailService $emailService;
    #[Inject] private SettingsVO $settings;

    public function getWebStats(): array {
        return [
            'onlineUsers' => $this->characterModel->getOnlineCount(),
            'serverTime' => \date('c'),
            'serverVersion' => $this->settings->serverVersion,
            'gitRev' => \getenv('GIT_REV') ?: null,
        ];
    }
    

    public function recoveryPassword(string $email): array {
        if(!$this->settings->sendEmails) {
            return [
                'success' => false,
                'isEmailDisabled' => true,
                'supportEmail' => $this->settings->emailAddress,
                'error' => 'Emails are disabled'
            ];
        }
        try {
            $user = $this->userService->getByEmail($email);
        } catch(DFException $e) {
            $user = null;
        }
        if($user) {
            $isEmailSent = $this->userService->sendRecoveryEmail($user);
        } else {
            $isEmailSent = $this->emailService->sendRecoveryPasswordNoUserEmail($email);
        }

        if(!$isEmailSent) {
            return [
                'success' => false,
                'isEmailDisabled' => false,
                'supportEmail' => $this->settings->emailAddress,
                'error' => 'Email could not be sent'
            ];
        }

        return [
            'success' => true,
            'isEmailDisabled' => false,
            'supportEmail' => $this->settings->emailAddress,
            'error' => ''
        ];
    }

    public function recoveryPassword2(string $email, string $code): array {
        if(!$this->settings->sendEmails) {
            return [
                'success' => false,
                'isEmailDisabled' => true,
                'supportEmail' => $this->settings->emailAddress,
                'error' => 'Emails are disabled'
            ];
        }
        try {
            $user = $this->userService->getByEmail($email);
        } catch(DFException $e) {
            $user = null;
        }

        if(!$user || !$this->userService->validateRecoveryCode($user, $code)) {
            return [
                'success' => false,
                'isEmailDisabled' => false,
                'supportEmail' => $this->settings->emailAddress,
                'error' => 'Invalid or expired code'
            ];
        }

        return [
            'success' => true,
            'isEmailDisabled' => false,
            'supportEmail' => $this->settings->emailAddress,
            'error' => ''
        ];
    }

    public function recoveryPassword3(string $email, string $code, string $password): array {
        if(!$this->settings->sendEmails) {
            return [
                'success' => false,
                'isEmailDisabled' => true,
                'supportEmail' => $this->settings->emailAddress,
                'error' => 'Emails are disabled'
            ];
        }
        try {
            $user = $this->userService->getByEmail($email);
        } catch(DFException $e) {
            $user = null;
        }

        if(!$user || !$this->userService->validateRecoveryCode($user, $code)) {
            return [
                'success' => false,
                'isEmailDisabled' => false,
                'supportEmail' => $this->settings->emailAddress,
                'error' => 'Invalid or expired code'
            ];
        }

        $this->userService->changePassword($user, $password);

        return [
            'success' => true,
            'isEmailDisabled' => false,
            'supportEmail' => $this->settings->emailAddress,
            'error' => ''
        ];
    }
}