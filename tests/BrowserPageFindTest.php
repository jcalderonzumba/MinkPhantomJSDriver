<?php
namespace Behat\PhantomJSExtension\Tests;

use Behat\PhantomJSExtension\Portergeist\Exception\InvalidSelector;

/**
 * Class BrowserPageFindTest
 * @package Behat\PhantomJSExtension\Tests
 */
class BrowserPageFindTest extends BrowserCommandsTestCase {

  public function testFindElementNoPage() {
    $notFound = $this->browser->find("xpath", '//*[@id="form_1014473"]');
    $this->assertEquals(0, $notFound["page_id"]);
  }

  public function testFindInvalidSelector() {
    $selector = "xpath";
    $invalidSelection = '//*INVALID_SELECTOR[@id="form_1014473"]';
    try {
      $this->browser->find($selector, $invalidSelection);
    } catch (InvalidSelector $e) {
      $this->assertEquals($selector, $e->getMethod());
      $this->assertEquals($invalidSelection, $e->getSelector());
    }
  }

  public function testFindElementPage() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $element = $this->browser->find("xpath", '//*[@id="form_1014473"]');
    $this->assertEquals(1, $element["page_id"]);
    $this->assertEquals(0, $element["ids"][0]);
    $cssElement = $this->browser->find("css", "#form_1014473 > div");
    $this->assertEquals(1, $cssElement["page_id"]);
    $this->assertEquals(1, $cssElement["ids"][0]);
  }

  public function testFindWithinElementNoPage() {
    try {
      $this->browser->findWithin(1, 0, "xpath", '//*[@id="li_1"]');
    } catch (\Exception $e) {
      $this->assertInstanceOf("Behat\\PhantomJSExtension\\Portergeist\\Exception\\ObsoleteNode", $e);
    }
  }

  public function testFindWithinElementPage() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $this->browser->find("css", "#form_1014473");
    $withinElement = $this->browser->findWithin(1, 0, "css", "div");
    $this->assertCount(4, $withinElement);
  }
}
