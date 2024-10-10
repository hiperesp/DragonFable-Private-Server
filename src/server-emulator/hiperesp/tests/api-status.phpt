--TEST--
1 - Api status
--FILE--
$data = \file_get_contents('http://localhost/server-emulator/server.php/api/web-stats.json');
$json = \json_decode($data, true);

var_dump(\array_key_exists('onlineUsers', $json));
var_dump(\array_key_exists('serverTime', $json));
var_dump(\array_key_exists('serverVersion', $json));
var_dump(\array_key_exists('gitRev', $json));
echo "\n";

var_dump(\gettype($json['onlineUsers']));
var_dump(\gettype($json['serverTime']));
var_dump(\gettype($json['serverVersion']));
var_dump(\gettype($json['gitRev'])=='string' || \gettype($json['gitRev'])=='NULL');
echo "\n";

var_dump($json['onlineUsers'] >= 0);
var_dump(\preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}+\d{2}:\d{2}$/', $json['serverTime']));

--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)

string(7) "integer"
string(6) "string"
string(6) "string"
bool(true)

bool(true)
int(0)

--CLEAN--
