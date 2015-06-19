<?php
namespace Zumba\GastonJS\Tests;


/**
 * Class BrowserNetworkTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserNetworkTest extends BrowserCommandsTestCase {

  public function testNetworkTraffic() {
    $this->assertEmpty($this->browser->networkTraffic());
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $traffic = $this->browser->networkTraffic();
    $this->assertCount(6, $traffic);
    $this->assertInstanceOf("Zumba\\GastonJS\\NetworkTraffic\\Request", $traffic[0]);
    $this->assertNotEmpty($traffic[0]->getResponseParts());
    $this->assertInstanceOf("Zumba\\GastonJS\\NetworkTraffic\\Response", $traffic[0]->getResponseParts()[0]);
  }

  public function testClearNetworkTraffic(){
    $this->testNetworkTraffic();
    $this->assertTrue($this->browser->clearNetworkTraffic());
    $this->assertEmpty($this->browser->networkTraffic());
  }

}
