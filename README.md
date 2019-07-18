# testiat-api-client-php

This is the PHP API client for [Testi@](https://testi.at).


## Installation

Download `testiat.php` and place it in your project.

## Usage

`emails.php`
```php
require __DIR__ . '/testiat.php';

use \Testiat\Api;

$api = new Api();
```

```shell
php emails.php --apikey=<your-api-key>
```

Alternatively set the `TESTIAT_APIKEY` environment variable.

You can also use [vlucas/phpdotenv](https://packagist.org/packages/vlucas/phpdotenv) or [symfony/dotenv](https://packagist.org/packages/symfony/dotenv).


## Examples

```php
require __DIR__ . '/testiat.php';

use \Testiat\Api;

$api = new Api();
var_dump($api->getAvailableClients());
var_dump($api->getProjectStatus('GlZfbnMcnRphPcRSyQwFVXbcn3'));
var_dump($api->startEmailTest(
    'test123',
    '<p>Sample HTML code</p>',
    [1483107480, 1479404638]
));
```


## Available methods

All methods return a Promise and are either resolved to the API response or rejected with an Error object when one of the required arguments was not set or of the correct type.


### getAvailableClients

List the email clients that you can use.


### getProjectStatus($id)

Gets the status of the given project ID.


### startEmailTest($subject, $html, $clients)

Starts a new email test using the given subject, html string and array of email client IDs.