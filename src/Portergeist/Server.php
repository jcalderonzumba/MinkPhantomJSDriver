<?php


namespace Behat\PhantomJSExtension\Portergeist;

use WebSocket\Client as WSocketClient;

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
  /** @var  Thread */
  protected $thread;
  /** @var  WSocketClient */
  protected $wsClient;

  /**
   * @param int $fixedPort
   * @param int $timeout
   */
  public function __construct($fixedPort = null, $timeout = null) {
    $this->fixedPort = ($fixedPort === null) ? Server::DEFAULT_PORT : $fixedPort;
    $this->timeout = ($timeout === null) ? Server::BIND_TIMEOUT : $timeout;
    $this->wsClient = null;
    $this->thread = null;
    //TODO: add the start method here
  }

  /**
   * @param string $message
   * @return mixed
   */
  public function send($message) {
    echo "Message to send $message\n";
    $this->getWsClient()->send($message);
    //We will wait for the response
    return $this->getWsClient()->receive();
  }

  /**
   * Stops the Server
   * @return bool
   */
  public function stop() {
    if ($this->getThread() !== null && $this->getThread()->getPid() !== null) {
      $this->getWsClient()->close();
      $this->getThread()->close();
      //TODO: Paranoid check to see if the process is properly closed
    }
    return true;
  }

  /**
   * Starts the embedded web socket client
   * @throws \Exception
   */
  public function wsClientStart() {
    if (1 == 2 && $this->getThread() === null) {
      throw new \Exception("Can not start the client if the server has not started");
    }

    if ($this->getWsClient() !== null) {
      throw  new \Exception("WebSocketClient is already started");
    }

    $this->wsClient = new WSocketClient("ws://127.0.0.1:{$this->getFixedPort()}/");
  }

  /**
   * Starts the server
   * @throws \Exception
   * @return bool
   */
  public function start() {
    $command = "/Users/juan/code/scm/pjsdriver/bin/wsserver {$this->getFixedPort()}";
    //$this->thread = new Thread($command);
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

  /**
   * @return Thread
   */
  public function getThread() {
    return $this->thread;
  }

  /**
   * @return WSocketClient
   */
  public function getWsClient() {
    return $this->wsClient;
  }

}
