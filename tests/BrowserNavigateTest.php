<?php

namespace Behat\PhantomJSExtension\Tests;

use Behat\PhantomJSExtension\Portergeist\Browser\Browser;

/**
 * Class BrowserNavigateTest
 * @package Behat\PhantomJSExtension\Tests
 */
class BrowserNavigateTest extends \PHPUnit_Framework_TestCase {

  /** @var  Browser */
  protected $browser;

  protected function setUp() {
    $this->browser = new Browser("http://127.0.0.1:8510/");
    $this->browser->reset();
  }

  /**
   * Helper to visit a specific url
   * @param string $url
   */
  protected function visitUrl($url = "http://localhost:6789/basic.html") {
    $cmdResponse = $this->browser->visit($url);
    $this->assertTrue(is_array($cmdResponse), true);
    $this->assertEquals("success", $cmdResponse["status"]);
  }

  public function testBrowserVisitCommand() {
    $this->visitUrl();
  }

  public function testBrowserCurrentUrlCommand() {
    $this->visitUrl("http://localhost:6789/basic.html");
    $currentUrl = $this->browser->currentUrl();
    $this->assertEquals("http://localhost:6789/basic.html", $currentUrl);
  }

  public function testBrowserReloadCommand() {
    $this->visitUrl("http://localhost:6789/basic.html");
    $this->assertTrue($this->browser->reload());
    $currentUrl = $this->browser->currentUrl();
    $this->assertEquals("http://localhost:6789/basic.html", $currentUrl);
  }

  public function testGoBackCommand() {
    //We have a clean slate so the first try should say no
    $this->assertFalse($this->browser->goBack());
    $this->testBrowserVisitCommand();
    //First visit still needs to be false
    $this->assertFalse($this->browser->goBack());
    $this->visitUrl("http://www.juan.ec");
    $this->assertTrue($this->browser->goBack());
    $this->assertEquals("http://localhost:6789/basic.html", $this->browser->currentUrl());
  }

  public function testGoForwardCommand() {
    //We have a clean slate so the first try should say no
    $this->assertFalse($this->browser->goForward());
    $this->testBrowserVisitCommand("http://localhost:6789/basic.html");
    //Still can not go forward
    $this->assertFalse($this->browser->goForward());
    $this->visitUrl("http://www.juan.ec/");
    //Now we can go back and forward
    $this->assertTrue($this->browser->goBack());
    $this->assertTrue($this->browser->goForward());
    $this->assertEquals("http://www.juan.ec/", $this->browser->currentUrl());
  }
}
