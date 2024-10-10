--TEST--
4 - Sample test (echo string with \r)
--FILE--
\ob_start();
echo "Sample\r\ntest\n";
$out = \ob_get_clean();
var_dump(\ord($out[6]), \ord($out[7]));

--EXPECT--
int(13)
int(10)
