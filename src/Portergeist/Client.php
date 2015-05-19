<?php

namespace Behat\PhantomJSExtension\Portergeist;

use WebSocket\Client as WSocketClient;

/**
 * Class Client
 * @package Behat\PhantomJSExtension\Portergeist
 */
class Client {
  const KILL_TIMEOUT = 2;
  const PHANTOMJS_NAME = "phantomjs";
  const MAX_READY_TRIES = 2;

  /** @var  string */
  protected $phantomJSScript;
  /** @var  array */
  protected $phantomJSVersion;
  /** @var  string */
  protected $phantomJSName;
  /** @var  Server */
  protected $server;
  /** @var  string */
  protected $phantomJSPath;
  /** @var  array */
  protected $windowSize;
  /** @var  array */
  protected $phantomJSOptions;
  /** @var  mixed */
  protected $phantomJSLogger;
  /** @var  Thread */
  protected $thread;
  /** @var  bool */
  protected $started;

  /** @var Client */
  private static $instance = null;

  /**
   * Gets the singleton instance of the class
   * It is singleton because the phantomjs stuff should be launched ONCE
   * @param Server $server
   * @param array  $options
   * @return Client
   */
  public static function getInstance(Server $server, $options = array()) {
    if (null === self::$instance) {
      self::$instance = new self($server, $options);
    }
    return self::$instance;
  }

  /**
   * @param Server $server
   * @param array  $options
   * @throws \Exception
   */
  private function __construct(Server $server, $options) {
    $this->server = $server;
    if (!isset($options["path"])) {
      //TODO: something like Cliver::detect
      throw new \Exception("You must specify where the phantomjs binary path is");
    }

    if ($this->getServer()->isStarted() !== true) {
      throw new \Exception("Server should be started, it seems is not");
    }

    $this->phantomJSPath = $options["path"];
    $this->windowSize = (isset($options["windowSize"])) ? $options["windowSize"] : array(1024, 768);
    $this->phantomJSOptions = (isset($options["phantomJSOptions"])) ? $options["phantomJSOptions"] : array();
    $this->phantomJSScript = realpath(dirname(__FILE__) . "/Client/main.js");
    $this->thread = null;
    $this->started = false;
    //TODO: $this->$phantomJSLogger;
  }


  /**
   * Stops the client
   * @return bool
   */
  public function stop() {
    if ($this->getThread() !== null && $this->getThread()->getPid() !== null) {
      $this->getThread()->close();
      //TODO: Paranoid check to see if the process is properly closed
    }
    $this->started = false;
  }

  /**
   * As with the websocket server, we need to wait for phantomjs to be ready to accept commands
   */
  protected function waitForPhantomJS() {
    //this is done to check that phantomjs is actually up and running
    $serverName = Server::HOST;
    $retry = 0;
    //assume the worst, we are not ready
    $ready = false;
    //This can be here safely because at the moment the socket client constructor does nothing related to connections
    $wsClient = new WSocketClient("ws://{$serverName}:{$this->getServer()->getFixedPort()}/", array("timeout" => Server::BIND_TIMEOUT));
    while (($ready === false) && ($retry < Client::MAX_READY_TRIES)) {
      try {
        $wsClient->send("are_you_ready");
        $jsonResponse = json_decode($wsClient->receive(), true);
        if ($jsonResponse !== null) {
          if (isset($jsonResponse["response"]) && $jsonResponse["response"] == "i_am_ready") {
            $ready = true;
          }
        }
      } catch (\Exception $error) {
        echo sprintf("%s is not ready to accept commands, waiting\n", Client::PHANTOMJS_NAME);
      }
      $retry++;
    }

    try {
      $wsClient->close();
      $wsClient->__destruct();
    } catch (\Exception $error) {
      echo sprintf("Something bad happened while: %s", $error->getMessage());
    }
    return $ready;
  }

  /**
   * Starts the client
   * @throws \Exception
   * @return bool
   */
  public function start() {
    $command = $this->getCommand();
    $this->thread = new Thread($command);
    if ($this->waitForPhantomJS() !== true) {
      throw new \Exception("Seems phantomjs is not taking websocket requests");
    }
    $this->started = true;
  }

  /**
   * Restarts the client
   */
  public function restart() {
    $this->stop();
    $this->start();
  }

  /**
   * Gets the command we will call the phantomjs with
   * @return string
   */
  public function getCommand() {
    $command = $this->getPhantomJSPath();
    $command .= " " . implode(" ", $this->getPhantomJSOptions());
    $command .= " " . $this->getPhantomJSScript();
    //Starting from this point this are the arguments for the script not for the binary itself
    $command .= " " . $this->getServer()->getFixedPort();
    $command .= " " . implode(" ", $this->getWindowSize());
    return $command;
  }

  /**
   * @return mixed
   */
  public function getPhantomJSLogger() {
    return $this->phantomJSLogger;
  }

  /**
   * @return string
   */
  public function getPhantomJSName() {
    return $this->phantomJSName;
  }

  /**
   * @return array
   */
  public function getPhantomJSOptions() {
    //TODO: crete proper options for phantomjs
    return $this->phantomJSOptions;
  }

  /**
   * @return string
   */
  public function getPhantomJSPath() {
    return $this->phantomJSPath;
  }

  /**
   * @return string
   */
  public function getPhantomJSScript() {
    return $this->phantomJSScript;
  }

  /**
   * @return array
   */
  public function getPhantomJSVersion() {
    return $this->phantomJSVersion;
  }

  /**
   * @return array
   */
  public function getWindowSize() {
    return $this->windowSize;
  }

  /**
   * @return Server
   */
  public function getServer() {
    return $this->server;
  }

  /**
   * @return Thread
   */
  public function getThread() {
    return $this->thread;
  }

  /**
   * Checks whether the service is started or not
   * @return bool
   */
  public function isStarted() {
    return $this->started;
  }

}
