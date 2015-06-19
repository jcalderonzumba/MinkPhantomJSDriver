<?php
namespace Zumba\GastonJS\Tests;

/**
 * Class BrowserPageTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserPageTest extends BrowserCommandsTestCase {

  public function testGetStatusCodeNoPage() {
    try {
      $this->browser->getStatusCode();
    } catch (\Exception $e) {
      $this->assertInstanceOf("Zumba\\GastonJS\\Exception\\StatusFailError", $e);
    }
  }

  public function testGetStatusCodePage() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertEquals(200, $this->browser->getStatusCode());
    $this->visitUrl($this->getTestPageBaseUrl() . "/this_does_not_exists");
    $this->assertEquals(404, $this->browser->getStatusCode());
  }

  public function testGetBodyNoPage() {
    $expectedBody = "<html><head></head><body></body></html>";
    $this->assertEquals($expectedBody, $this->browser->getBody());
  }

  public function testGetBodyPage() {
    $htmlFile = sprintf("%s/Server/www/web/static/basic.html", realpath(__DIR__));
    $expectedDom = new \DOMDocument();
    $expectedDom->loadHTMLFile($htmlFile);
    $expectedDom->preserveWhiteSpace = false;
    $expectedDom->formatOutput = true;

    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $pageDom = new \DOMDocument();
    $pageDom->loadHTML($this->browser->getBody());
    $pageDom->preserveWhiteSpace = false;
    $pageDom->formatOutput = true;

    $this->assertXmlStringEqualsXmlString($pageDom->saveXML(), $expectedDom->saveXML());
  }

  public function testGetSourceNoPage() {
    $this->assertNull($this->browser->getSource());
  }

  public function testGetSourcePage() {
    $htmlFile = sprintf("%s/Server/www/web/static/basic.html", realpath(__DIR__));
    $expectedDom = new \DOMDocument();
    $expectedDom->loadHTMLFile($htmlFile);
    $expectedDom->preserveWhiteSpace = false;
    $expectedDom->formatOutput = true;

    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $pageDom = new \DOMDocument();
    $pageDom->loadHTML($this->browser->getSource());
    $pageDom->preserveWhiteSpace = false;
    $pageDom->formatOutput = true;

    $this->assertXmlStringEqualsXmlString($pageDom->saveXML(), $expectedDom->saveXML());
  }

  public function testGetTitle() {
    $this->assertEmpty($this->browser->getTitle());
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertEquals("Test", $this->browser->getTitle());
  }

  public function testReset() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertTrue($this->browser->reset());
    $this->testGetStatusCodeNoPage();
    $this->testGetBodyNoPage();
    $this->testGetSourceNoPage();
    //TODO: increase reset tests by testing for example cookies
  }


}
