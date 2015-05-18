<?php
use Behat\PhantomJSExtension\Portergeist\Server;
use Behat\PhantomJSExtension\Portergeist\Client;

require_once "../vendor/autoload.php";

$server = new Server();
$client = new Client($server, array("path" => "/Users/juan/code/scm/pjsdriver/bin/phantomjs"));
var_dump($client->getCommand());