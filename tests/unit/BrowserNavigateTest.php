<?php

namespace Zumba\GastonJS\Tests;

/**
 * Class BrowserNavigateTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserNavigateTest extends BrowserCommandsTestCase {

  public function testBrowserVisit() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
  }

  public function testBrowserCurrentUrl() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $currentUrl = $this->browser->currentUrl();
    $this->assertEquals($this->getTestPageBaseUrl() . "/static/basic.html", $currentUrl);
  }

  public function testBrowserReload() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertTrue($this->browser->reload());
    $currentUrl = $this->browser->currentUrl();
    $this->assertEquals($this->getTestPageBaseUrl() . "/static/basic.html", $currentUrl);
  }

  public function testGoBack() {
    //We have a clean slate so the first try should say no
    $this->assertFalse($this->browser->goBack());
    $this->testBrowserVisit();
    //First visit still needs to be false
    $this->assertFalse($this->browser->goBack());
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/auth_ok.html");
    $this->assertTrue($this->browser->goBack());
    $this->assertEquals($this->getTestPageBaseUrl() . "/static/basic.html", $this->browser->currentUrl());
  }

  public function testGoForward() {
    //We have a clean slate so the first try should say no
    $this->assertFalse($this->browser->goForward());
    $this->testBrowserVisit();
    //Still can not go forward
    $this->assertFalse($this->browser->goForward());
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/auth_ok.html");
    //Now we can go back and forward
    $this->assertTrue($this->browser->goBack());
    $this->assertTrue($this->browser->goForward());
    $this->assertEquals($this->getTestPageBaseUrl() . "/static/auth_ok.html", $this->browser->currentUrl());
  }
}
