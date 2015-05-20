<?php
use Behat\PhantomJSExtension\Portergeist\Server;
use Behat\PhantomJSExtension\Portergeist\Client;
use Behat\PhantomJSExtension\Portergeist\Browser\Browser;

require_once "../vendor/autoload.php";

$server = Server::getInstance();
$server->start();
$client = Client::getInstance($server, array("path" => "/Users/juan/code/scm/pjsdriver/bin/phantomjs"));
$client->start();
$browser = new Browser($server, $client);
var_dump($browser->visit("http://ft.devsnt.com/"));
var_dump($browser->currentUrl());
$elements = $browser->find("xpath", '//*[@id="index_mobile"]/table/tbody/tr[1]');
$text = $browser->allHtml($elements["page_id"], $elements["ids"][0], "inner");
print_r($elements);
echo $text;
$text = $browser->allHtml($elements["page_id"], $elements["ids"][0], "outer");
echo $text;
while (true) {
  sleep(10);
  echo "waiting stuff";
}
