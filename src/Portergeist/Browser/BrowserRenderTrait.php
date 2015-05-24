<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Trait BrowserRenderTrait
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
trait BrowserRenderTrait {
  /**
   * Check and fix render options
   * @param $options
   * @return mixed
   */
  protected function checkRenderOptions($options) {
    if (isset($options["full"]) && $options["full"] === true) {
      if (isset($options["selector"])) {
        $options["selector"] = null;
      }
    }
    if (!isset($options["full"])) {
      //if not defined then we assume true
      $options["full"] = true;
    }
    return $options;
  }

  /**
   * Renders a page or selection to a file given by path
   * @param string $path
   * @param array  $options
   * @return mixed
   */
  public function render($path, $options = array()) {
    $fixedOptions = $this->checkRenderOptions($options);
    return $this->command('render', $path, $fixedOptions["full"], $fixedOptions["selector"]);
  }

  /**
   * Renders base64 a page or selection to a file given by path
   * @param string $imageFormat (PNG, GIF, JPEG)
   * @param array  $options
   * @return mixed
   */
  public function renderBase64($imageFormat, $options = array()) {
    $fixedOptions = $this->checkRenderOptions($options);
    return $this->command('render_base64', $imageFormat, $fixedOptions["full"], $fixedOptions["selector"]);
  }

  /**
   * Sets the paper size, useful when saving to PDF
   * @param $paperSize
   * @return mixed
   */
  public function setPaperSize($paperSize) {
    return $this->command('set_paper_size', $paperSize);
  }
}
