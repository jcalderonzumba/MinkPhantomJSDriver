<?php

namespace Zumba\GastonJS\Tests;

/**
 * Class BrowserFileTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserFileTest extends BrowserCommandsTestCase {

  public function testFileUpload() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/test/standard_form/form.html");
    $fileLocation = sprintf("%s/Server/www/web/test/standard_form/image_for_test.png", __DIR__);
    $this->assertCount(2, $this->browser->find("xpath", '//*[@id="element_2"]'));
    $this->assertTrue($this->browser->selectFile(1, 0, $fileLocation));
    $this->assertCount(2, $this->browser->find("xpath", '//*[@id="form_1014473"]'));
    $this->assertEquals("submit", $this->browser->trigger(1, 1, "submit"));
    //STUFF works like that, lets give 2 seconds to the browser for the page to load
    sleep(2);
    $requestResponse = json_decode(strip_tags($this->browser->getBody()), true);
    $expectedResponse = array("image_for_test.png" => array("file_name" => "image_for_test.png", "is_valid" => 1, "mime_type" => "image/png"));
    $this->assertArraySubset($expectedResponse, $requestResponse["files"]);
  }
}
