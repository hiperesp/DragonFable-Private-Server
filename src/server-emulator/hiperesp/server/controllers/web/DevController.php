<?php declare(strict_types=1);
namespace hiperesp\server\controllers\web;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\storage\Storage;
use hiperesp\server\util\DragonFableNinja2;

class DevController extends Controller {

    #[Request(
        endpoint: '/dev/sandbox',
        inputType: Input::NONE,
        outputType: Output::RAW
    )]
    public function sandbox(): string {
        return \implode("\n", []);
    }

    #[Request(
        endpoint: '/dev/phpinfo',
        inputType: Input::NONE,
        outputType: Output::HTML
    )]
    public function phpinfo(): string {
        \ob_start();
        \phpinfo();
        return \ob_get_clean();
    }

    #[Request(
        endpoint: '/dev/',
        inputType: Input::NONE,
        outputType: Output::HTML
    )]
    public function menu(): string {
        $output = <<<HTML
<h1>Dev</h1>
<hr>
<div style="display: flex;">
    <fieldset>
        <legend>Ninja2</legend>
        <form action="ninja2decrypt">
            <button>Ninja2 Decrypt</button>
        </form>
        <form action="ninja2encrypt">
            <button>Ninja2 Encrypt</button>
        </form>
    </fieldset>
    <fieldset>
        <legend>Database</legend>
        <form action="database/setup">
            <button>Setup/upgrade server</button>
        </form>
    </fieldset>
    <fieldset>
        <legend>Util</legend>
        <form action="sandbox">
            <button>Sandbox</button>
        </form>
        <form action="phpinfo">
            <button>PHP info</button>
        </form>
    </fieldset>
</div>
HTML;
// create a group with legend with the name of the group and the endpoints
        
        return $output;
    }

    #[Request(
        endpoint: '/dev/database/setup',
        inputType: Input::NONE,
        outputType: Output::RAW
    )]
    public function databaseUpgrade(): string {
        \ini_set('memory_limit', '16G');
        \set_time_limit(0);
        $storage = Storage::getStorage();
        $storage->setup();
        return "Database upgrade OK!";
    }

    #[Request(
        endpoint: '/dev/ninja2decrypt',
        inputType: Input::FORM,
        outputType: Output::HTML
    )]
    public function ninja2decrypt(array $input): string {
        $ninja2 = new DragonFableNinja2;

        $outputTxt = "";
        if(isset($input['input'])) {
            $outputTxt = \htmlspecialchars("{$ninja2->decrypt($input['input'])}");
        }
        return <<<HTML
        <pre>{$outputTxt}</pre>
        <form method='post'>
            <textarea name='input'></textarea><br>
            <button>Submit</button>
        </form>
        HTML;
    }

    #[Request(
        endpoint: '/dev/ninja2encrypt',
        inputType: Input::FORM,
        outputType: Output::HTML
    )]
    public function ninja2encrypt(array $input): string {
        $ninja2 = new DragonFableNinja2;

        $outputTxt = "";
        if(isset($input['input'])) {
            $outputTxt = \htmlspecialchars("{$ninja2->encrypt($input['input'])}");
        }
        return <<<HTML
        <pre>{$outputTxt}</pre>
        <form method='post'>
            <textarea name='input'></textarea><br>
            <button>Submit</button>
        </form>
        HTML;
    }

}