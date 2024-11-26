<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class WebManageAccountController extends Controller {

    #[Request(
        endpoint: '/api/manage-account/login',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function login(array $input): array {
        return [];
    }

    public function getAccountInfo(array $input): array {
        // username
        // email
        // email confirmation status
        // date created
        // last time played
        return [];
    }
    public function changePassword(array $input): array {
        $input['currentPassword'];
        $input['newPassword'];
        // will change the password
        // and email the user about the change
        return [];
    }
    public function changeEmail(array $input): array {
        $input['newEmail'];
        // will send an email with a code to the new email
        // will send an email with a code to the old email
        return [];
    }
    public function changeEmail2(array $input): array {
        $input['newEmail'];
        $input['newEmailCode'];
        $input['oldEmailCode'];
        // validate the codes
        // change the email
        // email the user about the change
        return [];
    }
    public function confirmEmail(array $input): array {
        $input['emailCode'];
        // validate the code
        // confirm the email
        return [];
    }

    public function getPreferences(array $input): array {
        // newsletterSubscription

        return [];
    }
    public function updatePreferences(array $input): array {
        return [];
    }

    public function getCharacters(array $input): array {
        return [];
    }

    public function getRedeemHistory(array $input): array {
        return [];
    }
    public function redeemCodeInfo(array $input): array {
        // return what this code can redeem
        return [];
    }
    public function redeemCode(array $input): array {
        // to redeem dragon coins, dragon amulet, etc to char or account
        return [];
    }

}