<?php

use \bitExpert\Testiat\Api;

require dirname(__DIR__, 1) . '/testiat.php';

$factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$client = new \Buzz\Client\Curl($factory);

$api = new Api($client, $factory, "someapikey");

var_dump($api->getAvailableClients());