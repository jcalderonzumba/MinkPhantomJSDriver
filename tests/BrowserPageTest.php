<?php
namespace Behat\PhantomJSExtension\Tests;

/**
 * Class BrowserPageTest
 * @package Behat\PhantomJSExtension\Tests
 */
class BrowserPageTest extends BrowserCommandsTestCase {

  public function testGetStatusCodeNoPage() {
    try {
      $this->browser->getStatusCode();
    } catch (\Exception $e) {
      $this->assertInstanceOf("Behat\\PhantomJSExtension\\Portergeist\\Exception\\StatusFailError", $e);
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
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $pageDom = new \DOMDocument();
    $pageDom->loadHTML($this->browser->getBody());
    $pageDom->preserveWhiteSpace = false;
    $this->assertXmlStringEqualsXmlString($expectedDom->saveHTML(), $pageDom->saveHTML());
  }

  public function testGetSourceNoPage() {
    $this->assertNull($this->browser->getSource());
  }

  public function testGetSourcePage() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertXmlStringEqualsXmlFile(sprintf("%s/Server/www/web/static/basic.html", realpath(__DIR__)), $this->browser->getSource());
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
