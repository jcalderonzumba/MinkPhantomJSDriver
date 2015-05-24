<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

use Behat\PhantomJSExtension\Portergeist\Exception\BrowserError;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

/**
 * Class BrowserBase
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
class BrowserBase {
  /** @var mixed */
  protected $logger;
  /** @var  bool */
  protected $debug;
  /** @var  string */
  protected $phantomJSHost;
  /** @var  Client */
  protected $apiClient;

  /**
   *  Creates an http client to consume the phantomjs API
   */
  protected function createApiClient() {
    $this->apiClient = new Client(array("base_url" => $this->getPhantomJSHost()));
  }

  /**
   * TODO: not sure how to do the normalizeKeys stuff fix when needed
   * @param $keys
   * @return mixed
   */
  protected function normalizeKeys($keys) {
    return $keys;
  }

  /**
   * @return Client
   */
  public function getApiClient() {
    return $this->apiClient;
  }

  /**
   * @return string
   */
  public function getPhantomJSHost() {
    return $this->phantomJSHost;
  }

  /**
   * @return mixed
   */
  public function getLogger() {
    return $this->logger;
  }

  /**
   * Restarts the browser
   */
  public function restart() {
    //TODO: Do we really need to do this?, we are just a client
  }

  /**
   * Sends a command to the browser
   * @throws BrowserError
   * @throws \Exception
   * @return mixed
   */
  public function command() {
    try {
      $args = func_get_args();
      $commandName = $args[0];
      array_shift($args);
      $messageToSend = json_encode(array('name' => $commandName, 'args' => $args));
      /** @var $commandResponse  Response */
      $commandResponse = $this->getApiClient()->post("/api", array("body" => $messageToSend));
      $jsonResponse = $commandResponse->json(array("object" => false));
    } catch (\Exception $e) {
      throw $e;
    }
    if (isset($jsonResponse['error'])) {
      //TODO: check that this actually works
      throw $this->getErrorClass($jsonResponse);
    }
    return $jsonResponse['response'];
  }

  /**
   * @param $error
   * @return BrowserError
   */
  protected function getErrorClass($error) {
    $errorClassMap = array(
      'Poltergeist.JavascriptError'   => "JavascriptError",
      'Poltergeist.FrameNotFound'     => "FrameNotFound",
      'Poltergeist.InvalidSelector'   => "InvalidSelector",
      'Poltergeist.StatusFailError'   => "StatusFailError",
      'Poltergeist.NoSuchWindowError' => "NoSuchWindowError"
    );

    if (isset($error['error']['name']) && isset($errorClassMap[$error["error"]["name"]])) {
      return new $errorClassMap[$error["error"]["name"]]($error);
    }

    return new BrowserError($error);
  }
}
