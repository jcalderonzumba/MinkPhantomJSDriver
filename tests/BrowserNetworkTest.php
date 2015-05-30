<?php
namespace Behat\PhantomJSExtension\Tests;


/**
 * Class BrowserNetworkTest
 * @package Behat\PhantomJSExtension\Tests
 */
class BrowserNetworkTest extends BrowserCommandsTestCase {

  public function testNetworkTraffic() {
    $this->assertEmpty($this->browser->networkTraffic());
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $traffic = $this->browser->networkTraffic();
    $this->assertCount(6, $traffic);
    $this->assertInstanceOf("Behat\\PhantomJSExtension\\Portergeist\\NetworkTraffic\\Request", $traffic[0]);
    $this->assertNotEmpty($traffic[0]->getResponseParts());
    $this->assertInstanceOf("Behat\\PhantomJSExtension\\Portergeist\\NetworkTraffic\\Response", $traffic[0]->getResponseParts()[0]);
  }

  public function testClearNetworkTraffic(){
    $this->testNetworkTraffic();
    $this->assertTrue($this->browser->clearNetworkTraffic());
    $this->assertEmpty($this->browser->networkTraffic());
  }

}
