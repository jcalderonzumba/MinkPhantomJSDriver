<?php

namespace Zumba\GastonJS\Tests;

/**
 * Class BrowserWindowTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserWindowTest extends BrowserCommandsTestCase {

  public function testWindowHandleNoPage() {
    $this->assertEquals(0, $this->browser->windowHandle());
  }

  public function testWindowHandlePage() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertEquals(0, $this->browser->windowHandle());
  }

  public function testWindowNameNoPage() {
    $this->assertEmpty($this->browser->windowName());
  }

  public function testWindowNamePage() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertEquals("BASIC_WINDOW", $this->browser->windowName());
  }

  public function testCloseWindow() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertEquals(0, $this->browser->windowHandle());
    $this->browser->closeWindow("0");
    $this->assertNull($this->browser->windowHandle());
  }

  public function testWindowHandlesNoPage() {
    $handles = $this->browser->windowHandles();
    $this->assertCount(1, $handles);
    $this->assertEquals($handles[0], "0");
  }

  public function testOpenNewWindow() {
    $this->assertTrue($this->browser->openNewWindow());
    $this->assertEquals("about:blank", $this->browser->currentUrl());
  }

  public function testWindowHandlesPage() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->browser->openNewWindow();
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/auth_ok.html");
    $this->assertCount(2, $this->browser->windowHandles());
  }

  public function testSwitchToWindow() {
    $this->testWindowHandlesPage();
    $this->assertEquals(0, $this->browser->windowHandle());
    $this->assertTrue($this->browser->switchToWindow("1"));
    $this->assertEquals(1, $this->browser->windowHandle());
  }
}
