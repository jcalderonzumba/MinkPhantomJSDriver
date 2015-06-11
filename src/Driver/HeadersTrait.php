<?php

namespace Behat\PhantomJSExtension\Driver;

/**
 * Class HeadersTrait
 * @package Behat\PhantomJSExtension\Driver
 */
trait HeadersTrait {

  /**
   * Gets the current request response headers
   * Should be called only after a request, other calls are undefined behaviour
   * @return array
   */
  public function getResponseHeaders() {
    //TODO: Check the output form of this response and fix it to suit the driver needs
    return $this->browser->responseHeaders();
  }

  /**
   * Current request status code response
   * @return int
   */
  public function getStatusCode() {
    return $this->browser->getStatusCode();
  }

  /**
   * The name say its all
   * @param string $name
   * @param string $value
   */
  public function setRequestHeader($name, $value) {
    $header = array();
    $header[$name] = $value;
    //TODO: as a limitation of the driver it self, we will send permanent for the moment
    $this->browser->addHeader($header, true);
  }

}
