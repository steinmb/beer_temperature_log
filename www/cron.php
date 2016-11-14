<?php
/**
 * @file cron.php
 *
 * Cron that needs to be run periodic from crond.
 */


/**
 * Read data to logfile.
 */
$sensors = getSensors();
$streams = getStreams($sensors);
$logString = readSensors($streams);

if ($logString)
  {
    writeLogFile($logString);

    if (time() > $endTime)
      {
        $end = TRUE;
      }
else
  {
    print 'No sensors found. Giving up' . PHP_EOL;
  }

closeStreams($streams);
