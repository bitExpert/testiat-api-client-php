# testiat-api-client-php

This is the PHP API client for [Testi@](https://testi.at).


## Installation

`composer require bitexpert/testiat-api-client-php`

## Usage

`emails.php`
```php
use \bitExpert\Testiat\Api;

require 'vendor/autoload.php';

$factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$client = new \Buzz\Client\Curl($factory);

$api = new Api($psr7Client, $psr17Factory, 'someapikey');
```

You can also use [vlucas/phpdotenv](https://packagist.org/packages/vlucas/phpdotenv) or [symfony/dotenv](https://packagist.org/packages/symfony/dotenv).


## Examples

```php
$factory = new \Nyholm\Psr7\Factory\Psr17Factory();
$client = new \Buzz\Client\Curl($factory);

$api = new Api($client, $factory, 'someapikey');

$availableClients = $api->getAvailableClients();
$availableClients = $availableClients->getBody();
var_dump(json_decode($availableClients));

$projectStatus = $api->getProjectStatus('GlZfbnMcnRphPcRSyQwFVXbcn3');
$projectStatus = $projectStatus->getBody();
var_dump(json_decode($projectStatus));

$emailTest = $api->startEmailTest(
    'test123',
    '<p>Sample HTML code</p>',
    [1483107480, 1479404638]
);
$emailTest = $emailTest->getBody();
var_dump(json_decode($emailTest));
```


## Available methods

All methods return a Promise and are either resolved to the API response or rejected with an Error object when one of the required arguments was not set or of the correct type.


### getAvailableClients

List the email clients that you can use.


### getProjectStatus($id)

Gets the status of the given project ID.


### startEmailTest($subject, $html, $clients)

Starts a new email test using the given subject, html string and array of email client IDs.