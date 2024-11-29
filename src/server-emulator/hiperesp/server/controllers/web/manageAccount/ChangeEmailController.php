<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web\manageAccount;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class ChangeEmailController extends Controller {

    #[Request(
        endpoint: '/api/manage-account/change-email/1',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function changeEmail(array $input): array {
        $input['newEmail'];
        // will send an email with a code to the new email
        // will send an email with a code to the old email
        return [];
    }

    #[Request(
        endpoint: '/api/manage-account/change-email/2',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function changeEmail2(array $input): array {
        $input['newEmail'];
        $input['newEmailCode'];
        $input['oldEmailCode'];
        // validate the codes
        // change the email
        // email the user about the change
        return [];
    }

}