<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Class BrowserWindowTrait
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
trait BrowserWindowTrait {
  /**
   * Returns the current window handle name in the browser
   * @return mixed
   */
  public function windowHandle() {
    return $this->command('window_handle');
  }

  /**
   * Returns all the window handles present in the browser
   * @return array
   */
  public function windowHandles() {
    return $this->command('window_handles');
  }

  /**
   * Change the browser focus to another window
   * @param $windowName
   * @return mixed
   */
  public function switchToWindow($windowName) {
    return $this->command('switch_to_window', $windowName);
  }

  /**
   * Opens a new window on the browser
   * @return mixed
   */
  public function openNewWindow() {
    return $this->command('open_new_window');
  }

  /**
   * Closes a window on the browser by a given name
   * @param $windowName
   * @return mixed
   */
  public function closeWindow($windowName) {
    return $this->command('close_window', $windowName);
  }

  /**
   * Gets the current request window name
   * @return string
   * @throws \Behat\PhantomJSExtension\Portergeist\Exception\BrowserError
   * @throws \Exception
   */
  public function windowName() {
    return $this->command('window_name');
  }

  /**
   * Zoom factor for a web page
   * @param $zoomFactor
   * @return mixed
   */
  public function setZoomFactor($zoomFactor) {
    return $this->command('set_zoom_factor', $zoomFactor);
  }

}
