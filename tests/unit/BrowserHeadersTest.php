<?php

namespace Zumba\GastonJS\Tests;

/**
 * Class BrowserHeadersTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserHeadersTest extends BrowserCommandsTestCase {

  public function testHeadersEmpty() {
    $this->assertCount(0, $this->browser->getHeaders());
  }

  public function testHeadersNotEmpty() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $responseHeaders = $this->browser->responseHeaders();
    $this->assertTrue(is_array($responseHeaders));
    $this->assertCount(4, $responseHeaders);
  }

  public function testHeaderAddPermanent() {
    $this->assertTrue($this->browser->addHeader(array("X-Permanent-Test" => "x_permanent_value"), true));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $this->assertEquals("x_permanent_value", $requestResponse["x-permanent-test"][0]);

    //After reload, the header should still be there
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $this->assertEquals("x_permanent_value", $requestResponse["x-permanent-test"][0]);
  }

  public function testHeaderAddTemp() {
    $this->assertTrue($this->browser->addHeader(array("X-Permanent-Test" => "x_permanent_value")));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $this->assertEquals("x_permanent_value", $requestResponse["x-permanent-test"][0]);

    //After the visit the header should not be present in the request
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $this->assertArrayNotHasKey("x-permanent-test", $requestResponse);
  }

  public function testAddHeaders() {
    $customHeaders = array("X-Header-One" => "one", "X-Header-Two" => "two");
    $this->assertTrue($this->browser->addHeaders($customHeaders));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $this->assertArrayHasKey("x-header-one", $requestResponse);
    $this->assertArrayHasKey("x-header-two", $requestResponse);

    //now we will issue another header and the previous should be there too
    $this->assertTrue($this->browser->addHeaders(array("X-Header-Three" => "three")));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $this->assertArrayHasKey("x-header-one", $requestResponse);
    $this->assertArrayHasKey("x-header-two", $requestResponse);
    $this->assertArrayHasKey("x-header-three", $requestResponse);
  }


  public function testSetHeaders() {
    $customHeaders = array("X-Header-One" => "one", "X-Header-Two" => "two");
    $this->assertTrue($this->browser->setHeaders($customHeaders));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $this->assertArrayHasKey("x-header-one", $requestResponse);
    $this->assertArrayHasKey("x-header-two", $requestResponse);

    //now we will issue another header and the previous should NOT be here
    $this->assertTrue($this->browser->setHeaders(array("X-Header-Three" => "three")));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $this->assertArrayHasKey("x-header-three", $requestResponse);
    $this->assertArrayNotHasKey("x-header-one", $requestResponse);
    $this->assertArrayNotHasKey("x-header-two", $requestResponse);
  }

}
