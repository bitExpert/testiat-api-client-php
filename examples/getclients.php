<?php
require dirname(__DIR__, 1) . '/testiat.php';

use \Testiat\Api;

$api = new Api();
var_dump($api->getAvailableClients());