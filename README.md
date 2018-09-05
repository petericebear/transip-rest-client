# Unofficial TransIP PHP REST Client

TransIP offers a REST API and this package is a framework agnostic package for it. Under the hood it can work with either Guzzle or Curl library.

## Usage Examples

Guzzle
```php
<?php
require 'vendor/autoload.php';

use TransIP\TransIPClient;
use TransIP\Adapter\GuzzleHttpAdapter;

// Using Guzzle 5 or 6...
$client = new TransIPClient(
    new GuzzleHttpAdapter('your-api-key')
);

$result = $client->vps()->vpses();

var_export($result);
```

CURL
```php
<?php
require 'vendor/autoload.php';

use TransIP\TransIPClient;
use TransIP\Adapter\CurlAdapter;

// Using regular CURL
$client = new TransIPClient(
    new CurlAdapter('your-api-key')
);

$result = $client->vps()->vpses();

var_export($result);
```

## Testing on local environment

Start a local php server on port 8000.
```bash
php -S localhost:8000 -t ./tests
```

Run the tests with:
```bash
./vendor/bin/phpunit 
 
#optional for prettier layout
./vendor/bin/phpunit --testdox
```
