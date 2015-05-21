<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Class BrowserScript
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
class BrowserScript extends BrowserMouseEvent {
  /**
   * Evaluates a script on the browser
   * @param $script
   * @return mixed
   */
  public function evaluate($script) {
    return $this->command('evaluate', $script);
  }

  /**
   * Executes a script on the browser
   * @param $script
   * @return mixed
   */
  public function execute($script) {
    return $this->command('execute', $script);
  }

  /**
   * Set whether to fail or not on javascript errors found on the page
   * @param bool $enabled
   * @return bool
   */
  public function jsErrors($enabled = true) {
    return $this->command('set_js_errors', $enabled);
  }

  /**
   * Add desired extensions to phantomjs
   * @param $extensions
   */
  public function extensions($extensions) {
    foreach ($extensions as $extensionName) {
      $this->command('add_extension', $extensionName);
    }
  }

}
