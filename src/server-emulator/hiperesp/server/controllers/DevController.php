<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

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
        endpoint: '/dev/test-txt',
        inputType: Input::FORM,
        outputType: Output::RAW
    )]
    public function testTxt(array $input): string {
        if(!isset($input["suite"])) {
            return "No suite provided";
        }
        \ob_start();
        $output = \hiperesp\tests\Runner::runSuite($input["suite"]);
        $logs = \ob_get_clean();
        return "{$logs}{$output}";
    }

    #[Request(
        endpoint: '/dev/test',
        inputType: Input::FORM,
        outputType: Output::HTML
    )]
    public function test(array $input): string {
        if(!isset($input["suite"])) {
            return "No suite provided";
        }
        \ob_start();
        $output = \hiperesp\tests\Runner::runSuite($input["suite"]);
        $logs = \ob_get_clean();
        $fullOutput = "{$logs}{$output}";

        $fullOutput = \preg_replace("/PASS/", "<span style='background-color: lime;  color: black;font-weight:bold;'>PASS</span>", $fullOutput);
        $fullOutput = \preg_replace("/FAIL/", "<span style='background-color: red;   color: white;font-weight:bold;'>FAIL</span>", $fullOutput);
        $fullOutput = \preg_replace("/SKIP/", "<span style='background-color: orange;color: black;font-weight:bold;'>SKIP</span>", $fullOutput);

        $fullOutput = "<body style='background-color: black; color: white'><pre style='white-space: pre-wrap;'>{$fullOutput}</pre></body>";

        return $fullOutput;
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
        $testsHtml = (function() {
            $output = "";
            foreach(\hiperesp\tests\Runner::getSuites() as $suite) {
                $output .= "<form action='test' method='POST'><button name='suite' value='{$suite}'>{$suite}</button></form>";
            }
            return $output;
        })();
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
        <form action="database/first-setup">
            <button>Setup collections</button>
        </form>
        <form action="database/update">
            <button>Update game data</button><!-- without char, char_item, logs, settings and user collections -->
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
    <fieldset>
        <legend>Tests</legend>
        {$testsHtml}
    </fieldset>
</div>
HTML;
// create a group with legend with the name of the group and the endpoints
        
        return $output;
    }

    #[Request(
        endpoint: '/dev/database/first-setup',
        inputType: Input::NONE,
        outputType: Output::RAW
    )]
    public function databaseFirstSetup(): string {
        \ini_set('memory_limit', '16G');
        \set_time_limit(0);
        $storage = Storage::getStorage();
        $storage->setup();
        return "Database setup OK!";
    }

    #[Request(
        endpoint: '/dev/database/update',
        inputType: Input::NONE,
        outputType: Output::RAW
    )]
    public function databaseUpdate(): string {
        \ini_set('memory_limit', '16G');
        \set_time_limit(0);
        $storage = Storage::getStorage();
        $storage->setup([ "char", "char_item", "logs", "settings", "user" ]);
        return "Database update OK!";
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