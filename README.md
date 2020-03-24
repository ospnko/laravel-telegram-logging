# Laravel telegram logging package

This package makes it possible to send error reports to Telegram chat in formatted form.

## Installation

First of all you should install the package by composer.
```
composer require scary-layer/laravel-telegram-logging
```
After that open project's ```config/logging.php``` file and put this to channels array.

```php
'telegram' => [
    'driver'  => 'custom',
    'level'   => 'debug',
    'via'     => ScaryLayer\Logging\Telegram\Logger::class,
    'token'   => env('LOG_TELEGRAM_BOT_TOKEN', ''),
    'chat_id' => env('LOG_TELEGRAM_CHAT_ID', ''),
],
```
At the end open your .env file and set ``` LOG_TELEGRAM_BOT_TOKEN ``` and ```LOG_TELEGRAM_CHAT_ID``` values.

## Using

To add this channel as additional logging channel add telegram to stack channels array in ```config/logging.php``` like this
```php
'stack' => [
    'driver' => 'stack',
    'channels' => ['daily', 'telegram'],
    'ignore_exceptions' => false,
],
```
or, if you want enable logging only to telegram, just change ```LOG_CHANNEL``` value to telegram in project's .env file
