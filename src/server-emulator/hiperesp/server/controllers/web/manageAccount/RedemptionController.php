<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web\manageAccount;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class RedemptionController extends Controller {

    #[Request(
        endpoint: '/api/manage-account/redemptions',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function getRedemptions(array $input): array {
        return [];
    }

    #[Request(
        endpoint: '/api/manage-account/redemptions/redeem/1',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function redeemCode1(array $input): array {
        // return what this code can redeem
        return [];
    }

    #[Request(
        endpoint: '/api/manage-account/redemptions/redeem/2',
        inputType: Input::JSON,
        outputType: Output::JSON
    )]
    public function redeemCode2(array $input): array {
        // to redeem dragon coins, dragon amulet, etc to char or account
        return [];
    }

}