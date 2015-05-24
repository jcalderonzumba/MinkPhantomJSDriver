<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Trait BrowserFrameTrait
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
trait BrowserFrameTrait {
  /**
   * Back to the parent of the iframe if possible
   * @return mixed
   * @throws \Behat\PhantomJSExtension\Portergeist\Exception\BrowserError
   * @throws \Exception
   */
  public function popFrame() {
    return $this->command("pop_frame");
  }

  /**
   * Goes into the iframe to do stuff
   * @param string $name
   * @param int    $timeout
   * @return mixed
   * @throws \Behat\PhantomJSExtension\Portergeist\Exception\BrowserError
   * @throws \Exception
   */
  public function pushFrame($name, $timeout = null) {
    return $this->command("push_frame", $name, $timeout);
  }
}
