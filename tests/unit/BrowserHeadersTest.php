<?php

namespace Behat\PhantomJSExtension\Tests;

/**
 * Class BrowserHeadersTest
 * @package Behat\PhantomJSExtension\Tests
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
    $this->assertContains("x-permanent-test", $this->browser->getBody());
    $this->assertContains("x_permanent_value", $this->browser->getBody());
    //After reload, the header should still be there
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $this->assertContains("x-permanent-test", $this->browser->getBody());
    $this->assertContains("x_permanent_value", $this->browser->getBody());
  }

  public function testHeaderAddTemp() {
    $this->assertTrue($this->browser->addHeader(array("X-Permanent-Test" => "x_permanent_value")));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $this->assertContains("x-permanent-test", $this->browser->getBody());
    $this->assertContains("x_permanent_value", $this->browser->getBody());
    //After the visit the header should not be present in the request
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $this->assertNotContains("x-permanent-test", $this->browser->getBody());
    $this->assertNotContains("x_permanent_value", $this->browser->getBody());
  }

  public function testAddHeaders() {
    $customHeaders = array("X-Header-One" => "one", "X-Header-Two" => "two");
    $this->assertTrue($this->browser->addHeaders($customHeaders));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $this->assertContains("x-header-one", $this->browser->getBody());
    $this->assertContains("x-header-two", $this->browser->getBody());
    //now we will issue another header and the previous should be there too
    $this->assertTrue($this->browser->addHeaders(array("X-Header-Three" => "three")));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $this->assertContains("x-header-one", $this->browser->getBody());
    $this->assertContains("x-header-two", $this->browser->getBody());
    $this->assertContains("x-header-three", $this->browser->getBody());
  }


  public function testSetHeaders() {
    $customHeaders = array("X-Header-One" => "one", "X-Header-Two" => "two");
    $this->assertTrue($this->browser->setHeaders($customHeaders));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestBody = $this->browser->getBody();
    $this->assertContains("x-header-one", $requestBody);
    $this->assertContains("x-header-two", $requestBody);
    //now we will issue another header and the previous should NOT be here
    $this->assertTrue($this->browser->setHeaders(array("X-Header-Three" => "three")));
    $this->visitUrl($this->getTestPageBaseUrl() . "/check-request-headers/");
    $requestBody = $this->browser->getBody();
    $this->assertContains("x-header-three", $requestBody);
    $this->assertNotContains("x-header-one", $requestBody);
    $this->assertNotContains("x-header-two", $requestBody);
  }

}
