<?php

namespace Behat\PhantomJSExtension\Portergeist\Exception;


/**
 * Class DeadClient
 * @package Behat\PhantomJSExtension\Portergeist\Exception
 */
class DeadClient extends \Exception {
  /**
   * @param string $message
   */
  public function __construct($message) {
    $errorMessage = "PhantomJS client died while processing $message";
    parent::__construct($errorMessage);
  }
}
