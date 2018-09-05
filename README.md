# Unofficial TransIP PHP REST Client

TransIP offers a REST API and this package is a framework agnostic package for it. Under the hood it works with either Guzzle or Curl library.

### Testing on local environment

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
