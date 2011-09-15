<?php

$memCacheServers = array('localhost');
$memcache = new Memcache;
$numMemcacheServers = count($memCacheServers);
for ($i=0;$i<$numMemcacheServers;$i++) 
{
	print 'adding: ' . $memCacheServers[$i] . "\n";
	$memcache->addServer( $memCacheServers[$i], 11211 );
}

echo "Flushing Prod Memcache \n";
echo "########################\n";
$memcache->flush();

