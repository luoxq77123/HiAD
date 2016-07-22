<?php
var_dump(ip2long('127.0.0.1'));exit;
$memcache = new Memcache();
$memcache->connect('127.0.0.1', 11211);
$memcache->set('key', 'Memcache test successful!', 0, 60);
$result = $memcache->get('key');
unset($memcache);
echo $result;
?>