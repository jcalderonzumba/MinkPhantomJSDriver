<?php

namespace Behat\PhantomJSExtension\Tests;

/**
 * Class BrowserNavigateTest
 * @package Behat\PhantomJSExtension\Tests
 */
class BrowserNavigateTest extends BrowserCommandsTestCase {

  public function testBrowserVisitCommand() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
  }

  public function testBrowserCurrentUrlCommand() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $currentUrl = $this->browser->currentUrl();
    $this->assertEquals($this->getTestPageBaseUrl() . "/static/basic.html", $currentUrl);
  }

  public function testBrowserReloadCommand() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertTrue($this->browser->reload());
    $currentUrl = $this->browser->currentUrl();
    $this->assertEquals($this->getTestPageBaseUrl() . "/static/basic.html", $currentUrl);
  }

  public function testGoBackCommand() {
    //We have a clean slate so the first try should say no
    $this->assertFalse($this->browser->goBack());
    $this->testBrowserVisitCommand();
    //First visit still needs to be false
    $this->assertFalse($this->browser->goBack());
    $this->visitUrl("http://www.juan.ec");
    $this->assertTrue($this->browser->goBack());
    $this->assertEquals($this->getTestPageBaseUrl() . "/static/basic.html", $this->browser->currentUrl());
  }

  public function testGoForwardCommand() {
    //We have a clean slate so the first try should say no
    $this->assertFalse($this->browser->goForward());
    $this->testBrowserVisitCommand();
    //Still can not go forward
    $this->assertFalse($this->browser->goForward());
    $this->visitUrl("http://www.juan.ec/");
    //Now we can go back and forward
    $this->assertTrue($this->browser->goBack());
    $this->assertTrue($this->browser->goForward());
    $this->assertEquals("http://www.juan.ec/", $this->browser->currentUrl());
  }
}
