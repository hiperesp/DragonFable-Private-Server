#!/usr/bin/env php
<?php
define('OS', 'windows'); // windows, mac, linux
define('SWF_FILE', 'game15_9_59');

$replaces = [
    # REPLACE SOME FRONTEND URLS TO DYNAMIC URLS
    "\"http://www.dragonfable.com/df-activation.asp\"" => "_root.conn.url + \"web/df-activation.asp\"",
    "\"https://www.dragonfable.com/df-chardetail.asp?id=\"" => "_root.conn.url + \"web/df-chardetail.asp?id=\"",
    "\"https://www.dragonfable.com/amulet/df-upgrade3.asp?CharID=\"" => "_root.conn.url + \"web/df-upgrade3.asp?CharID=\"",
    "\"http://www.dragonfable.com/amulet/df-upgrade3.asp?CharID=\"" => "_root.conn.url + \"web/df-upgrade3.asp?CharID=\"",
    "\"<a href=\'https://www.dragonfable.com/df-lostpassword.aspx\'><u>Forgot your password</u></a>?\"" => "\"<a href=\'\" + _root.conn.url + \"web/df-lostpassword.aspx\'><u>Forgot your password</u></a>?\"",
    "\"<a href=\'https://www.dragonfable.com/df-signup.asp\'><u>Create a new account.</u></a>\"" => "\"<a href=\'\" + _root.conn.url + \"web/df-signup.asp\'><u>Create a new account.</u></a>\"",
    "\"http://dragonfable.battleon.com/game/cf-dacheck.asp\"" => "_root.conn.url + \"cf-dacheck.asp\"",
    "\"https://dragonfable.battleon.com/game/cf-dacheck.asp\"" => "_root.conn.url + \"cf-dacheck.asp\"",

    # REPLACE BUILD VERSION TO DYNAMIC VERSION STRING
    "var strBuild = " => "var strBuild = _root.core.gameVersion; //",

    # HIDE "SPECIAL CHARACTER SLOT" BUTTONS IF USER DOESN'T HAVE THE SPECIAL CHARACTER "ASH"
    '_root.game.btnChar6.gotoAndPlay("New");' => '_root.game.btnChar6.gotoAndStop("Blank");',
    '_root.game.btnChar7.gotoAndPlay("New");' => '_root.game.btnChar7.gotoAndStop("Blank");',
];

$complexReplaces = [
    "big-switch-statement-1",
    "game-server-url",
    "undefined-strings-in-actions-tooltips",
    "activation-button",
    "hide-error-code-if-empty",
    "external-feature-chat",
];

// add complex replaces to the replaces array
foreach($complexReplaces as $complexReplace) {
    $folder = __DIR__."/patches/{$complexReplace}";
    $searchFile = "{$folder}/search.txt";
    $replaceFile = "{$folder}/replace.txt";

    if(!\is_file($searchFile) || !\is_file($replaceFile)) {
        echo "Missing search or replace file for complex replace: {$complexReplace}\n";
        die;
    }

    $search = \file_get_contents($searchFile);
    $replace = \file_get_contents($replaceFile);

    $replaces[$search] = $replace;
}

$chainedAssignmentsReplaces = [];

// fix all chained assignments
for($i=20; $i>1; $i--) {
    // $key = '/(\s+)(.+?) = (.+?) = (.+?) = (.+?) = (.+?) = (.+?);/';
    // $value = "$1var hfca = $7;\n$1$2 = hfca;\n$1$3 = hfca;\n$1$4 = hfca;\n$1$5 = hfca;\n$1$6 = hfca;";

    $valueIndex = $i+2;

    $key = \str_replace('CHAINED_ASSIGNMENT', \str_repeat('(.+?) = ', $i), '/(\r?\n\s+)CHAINED_ASSIGNMENT(.+?);/');
    $value = "$1var HIPERESP_VAR_NAME = \${$valueIndex};";
    for($j=2; $j<$valueIndex; $j++) {
        $value .= "$1\${$j} = HIPERESP_VAR_NAME;";
    }

    $chainedAssignmentsReplaces[$key] = $value;
}

$file = SWF_FILE.".swf";
$outfile = SWF_FILE."-patched.swf";

