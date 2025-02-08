<?php declare(strict_types=1);
namespace hiperesp\server\exceptions;

final class SettingsNotFoundException extends DFException {

    public function __construct() {
        parent::__construct(DFException::SETTINGS_NOT_FOUND);
    }

}