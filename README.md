# Beer temperature log
Reads and logs temperatures from 1Wire sensors connected to a Raspberry Pi. Written in PHP and uses R to create statistics and graphs. Written for fun as a homebrewed temperature fermentation monitoring making beer. The 1wire library written in PHP is genric and can be used in your own project without having to brew beer though brewing is considered a good thing. As well is sharing code.

## Code examples

### Find all attached sensors

```php
RuntimeEnvironment::setSetting('BREW_ROOT', __DIR__);
$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();
```

### Write sensor data, one log file pr. attached sensor

```php
$loggerService = new Logger('temperature');

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $fileLogger = $loggerService->pushHandler(new FileStorage($probe . '.csv'));
    $fileLogger->write((string) $temperature);
    $fileLogger->close();
}
```
