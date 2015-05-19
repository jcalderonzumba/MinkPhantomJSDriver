<?php
use Behat\PhantomJSExtension\Portergeist\Server;
use Behat\PhantomJSExtension\Portergeist\Client;
use Behat\PhantomJSExtension\Portergeist\Browser;

require_once "../vendor/autoload.php";

$server = new Server();
$server->start();
$client = new Client($server, array("path" => "/Users/juan/code/scm/pjsdriver/bin/phantomjs"));
$client->start();
$browser = new Browser($server, $client);
var_dump($browser->visit("http://www.ekhanei.com/"));
var_dump($browser->currentUrl());
while (true) {
  sleep(10);
  echo "waiting stuff";
}
