<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web\manageAccount;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class ConfirmEmailController extends Controller {

    #[Request(
        endpoint: '/api/manage-account/confirm-email/1',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function confirmEmail(array $input): array {
        $input['emailCode'];
        // will send an email with a code
        return [];
    }

    #[Request(
        endpoint: '/api/manage-account/confirm-email/2',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function confirmEmail2(array $input): array {
        $input['emailCode'];
        // validate the code
        // confirm the email
        return [];
    }

}