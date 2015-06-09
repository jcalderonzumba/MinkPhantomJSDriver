<?php

namespace Behat\PhantomJSExtension\Driver;
use Behat\Mink\Exception\DriverException;

/**
 * Class WindowTrait
 * @package Behat\PhantomJSExtension\Driver
 */
trait WindowTrait {
  /**
   * Returns the current page window name
   * @return string
   */
  public function getWindowName() {
    return $this->browser->windowName();
  }

  /**
   * Return all the window handles currently present in phantomjs
   * @return array
   */
  public function getWindowNames() {
    return $this->browser->windowHandles();
  }

  /**
   * Switches to window by name if possible
   * @param $name
   */
  public function switchToWindow($name = null) {
    if ($name === null) {
      //Nothing to do, we stay on the window we are in
      return;
    }
    //TODO: this stuff throws error on browser.js so check it when testing
    $this->browser->switchToWindow($name);
  }

  /**
   * Resizing a window with specified size
   * @param int    $width
   * @param int    $height
   * @param string $name
   * @throws DriverException
   */
  public function resizeWindow($width, $height, $name = null) {
    if ($name !== null) {
      //TODO: add this on the phantomjs stuff
      throw new DriverException("Resizing other window than the main one is not supported yet");
    }
    $this->browser->resize($width, $height);
  }

}
