<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\vo\SettingsVO;

class RedirectController extends Controller {

    #[Inject] private SettingsVO $settings;

    #[Request(
        endpoint: '/web/default.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT_301
    )]
    public function index(): string {
        return $this->settings->homeUrl;
    }

    #[Request(
        endpoint: '/web/game.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT_301
    )]
    public function play(): string {
        return $this->settings->playUrl;
    }

    #[Request(
        endpoint: '/web/df-signup.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT_301
    )]
    public function signUp(): string {
        return $this->settings->signUpUrl;
    }

    #[Request(
        endpoint: '/web/df-lostpassword.aspx',
        inputType: Input::NONE,
        outputType: Output::REDIRECT_301
    )]
    public function lostPassword(): string {
        return $this->settings->lostPasswordUrl;
    }

    #[Request(
        endpoint: '/web/df-terms.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT_301
    )]
    public function terms(): string {
        return $this->settings->tosUrl;
    }

    #[Request(
        endpoint: '/web/df-chardetail.asp',
        inputType: Input::QUERY,
        outputType: Output::REDIRECT_301
    )]
    public function charDetail(array $input): string {
        $queryParams = \http_build_query($input);
        return "{$this->settings->charDetailUrl}?{$queryParams}";
    }

}