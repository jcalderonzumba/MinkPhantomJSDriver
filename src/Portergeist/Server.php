<?php


namespace Behat\PhantomJSExtension\Portergeist;

/**
 * Class Server
 * @package Behat\PhantomJSExtension\Portergeist
 */
class Server {

  const RECV_SIZE = 1024;
  const BIND_TIMEOUT = 5;
  const HOST = '127.0.0.1';
  const DEFAULT_PORT = 8510;

  /** @var  mixed */
  protected $socket;
  /** @var int */
  protected $fixedPort;
  /** @var int */
  protected $timeout;

  /**
   * @param int $fixedPort
   * @param int $timeout
   */
  public function __construct($fixedPort = null, $timeout = null) {
    $this->fixedPort = ($fixedPort === null) ? Server::DEFAULT_PORT : $fixedPort;
    $this->timeout = ($timeout === null) ? Server::BIND_TIMEOUT : $timeout;
    //TODO: add the start method here
  }

  /**
   * @param $message
   * @return mixed
   */
  public function send($message) {
    //TODO: do the actual message send with a curl HTTP REQUEST
    return null;
  }

  /**
   * Stops the Server
   * @return bool
   */
  public function stop() {
    //TODO: we might need to do stuff here
    return true;
  }

  /**
   * Starts the server
   * @return bool
   */
  public function start() {
    //TODO: we might need to do stuff here
    return true;
  }

  /**
   * Restarts the server
   */
  public function restart() {
    $this->stop();
    $this->start();
  }

  /**
   * @return int
   */
  public function getFixedPort() {
    return $this->fixedPort;
  }

  /**
   * @return mixed
   */
  public function getSocket() {
    return $this->socket;
  }

  /**
   * @return int
   */
  public function getTimeout() {
    return $this->timeout;
  }


}
