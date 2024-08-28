<?php

$replaces = [
    "\"http://www.dragonfable.com/df-activation.asp\"" => "_root.conn.url + \"web/df-activation.asp\"",
    "\"https://www.dragonfable.com/df-chardetail.asp?id=\"" => "_root.conn.url + \"web/df-chardetail.asp?id=\"",
    "\"https://www.dragonfable.com/amulet/df-upgrade3.asp?CharID=\"" => "_root.conn.url + \"web/df-upgrade3.asp?CharID=\"",
    "\"http://www.dragonfable.com/amulet/df-upgrade3.asp?CharID=\"" => "_root.conn.url + \"web/df-upgrade3.asp?CharID=\"",
    "\"<a href=\'https://www.dragonfable.com/df-lostpassword.aspx\'><u>Forgot your password</u></a>?\"" => "\"<a href=\'\" + _root.conn.url + \"web/df-lostpassword.aspx\'><u>Forgot your password</u></a>?\"",
    "\"<a href=\'https://www.dragonfable.com/df-signup.asp\'><u>Create a new account.</u></a>\"" => "\"<a href=\'\" + _root.conn.url + \"web/df-signup.asp\'><u>Create a new account.</u></a>\"",
    "\"http://dragonfable.battleon.com/game/cf-dacheck.asp\"" => "_root.conn.url + \"cf-dacheck.asp\"",
    "\"https://dragonfable.battleon.com/game/cf-dacheck.asp\"" => "_root.conn.url + \"cf-dacheck.asp\"",
    "\"Build 15.9.00\"" => "_root.core.gameVersion",
    <<<'ACTIONSCRIPT'
    _root.conn = new Object();
    if(_url.indexOf("file://") == -1)
    {
       _root.conn.url = "";
    }
    else
    {
       _root.conn.url = "https://dragonfable.battleon.com/game/";
    }
    if(_root.onfacebook != undefined)
    {
       _root.conn.url = "https://dragonfable.battleon.com/game/";
    }
    _root.conn.login = function(strUsername, strPassword)
    ACTIONSCRIPT => <<<'ACTIONSCRIPT'
    _root.conn = new Object();
    _root.conn.url = _root.core.server;
    _root.conn.login = function(strUsername, strPassword)
    ACTIONSCRIPT,
];

$file = "game15_9_00.swf";
$outfile = "{$file}-patched.swf";

$scriptsDir = "{$file}-scripts";
$replacedScriptsDir = "{$file}-patched-scripts";
if(!\is_dir("{$file}-scripts")) {
    \mkdir("{$file}-scripts");

    // first export all the scripts to a folder
    $cmd = "java -jar \"C:\\Program Files (x86)\\FFDec\\ffdec.jar\" -export script \"{$file}-scripts\" {$file}\n";

    echo "Run the following command:\n";
    echo "{$cmd}\n";
    die;
}

if(!\is_dir($replacedScriptsDir)) {
    \mkdir($replacedScriptsDir);
    // then replace all the occurrences of the keys with their values

    $replacesMatches = [];

    $scripts = globR("{$file}-scripts");
    foreach ($scripts as $script) {
        $content = \file_get_contents($script);

        foreach($replaces as $key => $value) {
            if(\strpos($content, $key) !== false) {
                $replacesMatches[$key]++;
            } else {
                if(!isset($replacesMatches[$key])) {
                    $replacesMatches[$key] = 0;
                }
            }
        }
        $newContent = \str_replace(\array_keys($replaces), \array_values($replaces), $content);
        if($content === $newContent) {
            continue;
        }

        $newFileName = \str_replace($scriptsDir, $replacedScriptsDir, $script);
        if(!\is_dir(\dirname($newFileName))) {
            \mkdir(\dirname($newFileName), 0777, true);
        }
        \file_put_contents($newFileName, $newContent);
    }

    foreach($replacesMatches as $key => $count) {
        if($count === 0) {
            echo "Replace not matched: {$key}\n";die;
        }
    }
    echo "All replaces matched\n";
    echo "Done!\n";
}

// finally, import the scripts back to the swf file
$cmd = "java -jar \"C:\\Program Files (x86)\\FFDec\\ffdec.jar\" -importScript {$file} {$outfile} \"{$file}-patched-scripts\"\n";
echo "Run the following command:\n";
echo "{$cmd}\n";
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
