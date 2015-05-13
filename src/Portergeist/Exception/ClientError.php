<?php

namespace Behat\PhantomJSExtension\Portergeist\Exception;

/**
 * Class ClientError
 * @package Behat\PhantomJSExtension\Exception
 */
class ClientError extends \Exception {

  protected $response;

  /**
   * @param string $response
   */
  public function __construct($response) {
    $this->response = $response;
  }
}
