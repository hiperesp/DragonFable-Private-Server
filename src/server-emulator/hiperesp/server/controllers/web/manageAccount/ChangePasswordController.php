<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web\manageAccount;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class ChangePasswordController extends Controller {

    #[Request(
        endpoint: '/api/manage-account/change-password',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function changePassword(array $input): array {
        $input['currentPassword'];
        $input['newPassword'];
        // will change the password
        // and email the user about the change
        return [];
    }

}