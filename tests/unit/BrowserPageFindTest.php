<?php
namespace Zumba\GastonJS\Tests;

use Zumba\GastonJS\Exception\BrowserError;
use Zumba\GastonJS\Exception\InvalidSelector;
use Zumba\GastonJS\Exception\ObsoleteNode;

/**
 * Class BrowserPageFindTest
 * @package Zumba\GastonJS\Tests
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
      $this->assertInstanceOf("Zumba\\GastonJS\\Exception\\ObsoleteNode", $e);
    }
  }

  public function testFindWithinElementPage() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $this->browser->find("css", "#form_1014473");
    $withinElement = $this->browser->findWithin(1, 0, "css", "div");
    $this->assertCount(4, $withinElement);
  }

  public function testGetParents() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $this->browser->find("xpath", '//*[@id="form_1014473"]');
    $this->assertArraySubset(array(1, 2, 3), $this->browser->getParents(1, 0));
  }

  public function testTagName() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $this->browser->find("xpath", '//*[@id="form_1014473"]');
    $this->assertEquals("form", $this->browser->tagName(1, 0));
  }

  public function testEquals() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    try {
      $this->browser->equals(1, 0, 1);
    } catch (ObsoleteNode $e) {
    }
    //TODO: equals method seems to be broken or i do not know how to use it
  }

  public function testIsVisible() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->browser->find("xpath", '//*[@id="break"]');
    $this->assertTrue($this->browser->isVisible(1, 0));
    $this->browser->find("xpath", '/html/body/p[1]');
    $this->assertFalse($this->browser->isVisible(1, 1));
  }

  public function testIsDisabled() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->browser->find("xpath", '//*[@id="disabled_check"]');
    $this->browser->find("xpath", '//*[@id="enabled_check"]');
    $this->assertTrue($this->browser->isDisabled(1, 0));
    $this->assertFalse($this->browser->isDisabled(1, 1));
  }
}