$scriptsDir = SWF_FILE."-scripts";
$replacedScriptsDir = SWF_FILE."-patched-scripts";
if(!\is_dir($scriptsDir)) {
    \mkdir($scriptsDir);

    // first export all the scripts to a folder
    if(OS==='windows') {
        $cmd = "java -jar \"C:\\Program Files (x86)\\FFDec\\ffdec.jar\" -export script \"{$scriptsDir}\" {$file}\n";
    } else if(OS==='mac') {
        $cmd = "java -jar /Applications/FFDec.app/Contents/Resources/ffdec.jar -export script \"{$scriptsDir}\" {$file}\n";
    } else if(OS==='linux') {
        $cmd = "java -jar /opt/ffdec/ffdec.jar -export script \"{$scriptsDir}\" {$file}\n";
    } else {
        echo "Unknown OS\n";
        die;
    }

    echo "Run the following command:\n";
    echo "{$cmd}\n";
    die;
}

if(!\is_dir($replacedScriptsDir)) {
    \mkdir($replacedScriptsDir);
    // then replace all the occurrences of the keys with their values

    $replacesMatches = [];
    $chainedAssignmentsReplacesCount = 0;

    $scripts = globR("{$scriptsDir}");
    $uniqueId = 0;
    foreach ($scripts as $script) {
        $content = \file_get_contents($script);

        $content = \preg_replace('/\r\n/', "\n", $content);

        $newReplaces = [];

        foreach($replaces as $key => $value) {
            $key = \preg_replace('/\r\n/', "\n", $key);
            $value = \preg_replace('/\r\n/', "\n", $value);
            $newReplaces[$key] = $value;

            if(\strpos($content, $key) !== false) {
                $replacesMatches[$key]++;
            } else {
                if(!isset($replacesMatches[$key])) {
                    $replacesMatches[$key] = 0;
                }
            }
        }
        $newContent = \str_replace(\array_keys($newReplaces), \array_values($newReplaces), $content);

        foreach($chainedAssignmentsReplaces as $key => $value) {
            while(\preg_match($key, $newContent)) {
                $newValue = \str_replace('HIPERESP_VAR_NAME', "hiperesp_fix_chained_assignment_".($uniqueId++), $value);
                $newContent = \preg_replace($key, $newValue, $newContent, 1);
                $chainedAssignmentsReplacesCount++;
            }
        }

        if($content === $newContent) {
            continue;
        }

        $newFileName = \str_replace($scriptsDir, $replacedScriptsDir, $script);
        if(!\is_dir(\dirname($newFileName))) {
            \mkdir(\dirname($newFileName), 0777, true);
        }
        \file_put_contents($newFileName, $newContent);
    }

    $totalReplacesKeys = \count($replaces);
    $totalReplacedValues = 0;
    foreach($replacesMatches as $key => $count) {
        if($count === 0) {
            echo "Replace not matched: {$key}\n";die;
        }
        $totalReplacedValues += $count;
    }

    $info = "All string replaces matched\n";
    $info.= " - Total keys: {$totalReplacesKeys}\n";
    $info.= " - Total replaced values: {$totalReplacedValues}\n";
    $info.= " - Total chained assignments fixes: {$chainedAssignmentsReplacesCount}\n";
    $info.= "Done!\n";
    \file_put_contents(SWF_FILE."-info.txt", $info);
    echo $info;
}

if(!\is_file($outfile)) {
    \copy($file, $outfile);
}

// finally, import the scripts back to the swf file
if(OS==='windows') {
    $cmd = "java -jar \"C:\\Program Files (x86)\\FFDec\\ffdec.jar\" -importScript {$outfile} {$outfile} \"{$replacedScriptsDir}\"\n";
} else if(OS==='mac') {
    $cmd = "java -jar /Applications/FFDec.app/Contents/Resources/ffdec.jar -importScript {$outfile} {$outfile} \"{$replacedScriptsDir}\"\n";
} else if(OS==='linux') {
    $cmd = "java -jar /opt/ffdec/ffdec.jar -importScript {$outfile} {$outfile} \"{$replacedScriptsDir}\"\n";
} else {
    echo "Unknown OS\n";
    die;
}

echo "Run the following command:\n";
echo "{$cmd}\n";
echo "Then, done!\n";
die;

function globR($dir) {
    $files = [];
    foreach (\scandir($dir) as $file) {
        if ($file == '.' || $file == '..') continue;
        $path = $dir . '/' . $file;
        if (\is_dir($path)) {
            $files = \array_merge($files, globR($path));
        } else {
            $files[] = $path;
        }
    }
    return $files;
}
