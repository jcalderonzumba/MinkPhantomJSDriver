<?php

namespace Behat\PhantomJSExtension\Portergeist\Exception;

/**
 * Class InvalidSelector
 * @package Behat\PhantomJSExtension\Portergeist\Exception
 */
class InvalidSelector extends ClientError {
  /**
   * Gets the method of selection
   * @return string
   */
  public function getMethod() {
    return $this->response["args"][0];
  }

  /**
   * Gets the selector related to the method
   * @return string
   */
  public function getSelector() {
    return $this->response["args"][1];
  }

  /**
   * @return string
   */
  public function message() {
    return "The browser raised a syntax error while trying to evaluate" . $this->getMethod() . " selector " . $this->getSelector();
  }
}
