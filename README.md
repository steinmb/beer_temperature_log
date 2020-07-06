# Beer temperature log
Reads and logs temperatures from 1Wire sensors connected to a Raspberry Pi. Written in PHP and uses R to create statistics and graphs. Written for fun as a homebrewed temperature fermentation monitoring making beer. The 1wire library written in PHP is genric and can be used in your own project without having to brew beer though brewing is considered a good thing. As well is sharing code.

## Example, find all attached sensors

```php
RuntimeEnvironment::setSetting('BREW_ROOT', __DIR__);
$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = $sensor->getTemperatureSensors();
```

## Example, write sensor data to log file

```php
$logger = new Logger('temperature');
$handler = new FileStorage();
$logger->pushHandler($handler);

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $logger->write((string) $temperature);
}

$logger->close();
```
