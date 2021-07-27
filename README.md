# 1-Wire temperature logger

Reads temperature data from one or multiple [1-Wire](https://en.wikipedia.org/wiki/1-Wire) sensors. Data can be written to file(s) or external systems. Allows you to define fermentation ranges and batch numbers.

As a home brewer I wanted to monitor the beer during the fermentation but not use any pre-built solutions out there. Written in modern PHP is and pretty generic and can be used in your own projects without having to brew beer though making beer is generally a good thing.

## Logging data

Supports logging to:
* CSV files. Recorded data can be written to CSV files. One file pr. sensor. Supports batch/production-code to be added to sensors. CVS files can be analyzed by [R](https://www.r-project.org/) and exposed in a simple HTML page.
* [Telegram messenger](https://telegram.org/).
* Temperature alarms can be written to separate file or a separeate Telegram messager group.
* Brewers Friend brew sessions.

Feel free to write your own logger handler or request one. These are the one I needed and the time a wrote it and should be pretty easy to implement.
 
## Tested and works with

* [Raspberry Pi](https://en.wikipedia.org/wiki/Raspberry_Pi) computers.
* Supported OS - Linux.
  * Linux 1-Wire drivers loaded.
  * Sensor(s) attached to the GPIO bus. 
* Requires PHP 8.0 or newer.
* Graphing requires [R](https://www.r-project.org/)
* Webinterface requires a webserver service.
* [Brewers friend](https://www.brewersfriend.com/) require an account with a API key.

## Configuration

The application can use a configuration file, `settings.php`. Place it in the application root directory.

### settings.php example

```php
$configuration['LOG_DIRECTORY'] = '/home/linus/logs';
$configuration['BREW_ROOT'] = __DIR__;

$configuration['BATCH'] = [
    '83' => [
        'probe' => '28-0000098101de',
        'ambient' => '10-000802be73fa',
        'low_limit' => 19.3,
        'high_limit' => 23.8,
    ],
    '84' => [
        'probe' => '10-000802a55696',
        'ambient' => '10-000802be73fa',
        'low_limit' => 17,
        'high_limit' => 26,
    ],
    '85' => [
        'probe' => '10-000802a4ef03',
        'ambient' => '10-000802be73fa',
        'low_limit' => 17,
        'high_limit' => 26,
    ],
    'special' => [
        'probe' => '10-000802be7340',
        'ambient' => '10-000802be73fa',
        'low_limit' => 0.5,
        'high_limit' => 5,
    ],
];

$configuration['BREWERS_FRIEND'] = [
    'SESSION_ID' => '12345',
    'TOKEN' => 'a42abc3182b8fcb45754210312345',
];

$configuration['TELEGRAM'] = [
    'TOKEN' => '138712223:BBENCNuSFK34-ABC-BOpLGfJTCgxzdw1234',
    'CHANNEL' => '-100123712345',
];

$configuration['TELEGRAM_ALARM'] = [
    'TOKEN' => '138712223:BBENCNuSFK34-ABC-BOpLGfJTCgxzdw1234',
    'CHANNEL' => '-520654321',
];
 
```

## Usage

Have a look in the `examples` directory.

### Example, find all 1-Wire sensors

```php
use steinmb\Onewire\OneWire;

include_once __DIR__ . '/../vendor/autoload.php';

$oneWire = new OneWire();
echo (string) $oneWire . PHP_EOL;
```
