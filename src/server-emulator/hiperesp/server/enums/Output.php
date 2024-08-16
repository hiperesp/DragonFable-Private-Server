<?php
namespace hiperesp\server\enums;

enum Output {
    case NINJA2STR;
    case NINJA2XML;
    case XML;
    case FORM;
    case RAW;
    case HTML;
    case REDIRECT;
}