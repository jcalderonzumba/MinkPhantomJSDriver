<?php

namespace Behat\PhantomJSExtension\Driver;

use Behat\Mink\Driver\CoreDriver;
use Behat\Mink\Exception\DriverException;
use Behat\Mink\Session;
use Behat\PhantomJSExtension\Portergeist\Browser\Browser;
use Behat\PhantomJSExtension\Portergeist\Client;
use Behat\PhantomJSExtension\Portergeist\Server;

/**
 * Class BasePhantomJSDriver
 * @package Behat\PhantomJSExtension\Driver
 */
class BasePhantomJSDriver extends CoreDriver {

  /** @var  string */
  protected $binLocation;
  /** @var  array */
  protected $options;
  /** @var  Session */
  protected $session;
  /** @var  Server */
  protected $server;
  /** @var  Client */
  protected $client;
  /** @var  Browser */
  protected $browser;

  /**
   * @param string $binLocation Location of the phantomjs binary
   * @param array  $options the options to start the phantomjs binary
   */
  public function __construct($binLocation, $options = array()) {
    $this->binLocation = $binLocation;
    $this->options = $options;
    //Driver creation mean we need to have server and client ready for use
    $this->server = Server::getInstance();
    if ($this->getServer()->isStarted() !== true) {
      $this->server->start();
    } else {
      echo "Server is already started, skipping...\n";
    }
    $this->client = Client::getInstance($this->server, array("path" => $this->binLocation));
    if ($this->getClient()->isStarted() !== true) {
      $this->getClient()->start();
    } else {
      echo "Client is already started, skipping...\n";
    }
    $this->browser = null;
  }

  /**
   * Helper to find a node element given an xpath
   * @param string $xpath
   * @param int    $max
   * @returns int
   * @throws DriverException
   */
  protected function findElement($xpath, $max = 1) {
    $elements = $this->browser->find("xpath", $xpath);
    if (!isset($elements["page_id"]) || !isset($elements["ids"]) || count($elements["ids"]) !== $max) {
      throw new DriverException("Failed to get elements with given $xpath");
    }
    return $elements;
  }

  /**
   * {@inheritdoc}
   * @param Session $session
   */
  public function setSession(Session $session) {
    $this->session = $session;
  }

  /**
   * @return Browser
   */
  public function getBrowser() {
    return $this->browser;
  }

  /**
   * @return Server
   */
  public function getServer() {
    return $this->server;
  }

  /**
   * @return Client
   */
  public function getClient() {
    return $this->client;
  }


}
