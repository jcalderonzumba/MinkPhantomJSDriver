<?php

namespace Behat\PhantomJSExtension\Portergeist\NetworkTraffic;

/**
 * Class Request
 * @package Behat\PhantomJSExtension\Portergeist\NetworkTraffic
 */
class Request {
  protected $data;
  protected $responseParts;

  /**
   * @param array $data
   * @param array $responseParts
   */
  public function __construct($data, $responseParts = null) {
    $this->data = $data;
    $this->responseParts = $responseParts;
  }

  /**
   * @return array
   */
  public function getResponseParts() {
    return $this->responseParts;
  }

  /**
   * @param array $responseParts
   */
  public function setResponseParts($responseParts) {
    $this->responseParts = $responseParts;
  }

  /**
   * Returns the url where the request is going to be made
   * @return string
   */
  public function getUrl() {
    //TODO: add isset maybe?
    return $this->data['url'];
  }

  /**
   * Returns the request method
   * @return string
   */
  public function getMethod() {
    return $this->data['method'];
  }

  /**
   * Gets the request headers
   * @return array
   */
  public function getHeaders() {
    //TODO: Check if the data is actually an array, else make it array and see implications
    return $this->data['headers'];
  }

  /**
   * Returns if exists the request time
   * @return \DateTime
   */
  public function getTime() {
    if (isset($this->data['date'])) {
      $requestTime = new \DateTime();
      $requestTime->setTimestamp(strtotime($this->data['date']));
    }
    return null;
  }

}
