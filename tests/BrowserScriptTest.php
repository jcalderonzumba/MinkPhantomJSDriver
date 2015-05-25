<?php
namespace Behat\PhantomJSExtension\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

/**
 * Class BrowserScriptTest
 * @package Behat\PhantomJSExtension\Tests
 */
class BrowserScriptTest extends BrowserCommandsTestCase {

  public function testScriptEvaluate() {
    $webClient = new Client();
    /** @var $response Response */
    $response = $webClient->get($this->getTestPageBaseUrl() . "/static/fibonacci.js");
    $script = $response->getBody()->getContents();
    //Fibonacci(10) = 55;
    $this->assertEquals(55, $this->browser->evaluate($script));
  }

  protected function doFormSubmit() {
    //We have changed the form so lets get the element and see such changes
    $this->assertCount(2, $this->browser->find("xpath", '//*[@id="form_1014473"]'));
    $this->assertEquals("submit", $this->browser->trigger(1, 0, "submit"));
    //STUFF works like that, lets give 2 seconds to the browser for the page to load
    sleep(2);
    $this->assertEquals("http://127.0.0.1:6789/check-post-request/", $this->browser->currentUrl());
    $formResponse = json_decode(strip_tags($this->browser->getSource()), true);
    $this->assertTrue(is_array($formResponse));
    $this->assertEquals("THIS_IS_SPARTA", $formResponse["element_1"]);
    $this->assertEquals("1", $formResponse["element_3"]);
  }

  public function testScriptExecute() {
    $webClient = new Client();
    /** @var $response Response */
    $response = $webClient->get($this->getTestPageBaseUrl() . "/test/script_execute/execute.js");
    $script = $response->getBody()->getContents();
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/script_execute/form.html");
    $this->assertTrue($this->browser->execute($script));
    $this->doFormSubmit();
  }

  public function testScriptExtensions() {
    $viewJS = sprintf("%s/Server/www/web/test/script_extensions/extension.js", __DIR__);
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/script_extensions/form.html");
    $this->assertTrue($this->browser->extensions(array($viewJS)));
    $this->doFormSubmit();
  }
}
