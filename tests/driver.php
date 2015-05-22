<?php
use Behat\PhantomJSExtension\Portergeist\Server;
use Behat\PhantomJSExtension\Portergeist\Client;
use Behat\PhantomJSExtension\Portergeist\Browser\Browser;

require_once "../vendor/autoload.php";

Twig_Autoloader::register();

$twigLoader = new Twig_Loader_Filesystem("/Users/juan/code/scm/pjsdriver/src/Resources/Script");
$twigEnv = new Twig_Environment($twigLoader, array('cache' => '/tmp/jcalderonzumba/phantomjs', 'strict_variables' => true));

$server = Server::getInstance("/Users/juan/code/scm/pjsdriver/bin/wsserver");
$server->start();
$client = Client::getInstance($server, array("path" => "/Users/juan/code/scm/pjsdriver/bin/phantomjs"));
$client->start();
$browser = new Browser($server, $client);
while (true) {
  sleep(10);
  echo "waiting stuff";
}

var_dump($browser->visit("https://www2.ekhanei.com/ai/form/0"));
var_dump($browser->currentUrl());
$xpath = '//*[@id="category_group"]';
$value = '2030';

$template = $twigEnv->loadTemplate("select_option.js.twig");
$javascript = $template->render(array("xpath" => $xpath, "value" => $value));
var_dump($browser->evaluate($javascript));
$options = array("full" => true, "selector" => null);
$tmpDir = sys_get_temp_dir();
$randomName = str_replace(".", "", str_replace(" ", "", microtime(false)));
$filePath = sprintf("%s/phantomjs_driver_screenshot_%s.png", $tmpDir, $randomName);
echo "$filePath\n\n";
$base64Screenshot = $browser->render("/Users/juan/Downloads/ekhanei.png", $options);

var_dump($browser->find("xpath", '//*[@id="wrapper"]/div/formfdsfadsf'));
while (true) {
  sleep(10);
  echo "waiting stuff";
}
