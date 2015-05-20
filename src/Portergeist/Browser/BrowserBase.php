<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

use Behat\PhantomJSExtension\Portergeist\Client;
use Behat\PhantomJSExtension\Portergeist\Exception\BrowserError;
use Behat\PhantomJSExtension\Portergeist\Server;

/**
 * Class BrowserBase
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
class BrowserBase {
  /** @var Server */
  protected $server;
  /** @var Client */
  protected $client;
  /** @var mixed */
  protected $logger;
  /** @var  bool */
  protected $debug;

  /**
   * @return Client
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * @return mixed
   */
  public function getLogger() {
    return $this->logger;
  }

  /**
   * @return Server
   */
  public function getServer() {
    return $this->server;
  }

  /**
   * Restarts the browser
   */
  public function restart() {
    //TODO: check who can work properly if needed
    $this->getServer()->restart();
    $this->getClient()->restart();
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
      //TODO: log message here
      $response = $this->getServer()->send($messageToSend);
      if (($jsonResponse = json_decode($response, true)) === null) {
        //TODO: add a proper exception class
        throw new \Exception("Could not decode the phantomjs server response");
      }
    } catch (\Exception $e) {
      //$this->restart();
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
