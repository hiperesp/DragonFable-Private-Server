<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\UserModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\UserVO;

class UserService extends Service {

    private UserModel $userModel;
    private CharacterModel $characterModel;

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

}