<?php
require __DIR__ . '/../../vendor/autoload.php';

use Behat\PhantomJSExtension\Tests\Server\LocalWebServer;

LocalWebServer::getInstance("-t www/web/ www/web/index.php");
