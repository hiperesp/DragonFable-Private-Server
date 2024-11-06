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
        return $this->userModel->signup($username, $password, $email, $birthdate); // in case of error, a exception will be thrown
    }

    public function createChar(UserVO $user, array $input): CharacterVO {
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

}