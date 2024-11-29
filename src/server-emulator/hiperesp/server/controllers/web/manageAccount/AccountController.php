<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web\manageAccount;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class AccountController extends Controller {

    #[Request(
        endpoint: '/api/manage-account/login',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function login(array $input): array {
        return [];
    }

    #[Request(
        endpoint: '/api/manage-account/account-info',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function getAccountInfo(array $input): array {
        // username
        // email
        // email confirmation status
        // date created
        // last time played
        return [];
    }

    #[Request(
        endpoint: '/api/manage-account/characters',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function getCharacters(array $input): array {
        return [];
    }

    #[Request(
        endpoint: '/api/manage-account/preferences',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function getPreferences(array $input): array {
        // newsletterSubscription

        return [];
    }

    #[Request(
        endpoint: '/api/manage-account/preferences/update',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function updatePreferences(array $input): array {
        return [];
    }

}