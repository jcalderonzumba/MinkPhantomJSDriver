<?php

namespace Behat\PhantomJSExtension\Driver;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Session;
use JonnyW\PhantomJs\Message\Request;

/**
 * Class PhantomJSDriver
 * @package Behat\Mink\Driver
 */
class PhantomJSDriver extends BasePhantomJSDriver {

  /**
   * {@inheritdoc}
   * @param Session $session
   */
  public function setSession(Session $session) {
    $this->session = $session;
  }

  /**
   * {@inheritdoc}
   */
  public function start() {
    if ($this->getRequest() === null) {
      $this->request = $this->getPjsClient()->getMessageFactory()->createRequest();
      $this->response = $this->getPjsClient()->getMessageFactory()->createResponse();
    }
  }

  /**
   * {@inheritdoc}
   * @return bool
   */
  public function isStarted() {
    return ($this->getRequest() !== null && $this->getRequest() instanceof Request);
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
   * {@inheritdoc}
   * @param string $url
   * @throws DriverException
   * @return boolean
   */
  public function visit($url) {
    try {
      $request = $this->getRequest();
      $response = $this->getResponse();

      $request->setMethod("GET");
      $request->setUrl($url);

      $this->addHeadersToRequest();
      $this->getPjsClient()->send($request, $response);

      if ($response->isRedirect()) {
        return $this->visit($this->getRedirectUrl());
      }

      $this->createCrawlerFromResponse($this->getResponse());
      return true;
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
    $cookieHeader = sprintf("%s=%s;", $name, $value);
    //TODO: handle null value as DELETING THE COOKIE
    $this->addCookieToHeaders($cookieHeader);
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

  /**
   * {@inheritdoc}
   * @param string $xpath
   * @return string
   */
  public function getText($xpath) {
    $text = $this->getFilteredCrawler($xpath)->text();
    $text = str_replace("\n", ' ', $text);
    $text = preg_replace('/ {2,}/', ' ', $text);

    return trim($text);
  }

}
