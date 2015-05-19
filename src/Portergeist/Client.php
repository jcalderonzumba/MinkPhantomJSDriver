<?php

namespace Behat\PhantomJSExtension\Portergeist;


/**
 * Class Client
 * @package Behat\PhantomJSExtension\Portergeist
 */
class Client {
  const KILL_TIMEOUT = 2;
  const PHANTOMJS_NAME = "phantomjs";

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

  /**
   * @param Server $server
   * @param array  $options
   * @throws \Exception
   */
  public function __construct(Server $server, $options = array()) {
    $this->server = $server;

    if (!isset($options["path"])) {
      //TODO: something like Cliver::detect
      throw new \Exception("You must specify where the phantomjs binary path is");
    }

    $this->phantomJSPath = $options["path"];
    $this->windowSize = (isset($options["windowSize"])) ? $options["windowSize"] : array(1024, 768);
    $this->phantomJSOptions = (isset($options["phantomJSOptions"])) ? $options["phantomJSOptions"] : array();
    $this->phantomJSScript = realpath(dirname(__FILE__) . "/Client/main.js");
    $this->thread = null;
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
  }

  /**
   * Starts the client
   * @return bool
   */
  public function start() {
    $command = $this->getCommand();
    $this->thread = new Thread($command);
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


}
