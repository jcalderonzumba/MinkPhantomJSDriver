<?php

namespace Behat\PhantomJSExtension\Driver;

use Behat\Mink\Driver\CoreDriver;
use Behat\Mink\Exception\DriverException;
use Behat\Mink\Session;
use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\Message\Request;
use JonnyW\PhantomJs\Message\Response;
use Symfony\Component\DomCrawler\Crawler;

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
  /** @var  string */
  private $baseUrl;
  /** @var  Session */
  private $session;
  /** @var  Crawler */
  private $crawler;

  /**
   * @param string $binLocation Location of the phantomjs binary
   * @param string $loaderLocation Location of the phantomjs loader
   * @param string $baseUrl base url for request
   */
  public function __construct($binLocation, $loaderLocation, $baseUrl) {
    $this->request = null;
    $this->headers = null;
    $this->response = null;
    $this->crawler = null;
    $this->pjsClient = Client::getInstance();
    $this->pjsClient->setPhantomJs($binLocation);
    $this->pjsClient->setPhantomLoader($loaderLocation);
    $this->baseUrl = $baseUrl;
  }

  /**
   * @return Crawler
   */
  protected function getCrawler() {
    return $this->crawler;
  }

  /**
   * @param Crawler $crawler
   */
  protected function setCrawler(Crawler $crawler) {
    $this->crawler = $crawler;
  }

  /**
   * @return Request
   */
  protected function getRequest() {
    return $this->request;
  }

  /**
   * @return Response
   */
  protected function getResponse() {
    return $this->response;
  }

  /**
   *  Adds the possible headers to the request
   */
  protected function addHeadersToRequest() {
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
   * Creates a crawler from a given content response
   * @param Response $response
   */
  protected function createCrawlerFromResponse(Response $response) {
    $crawler = new Crawler();
    $crawler->addContent($response->getContent(), $response->getContentType());
    $this->setCrawler($crawler);
  }

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
   * {@inheritdoc}
   * @param string $url
   * @throws DriverException
   */
  public function visit($url) {
    try {
      $this->request->setMethod("GET");
      $this->request->setUrl($url);
      $this->addHeadersToRequest();
      $this->pjsClient->send($this->request, $this->response);
      $this->createCrawlerFromResponse($this->response);
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
    //TODO: handle null value as DELETING THE COOKIE
    if (isset($this->headers["Cookie"])) {
      $this->headers["Cookie"] = sprintf("%s %s", $this->headers["Cookie"], $cookieHeader);
    } else {
      $this->headers["Cookie"] = $cookieHeader;
    }
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
   * @param $xpath
   * @return Crawler
   * @throws DriverException
   */
  protected function getFilteredCrawler($xpath) {
    if (!count($crawler = $this->getCrawler()->filterXPath($xpath))) {
      throw new DriverException(sprintf('There is no element matching XPath "%s"', $xpath));
    }

    return $crawler;
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
