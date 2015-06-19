<?php

namespace Zumba\GastonJS\Tests;

use Zumba\GastonJS\Browser\Browser;

/**
 * Class BrowserCommandsTestCase
 * @package Zumba\GastonJS\Tests
 */
class BrowserCommandsTestCase extends \PHPUnit_Framework_TestCase {

  const LOCAL_SERVER_HOSTNAME = "127.0.0.1";
  const LOCAL_SERVER_PORT = 6789;

  /** @var  Browser */
  protected $browser;
  /** @var  string */
  protected $testPageBaseUrl;

  protected function setUp() {
    $this->browser = new Browser("http://127.0.0.1:8510/");
    $this->browser->reset();
    $this->testPageBaseUrl = sprintf("http://%s:%d", BrowserCommandsTestCase::LOCAL_SERVER_HOSTNAME, BrowserCommandsTestCase::LOCAL_SERVER_PORT);
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
