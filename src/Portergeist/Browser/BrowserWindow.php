<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Class BrowserWindow
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
class BrowserWindow extends BrowserScript {
  /**
   * TODO: Don't know yet what this command does
   * @return mixed
   */
  public function windowHandle() {
    return $this->command('window_handle');
  }

  /**
   * TODO: Don't know yet what this command does
   * @return mixed
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
   * TODO: Not sure how to use this stuff
   * @param $windowName
   * @return bool
   */
  public function withinWindow($windowName) {
    return false;
  }
}
