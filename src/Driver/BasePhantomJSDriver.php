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
 * Class BasePhantomJSDriver
 * @package Behat\PhantomJSExtension\Driver
 */
class BasePhantomJSDriver extends CoreDriver {

  /** @var Request */
  protected $request;
  /** @var Client */
  protected $pjsClient;
  /** @var  Response */
  protected $response;
  /** @var   array */
  protected $headers;
  /** @var  string */
  protected $baseUrl;
  /** @var  Crawler */
  protected $crawler;
  /** @var  Session */
  protected $session;

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
   * @return Client
   */
  protected function getPjsClient() {
    return $this->pjsClient;
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
   * Returns the redirect location since response->getRedirectUrl seems not to work..
   * @return string
   */
  protected function getRedirectUrl() {
    return trim($this->response->getHeader("Location"));
  }

  /**
   * @param $cookieHeader
   */
  protected function addCookieToHeaders($cookieHeader) {
    if (isset($this->headers["Cookie"])) {
      $this->headers["Cookie"] = sprintf("%s %s", $this->headers["Cookie"], $cookieHeader);
    } else {
      $this->headers["Cookie"] = $cookieHeader;
    }
  }

  /**
   * If we have a response that sends cookies, we will add them to the headers we have
   */
  protected function addCookiesFromResponse() {
    //TODO: Overlapping cookies, we should update them not just add another
    $cookies = $this->response->getHeader("Set-Cookie");
    if (!empty($cookies)) {
      $regexp = "#^(([^=]+)=([^;]+))#";
      if (preg_match($regexp, $cookies, $match) === 1) {
        $cookie = "{$match[1]};";
        $this->addCookieToHeaders($cookie);
      }
    }
  }

  /**
   *  Adds the possible headers to the request
   */
  protected function addHeadersToRequest() {
    //Header management
    if ($this->headers !== null && count($this->headers) > 0) {
      foreach ($this->headers as $headerName => $headerValue) {
        $this->request->addHeader($headerName, $headerValue);
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
}
