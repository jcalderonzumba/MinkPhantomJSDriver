<?php

require_once __DIR__ . '/../../vendor/behat/mink/driver-testsuite/bootstrap.php';

use Behat\PhantomJSExtension\Tests\Server\LocalWebServer;

$workingDir = realpath(__DIR__ . '/../../vendor/behat/mink/driver-testsuite/web-fixtures');
if (getenv('TRAVIS') != 'true') {
  LocalWebServer::getInstance("", $workingDir);
}
