<?php

namespace Zumba\GastonJS\Tests;

use Zumba\GastonJS\Exception\ObsoleteNode;

/**
 * Class BrowserPageContentTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserPageContentTest extends BrowserCommandsTestCase {

  public function testAllText() {
    try {
      $this->browser->allText(1, 0);
    } catch (ObsoleteNode $e) {
    }

    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->browser->find("xpath", '//*[@id="break"]');
    $this->assertEquals("Foo Bar", trim($this->browser->allText(1, 0)));
    $this->browser->find("xpath", '//*[@id="nav"]');
    $this->assertEquals("Home", trim($this->browser->allText(1, 1)));
    $this->browser->find("xpath", '/html/body');
    $text = trim($this->browser->allText(1, 2));
    //This could be done better, but for the moment is just like this..
    $this->assertContains("Home", $text);
    $this->assertContains("Link", $text);
    $this->assertContains("Foo Bar", $text);
    $this->assertContains("THIS SHOULD NOT BE SEEN", $text);
  }

  public function testVisibleText() {
    $this->testAllText();
    $this->assertEquals("Foo Bar", trim($this->browser->visibleText(1, 0)));
    $this->assertEquals("Home", trim($this->browser->visibleText(1, 1)));
    $text = trim($this->browser->visibleText(1, 2));
    $this->assertContains("Home", $text);
    $this->assertContains("Link", $text);
    $this->assertContains("Foo Bar", $text);
    $this->assertNotContains("THIS SHOULD NOT BE SEEN", $text);
  }

  public function testAllHtml() {
    try {
      $this->browser->allHtml(1, 0);
    } catch (ObsoleteNode $e) {
    }

    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->browser->find("xpath", '/html/body/ul');
    $innerHtml = trim($this->browser->allHtml(1, 0, "inner"));
    $outerHtml = trim($this->browser->allHtml(1, 0, "outer"));
    $html= '<li><a id="nav" href="/">Home</a></li>';
    $this->assertXmlStringEqualsXmlString($html, $innerHtml);
    $this->assertXmlStringEqualsXmlString("<ul>$html</ul>", $outerHtml);
    $this->assertEmpty($this->browser->allHtml(1, 0, "not_valid"));
  }
}
