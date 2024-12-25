#!/usr/bin/env php
<?php
define('OS', 'windows'); // windows, mac, linux
define('SWF_FILE', 'game15_9_31');

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

    # REPLACE GAME SERVER URL
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

    # FIX "UNDEFINED" STRINGS IN ACTIONS TOOLTIPS
    <<<'ACTIONSCRIPT'
    function formatTTText(strText)
    {
       var _loc4_ = "";
       var _loc3_ = false;
       var _loc2_ = strText.split("");
       var _loc1_ = 0;
       while(_loc1_ <= _loc2_.length)
    ACTIONSCRIPT => <<<'ACTIONSCRIPT'
    function formatTTText(strText)
    {
       var _loc4_ = "";
       var _loc3_ = false;
       var _loc2_ = strText.split("");
       var _loc1_ = 0;
       while(_loc1_ < _loc2_.length)
    ACTIONSCRIPT,

    # ADD ACTION WHEN CLICKING ON ACTIVATION BUTTON (IF USER IS NOT ACTIVATED)
    <<<'ACTIONSCRIPT'
    if(_root.user.intActivationFlag != 1)
    {
       this._visible = false;
    }
    stop();
    ACTIONSCRIPT => <<<'ACTIONSCRIPT'
    if(_root.user.intActivationFlag != 1)
    {
       this._visible = false;
    }
    this.onRelease = function() {
       var result = new LoadVars();
       result.onLoad = function(success) {
          if(success) {
             traced("[Received]: " + result);
             _root.conn.showConnError("10.10", result.title, result.description, result.gameAction);
          } else {
             _root.conn.showConnError("10.10","Failed to start activation","We're experiencing technical difficulties, and your activation could not be processed at this time. Please try again later. If the issue persists, contact support for assistance.","none");
          }
       };
       result.onhttpStatus = function(httpStatus) {};
    
       var params = new LoadVars();
       params.strToken = _root.user.strToken;
       _root.conn.showConn("Starting activation...");
       params.sendAndLoad(_root.conn.url + "/custom/activate-account",result,"POST");
    };
    stop();
    ACTIONSCRIPT,

    # HIDE ERROR CODE IF EMPTY
    <<<'ACTIONSCRIPT'
    _root.conn.showConnError = function(strCode, strReason, strMessage, strAction)
    {
       if(strAction == "continue")
       {
          _root.game.mcConn.gotoAndStop("ErrContinue");
       }
       else
       {
          _root.game.mcConn.gotoAndStop("ErrorCritical");
       }
       _root.game.mcConn.strCode = "Error Code: " + strCode;
       _root.game.mcConn.strReason = strReason;
       _root.game.mcConn.strMsg = strMessage;
    };
    ACTIONSCRIPT => <<<'ACTIONSCRIPT'
    _root.conn.showConnError = function(strCode, strReason, strMessage, strAction)
    {
       if(strAction == "continue")
       {
          _root.game.mcConn.gotoAndStop("ErrContinue");
       }
       else
       {
          _root.game.mcConn.gotoAndStop("ErrorCritical");
       }
       if(strCode != "") {
          _root.game.mcConn.strCode = "Error Code: " + strCode;
       }
       _root.game.mcConn.strCode = "";
       _root.game.mcConn.strReason = strReason;
       _root.game.mcConn.strMsg = strMessage;
    };
    ACTIONSCRIPT,

    # HIDE FIRST "SPECIAL CHARACTER SLOT" BUTTON IF USER DOESN'T HAVE THE SPECIAL CHARACTER "ASH"
    <<<'ACTIONSCRIPT'
    _root.game.btnChar6.gotoAndPlay("New");
    ACTIONSCRIPT => <<<'ACTIONSCRIPT'
    _root.game.btnChar6.gotoAndStop("Blank");
    ACTIONSCRIPT,

    # HIDE SECOND "SPECIAL CHARACTER SLOT" BUTTON IF USER DOESN'T HAVE THE SPECIAL CHARACTER "ALEX"
    <<<'ACTIONSCRIPT'
    _root.game.btnChar7.gotoAndPlay("New");
    ACTIONSCRIPT => <<<'ACTIONSCRIPT'
    _root.game.btnChar7.gotoAndStop("Blank");
    ACTIONSCRIPT,

    # FIX ADVENTURE/NORMAL/DOOMED MODE BUTTONS
    <<<'ACTIONSCRIPT'
    toggleDoom = function()
    {
       if(_root.game.getDragonAmulet() > 0)
       {
          if(_root.battle.isActive != true)
          {
             _root.game.bitDifficulty = chkDiffDoom.bitChecked;
             chkDiffNormal.bitChecked = chkDiffNormal.checkmark._visible = false;
             _root.game.bitAdventure = chkDiffAdv.bitChecked = chkDiffAdv.checkmark._visible = false;
             _root.game.saveDiffMusPref();
          }
          else
          {
             _root.notify3("You cannot change game mode in the middle of a battle.");
             chkDiffDoom.checkmark._visible = _root.game.bitDifficulty;
          }
       }
       else
       {
          _root.notify("A Dragon Amulet is required to access Doomed Mode.");
          _root.game.bitDifficulty = chkDifficulty.bitChecked = chkDifficulty.checkmark._visible = false;
       }
       _root.game.updateDiffIcon();
    };
    toggleAdventure = function()
    {
       if(_root.game.getDragonAmulet() > 0)
       {
          if(_root.battle.isActive != true)
          {
             _root.game.bitAdventure = chkDiffAdv.bitChecked;
             chkDiffNormal.bitChecked = chkDiffNormal.checkmark._visible = false;
             _root.game.bitDifficulty = chkDiffDoom.bitChecked = chkDiffDoom.checkmark._visible = false;
             _root.game.saveDiffMusPref();
          }
          else
          {
             _root.notify3("You cannot change game mode in the middle of a battle.");
             chkDiffAdv.checkmark._visible = _root.game.bitAdventure;
          }
       }
       else
       {
          _root.notify("A Dragon Amulet is required to access Adventure Mode.");
          _root.game.bitDifficulty = chkDifficulty.bitChecked = chkDifficulty.checkmark._visible = false;
       }
       _root.game.updateDiffIcon();
    };
    toggleNormal = function()
    {
       if(_root.game.getDragonAmulet() > 0)
       {
          if(_root.battle.isActive != true)
          {
             _root.game.bitDifficulty = chkDiffDoom.bitChecked = chkDiffDoom.checkmark._visible = false;
             chkDiffNormal.bitChecked = chkDiffNormal.checkmark._visible = true;
             _root.game.bitAdventure = chkDiffAdv.bitChecked = chkDiffAdv.checkmark._visible = false;
             _root.game.saveDiffMusPref();
          }
          else
          {
             _root.notify3("You cannot change game mode in the middle of a battle.");
             chkDifficulty.checkmark._visible = _root.game.bitDifficulty;
          }
       }
       else
       {
          _root.notify("A Dragon Amulet is required to change game mode.");
       }
       _root.game.updateDiffIcon();
    };
    ACTIONSCRIPT => <<<'ACTIONSCRIPT'
    setMode = function(mode) {
        var isDoomed = _root.game.bitDifficulty;
        var isAdventure = _root.game.bitAdventure;
        var isNormal = !isDoomed && !isAdventure;

        var hasDragonAmulet = _root.game.getDragonAmulet() > 0;
        var isBattleActive = _root.battle.isActive;
        var canChangeMode = hasDragonAmulet && !isBattleActive;
    
        var toNotify = [];
        if(canChangeMode) {
            if(mode === 'doomed') {
                isDoomed = true;
                isNormal = false;
                isAdventure = false;
            } else if(mode === 'adventure') {
                isDoomed = false;
                isNormal = false;
                isAdventure = true;
            } else if(mode === 'normal') {
                isDoomed = false;
                isNormal = true;
                isAdventure = false;
            } else {
                _root.notify("Invalid game mode.");
            }
        }
    
        if(!hasDragonAmulet) {
            _root.notify("A Dragon Amulet is required to change game mode.");
        }
        if(isBattleActive) {
            _root.notify("You cannot change game mode in the middle of a battle.");
        }
    
        _root.game.bitDifficulty = isDoomed;
        chkDiffDoom.bitChecked = isDoomed;
        chkDiffDoom.checkmark._visible = isDoomed;
    
        chkDiffNormal.bitChecked = isNormal;
        chkDiffNormal.checkmark._visible = isNormal;
    
        _root.game.bitAdventure = isAdventure;
        chkDiffAdv.bitChecked = isAdventure;
        chkDiffAdv.checkmark._visible = isAdventure;
    
        _root.game.saveDiffMusPref();
        _root.game.updateDiffIcon();
    }
    toggleDoom      = function() {
        setMode('doomed');
    }
    toggleAdventure = function() {
        setMode('adventure');
    }
    toggleNormal    = function() {
        setMode('normal');
    }
    ACTIONSCRIPT,

    # FIX THE "Account" field in the character screen
    '_root.user.intAcessLevel' => '_root.user.intAccessLevel',
];

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
