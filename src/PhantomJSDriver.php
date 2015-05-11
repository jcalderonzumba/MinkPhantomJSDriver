<?php

namespace Behat\Mink\Driver;

use Behat\Mink\Exception\DriverException;
use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\Message\Request;
use JonnyW\PhantomJs\Message\Response;

/**
 * Class PhantomJSDriver
 * @package Behat\Mink\Driver
 */
class PhantomJSDriver extends CoreDriver {

  /** @var Request */
  private $request;
  /** @var Client */
  private $pjsClient;
  /** @var  Response */
  private $response;
  /** @var   array */
  private $headers;

  /**
   * Constructor for the Mink Driver
   */
  public function __construct() {
    $this->request = null;
    $this->headers = null;
    $this->response = null;
    $this->pjsClient = Client::getInstance();
    $this->pjsClient->setPhantomJs("../bin/phantomjs");
    $this->pjsClient->setPhantomLoader("../bin/phantomloader");
  }

  /**
   * {@inheritdoc}
   */
  public function start() {
    if ($this->request === null) {
      $this->request = $this->pjsClient->getMessageFactory()->createRequest();
      $this->response = $this->pjsClient->getMessageFactory()->createResponse();
    }
  }

  /**
   * {@inheritdoc}
   * @return bool
   */
  public function isStarted() {
    return ($this->request !== null && $this->request instanceof Request);
  }

  /**
   * @{inheritdoc}
   */
  public function stop() {
    //TODO: If we need to we should CLEAR ALL COOKIES
    $this->request = null;
    $this->response = null;
    $this->headers = null;
  }

  /**
   * {@inheritdoc}
   */
  public function reset() {
    //TODO: probably this needs to be revisited in the whole phantomjs way of working...
    //This is a soft reset, while stop should be a hard reset
    $this->stop();
  }

  /**
   *  Adds the possible headers to the request
   */
  private function addHeadersToRequest() {
    //Header management
    if ($this->headers !== null && count($this->headers) > 0) {
      foreach ($this->headers as $headerName => $headerValues) {
        if (is_array($headerValues)) {
          foreach ($headerValues as $headerValue) {
            $this->request->addHeader($headerName, $headerValue);
          }
        } else {
          $this->request->addHeader($headerName, $headerValues);
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   * @param string $url
   * @throws DriverException
   */
  public function visit($url) {
    try {
      //TODO: add the support for headers
      $this->request->setMethod("GET");
      $this->request->setUrl($url);
      $this->addHeadersToRequest();
      $this->pjsClient->send($this->request, $this->response);
    } catch (\Exception $e) {
      throw new DriverException("VISIT_EXCEPTION", -1, $e);
    }
  }

  /**
   * {@inheritdoc}
   * @return string
   */
  public function getContent() {
    return $this->response->getContent();
  }

  /**
   * {@inheritdoc}
   * @return array
   */
  public function getResponseHeaders() {
    return $this->response->getHeaders();
  }

  /**
   * {@inheritdoc}
   * @param string $name
   * @param string $value
   */
  public function setCookie($name, $value = null) {
    $cookieHeader = sprintf("%s=%s; path=/", $name, $value);
    $this->headers["Set-Cookie"][] = $cookieHeader;
  }

  /**
   * {@inheritdoc}
   * @return int
   */
  public function getStatusCode() {
    return $this->response->getStatus();
  }

  /**
   * {@inheritdoc}
   * @return string
   */
  public function getCurrentUrl() {
    if ($this->response->isRedirect()) {
      return $this->response->getRedirectUrl();
    }
    return $this->response->getUrl();
  }

  /**
   * {@inheritdoc}
   * @throws DriverException
   */
  public function reload() {
    $this->visit($this->getCurrentUrl());
  }

  /**
   * {@inheritdoc}
   * @param string $name
   * @param string $value
   */
  public function setRequestHeader($name, $value) {
    $this->headers[$name] = $value;
  }

  /**
   * {@inheritdoc}
   * @param string $user
   * @param string $password
   */
  public function setBasicAuth($user, $password) {
    $value = base64_encode(sprintf("%s:%s", $user, $password));
    $this->headers["Authorization"] = sprintf("Basic $value");
  }

}
