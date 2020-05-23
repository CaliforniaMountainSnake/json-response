# json-response

[![Build Status](https://travis-ci.com/CaliforniaMountainSnake/json-response.svg?branch=master)](https://travis-ci.com/CaliforniaMountainSnake/json-response)

A simple object for creating api responses.

## Install:
### Require this package with Composer
Install this package through [Composer](https://getcomposer.org/).
Edit your project's `composer.json` file to require `californiamountainsnake/json-response`:
```json
{
    "name": "yourproject/yourproject",
    "type": "project",
    "require": {
        "php": "^7.1",
        "californiamountainsnake/json-response": "*"
    }
}
```
and run `composer update`

### or
run this command in your command line:
```bash
composer require californiamountainsnake/json-response
```

## Usage examples:
```php
<?php
use CaliforniaMountainSnake\JsonResponse\JsonResponse;
return JsonResponse::error(['error_1', 'error_2', 'error_3'], JsonResponse::HTTP_BAD_REQUEST)->make();
```
