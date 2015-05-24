<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Trait BrowserScriptTrait
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
trait BrowserScriptTrait {
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
   * Add desired extensions to phantomjs
   * @param $extensions
   */
  public function extensions($extensions) {
    foreach ($extensions as $extensionName) {
      $this->command('add_extension', $extensionName);
    }
  }

}
