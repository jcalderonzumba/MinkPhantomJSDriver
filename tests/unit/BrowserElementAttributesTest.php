<?php

namespace Zumba\GastonJS\Tests;


/**
 * Class BrowserElementAttributesTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserElementAttributesTest extends BrowserCommandsTestCase {

  public function testAttributes() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $this->browser->find("xpath", '//*[@id="element_3"]');
    $attributes = $this->browser->attributes(1, 0);
    $this->assertCount(3, $attributes);
    $this->assertArraySubset(array("class" => "element select medium", "id" => "element_3", "name" => "element_3"), $attributes);
  }

  public function testAttribute() {
    $this->testAttributes();
    $this->assertEquals("element select medium", $this->browser->attribute(1, 0, "class"));
    $this->assertEquals("element_3", $this->browser->attribute(1, 0, "id"));
    $this->assertEquals("element_3", $this->browser->attribute(1, 0, "name"));
  }

  public function testSetAttribute() {
    $this->testAttributes();
    $this->assertTrue($this->browser->setAttribute(1, 0, "class", "element select"));
    $attributes = $this->browser->attributes(1, 0);
    $this->assertCount(3, $attributes);
    $this->assertArraySubset(array("class" => "element select", "id" => "element_3", "name" => "element_3"), $attributes);
  }

  public function testRemoveAttribute(){
    $this->testAttributes();
    $this->assertTrue($this->browser->removeAttribute(1, 0, "THIS_DOES_NOT_EXISTS"));
    $this->assertTrue($this->browser->removeAttribute(1, 0, "class"));
    $attributes = $this->browser->attributes(1, 0);
    $this->assertCount(2, $attributes);
    $this->assertArraySubset(array("id" => "element_3", "name" => "element_3"), $attributes);
  }

}
