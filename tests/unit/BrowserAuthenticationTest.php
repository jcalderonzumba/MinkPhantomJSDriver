<?php

namespace Zumba\GastonJS\Tests;

/**
 * Class BrowserAuthenticationTest
 * @package Zumba\GastonJS\Tests\Server
 */
class BrowserAuthenticationTest extends BrowserCommandsTestCase {

  public function testAuthenticationFails() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/basic-auth-required/");
    $this->assertEquals(401, $this->browser->getStatusCode());
    $this->assertContains("NOT_AUTHORIZED", $this->browser->getBody());
  }

  public function testAuthenticationSuccess() {
    $this->browser->setHttpAuth("test", "test");
    $this->visitUrl($this->getTestPageBaseUrl() . "/basic-auth-required/");
    $this->assertEquals(200, $this->browser->getStatusCode());
    $this->assertContains("AUTHORIZATION_OK", $this->browser->getBody());
  }
}
