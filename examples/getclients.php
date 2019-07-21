<?php

use \bitExpert\Testiat\Api;

require dirname(__DIR__, 1) . '/testiat.php';

$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$client = new \Buzz\Client\Curl($psr17Factory);
$api = new Api($client);
var_dump($api->getAvailableClients());