<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\interfaces\Bannable;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\LogsModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\LogsVO;
use hiperesp\server\vo\UserVO;

class UserService extends Service {

    #[Inject] private EmailService $emailService;
    #[Inject] private UserModel $userModel;
    #[Inject] private CharacterModel $characterModel;
    #[Inject] private LogsModel $logsModel;

    public function auth(\SimpleXMLElement|array|string $inputOrUserToken): UserVO {
        if(\is_array($inputOrUserToken)) {
            if(!isset($inputOrUserToken['strToken'])) {
                throw new DFException(DFException::BAD_REQUEST);
            }
            $userToken = (string)$inputOrUserToken['strToken'];
        } else if($inputOrUserToken instanceof \SimpleXMLElement) {
            if(isset($inputOrUserToken->strUsername) && isset($inputOrUserToken->strPassword)) {
                $username = (string)$inputOrUserToken->strUsername;
                $password = (string)$inputOrUserToken->strPassword;
                return $this->userModel->login($username, $password);
            }
            if(!isset($inputOrUserToken->strToken)) {
                throw new DFException(DFException::BAD_REQUEST);
            }
            $userToken = (string)$inputOrUserToken->strToken;
        } else {
            $userToken = $inputOrUserToken;
        }

        $user = $this->userModel->getBySessionToken((string)$userToken);

        return $user;
    }

    public function signup(string $username, string $password, string $email, string $birthdate): UserVO {
        $user = $this->userModel->signup($username, $password, $email, $birthdate); // in case of error, a exception will be thrown
        $this->emailService->sendWelcomeEmail($user);
        return $user;
    }

    public function createChar(UserVO $user, array $input): CharacterVO {
		if($input['intNPC'] == 1)
		{
			return $this->characterModel->createAsh($user, $input);
		}
		if($input['intNPC'] == 2)
		{
			return $this->characterModel->createAlexander($user, $input);
		}
        return $this->characterModel->create($user, $input); // in case of error, a exception will be thrown
    }

    public function ban(Bannable $bannable, string $reason, LogsVO $action, array $additionalData = []): void {
        if($bannable instanceof CharacterVO) {
            $user = $bannable->getUser();
            $char = $bannable;
        } else if($bannable instanceof UserVO) {
            $user = $bannable;
            $char = null;
        } else {
            throw new DFException(DFException::INVALID_REFERENCE);
        }

        $this->logsModel->register(LogsModel::SEVERITY_INFO, 'banUser', $reason, $char, $action, $additionalData);

        $this->userModel->ban($user);

        throw new DFException(DFException::USER_BANNED);
    }

    public function getByEmail(string $email): UserVO {
        if(\filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new DFException(DFException::INVALID_EMAIL);
        }
        return $this->userModel->getByEmail($email);
    }

    public function sendRecoveryEmail(UserVO $user): bool {
        $recoveryCode = $this->userModel->defineRecoveryCode($user);
        return $this->emailService->sendRecoveryEmail($user, $recoveryCode);
    }

    public function validateRecoveryCode(UserVO $user, string $code): bool {
        if(!$user->recoveryExpires) { // user has no recovery code
            return false;
        }
        if(\strtotime($user->recoveryExpires) < \time()) { // code is expired
            return false;
        }
        if($user->recoveryCode !== $code) { // code is different
            return false;
        }
        return true;
    }

    public function changePassword(UserVO $user, string $password): void {
        $this->userModel->changePassword($user, $password);
        $this->emailService->sendChangedPasswordEmail($user);
    }

}