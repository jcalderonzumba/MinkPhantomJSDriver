<?php
namespace Behat\PhantomJSExtension\Portergeist\NetworkTraffic;

/**
 * Class Response
 * @package Behat\PhantomJSExtension\Portergeist\NetworkTraffic
 */
class Response {
  protected $data;

  /**
   * @param $data
   */
  public function __construct($data) {
    $this->data = $data;
  }

  /**
   * Gets Response url
   * @return string
   */
  public function getUrl() {
    return $this->data['url'];
  }

  /**
   * Gets the response status code
   * @return int
   */
  public function getStatus() {
    return intval($this->data['status']);
  }

  /**
   * Gets the status text of the response
   * @return string
   */
  public function getStatusText() {
    return $this->data['statusText'];
  }

  /**
   * Gets the response headers
   * @return array
   */
  public function getHeaders() {
    //TODO: Check if the data is actually an array, else make it array and see implications
    return $this->data['headers'];
  }

  /**
   * Get redirect url if response is a redirect
   * @return string
   */
  public function getRedirectUrl() {
    if (isset($this->data['redirectUrl']) && !empty($this->data['redirectUrl'])) {
      return $this->data['redirectUrl'];
    }
    return null;
  }

  /**
   * Returns the size of the response body
   * @return int
   */
  public function getBodySize() {
    if (isset($this->data['bodySize'])) {
      return intval($this->data['bodySize']);
    }
    return 0;
  }

  /**
   * Returns the content type of the response
   * @return string
   */
  public function getContentType() {
    if (isset($this->data['contentType'])) {
      return $this->data['contentType'];
    }
    return null;
  }

  /**
   * Returns if exists the response time
   * @return \DateTime
   */
  public function getTime() {
    if (isset($this->data['date'])) {
      $responseTime = new \DateTime();
      $responseTime->setTimestamp(strtotime($this->data['date']));
    }
    return null;
  }
}
