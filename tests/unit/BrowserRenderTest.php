<?php

namespace Zumba\GastonJS\Tests;

/**
 * Class BrowserRenderTest
 * @package Zumba\GastonJS\Tests
 */
class BrowserRenderTest extends BrowserCommandsTestCase {

  public function testRenderBase64() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    //Check we get a string
    $stringData = $this->browser->renderBase64("png");
    $this->assertTrue(is_string($stringData));
    $binaryData = base64_decode($stringData, true);
    //now we check that the binary data is actually PNG
    $fileInfo = new \finfo(FILEINFO_MIME);
    $this->assertNotFalse($binaryData);
    $this->assertNotFalse(strstr($fileInfo->buffer($binaryData), "image/png"));
  }

  public function testRenderFile() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $tempFile = sprintf("%s/%d.png", sys_get_temp_dir(), time());
    $this->assertTrue($this->browser->render($tempFile));
    $this->assertFileExists($tempFile);
  }

  //TODO: Test properly the selection stuff and the paper size


}
