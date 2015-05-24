<?php

namespace Behat\PhantomJSExtension\Tests;

use Behat\PhantomJSExtension\Portergeist\Browser\Browser;

/**
 * Class BrowserCommandsTestCase
 * @package Behat\PhantomJSExtension\Tests
 */
class BrowserCommandsTestCase extends \PHPUnit_Framework_TestCase {
  /** @var  Browser */
  protected $browser;

  /** @var  string */
  protected $testPageBaseUrl;

  protected function setUp() {
    $this->browser = new Browser("http://127.0.0.1:8510/");
    $this->browser->reset();
    $this->testPageBaseUrl = "http://127.0.0.1:6789";
  }

  /**
   * Helper to visit a specific url
   * @param string $url
   */
  protected function visitUrl($url) {
    $this->assertNotEmpty($url);
    $cmdResponse = $this->browser->visit($url);
    $this->assertTrue(is_array($cmdResponse), true);
    $this->assertEquals("success", $cmdResponse["status"]);
  }

  /**
   * @return string
   */
  public function getTestPageBaseUrl() {
    return $this->testPageBaseUrl;
  }

}
