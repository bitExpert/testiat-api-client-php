<?php

use \bitExpert\Testiat\Api;

require '../vendor/autoload.php';

$factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$client = new \Buzz\Client\Curl($factory);

$api = new Api($client, $factory, 'someapikey');

$response = $api->getAvailableClients();
$response = $response->getBody();
$parsedResponse = json_decode($response);
var_dump($parsedResponse);
