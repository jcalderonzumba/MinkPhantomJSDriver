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

  protected function createApiClient() {
    $this->apiClient = new Client(array("base_url" => $this->getPhantomJSHost()));
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
    //TODO: check who can work properly if needed
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
