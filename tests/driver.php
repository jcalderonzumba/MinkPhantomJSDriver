<?php
use Behat\PhantomJSExtension\Portergeist\Server;
use Behat\PhantomJSExtension\Portergeist\Client;
use Behat\PhantomJSExtension\Portergeist\Browser\Browser;

require_once "../vendor/autoload.php";

function stuff(Browser $browser) {
  var_dump($browser->visit("http://ft.devsnt.com/"));
  var_dump($browser->currentUrl());
  $elements = $browser->find("xpath", '//*[@id="index_mobile"]/table/tbody/tr[1]');
  $text = $browser->allHtml($elements["page_id"], $elements["ids"][0], "inner");
  print_r($elements);
  echo $text;
  $text = $browser->allHtml($elements["page_id"], $elements["ids"][0], "outer");
  echo $text;
  var_dump($browser->visit("http://ft.devsnt.com/newad"));
  var_dump($browser->currentUrl());
  $elements = $browser->find("xpath", '//*[@id="no_telemarketers"]');
  print_r($elements);
  $data = $browser->attributes($elements["page_id"], $elements["ids"][0]);
  print_r($data);
  print_r($browser->tagName($elements["page_id"], $elements["ids"][0]));

  $elements = $browser->find("xpath", '//*[@id="phone_hidden"]');
  print_r($elements);
  $data = $browser->attributes($elements["page_id"], $elements["ids"][0]);
  print_r($data);
  var_dump($browser->setAttribute($elements["page_id"], $elements["ids"][0], "checked", "checked"));
  $elements = $browser->find("xpath", '//*[@id="phone_hidden"]');
  print_r($elements);
  $data = $browser->attributes($elements["page_id"], $elements["ids"][0]);
  print_r($data);
  var_dump($browser->removeAttribute($elements["page_id"], $elements["ids"][0], "checked"));
  $elements = $browser->find("xpath", '//*[@id="phone_hidden"]');
  print_r($elements);
  $data = $browser->attributes($elements["page_id"], $elements["ids"][0]);
  print_r($data);
}

$server = Server::getInstance();
$server->start();
$client = Client::getInstance($server, array("path" => "/Users/juan/code/scm/pjsdriver/bin/phantomjs"));
$client->start();
$browser = new Browser($server, $client);

var_dump($browser->visit("https://www2.ekhanei.devsnt.com/ai/form/0"));
var_dump($browser->currentUrl());

$javascript = <<<JAVASCRIPT
(function () {
  function getElement(xpath) {
    var result;
    result = document.evaluate('//*[@id="category_group"]', document, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
    if (result.snapshotLength !== 1) {
      return null;
    }
    return result.snapshotItem(0);
  }

  function isSelect(element) {
    if (element === null) {
      return false;
    }
    return (element.tagName.toLowerCase() == "select");
  }

  function isRadioInput(element) {
    if (element === null) {
      return false;
    }
    return ((element.tagName.toLowerCase() == "input") && element.getAttribute("type").toLowerCase() == "radio");
  }

  function doOptionSelect(element, value) {
    var i;
    for (i = 0; element.options.length; i++) {
      if (element.options[i].value == value) {
        element.selectedIndex = i;
        return true;
      }
    }
    return false;
  }

  var element = getElement("whatever");
  if (!isSelect(element) && !isRadioInput(element)) {
    return false;
  }

  if (isSelect(element)) {
    return doOptionSelect(element, "2010");
  }

  if (isRadioInput(element)) {
    return doRadioSelect(element, "2010");
  }

  return false;
}());
JAVASCRIPT;

var_dump($browser->evaluate($javascript));

while (true) {
  sleep(10);
  echo "waiting stuff";
}
