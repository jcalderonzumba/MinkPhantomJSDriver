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
  /** @var  bool */
  protected $started;

  /**
   * @param int $fixedPort
   * @param int $timeout
   */
  public function __construct($fixedPort = null, $timeout = null) {
    $this->fixedPort = ($fixedPort === null) ? Server::DEFAULT_PORT : $fixedPort;
    $this->timeout = ($timeout === null) ? Server::BIND_TIMEOUT : $timeout;
    $this->wsClient = null;
    $this->thread = null;
    $this->started = false;
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
    if ($this->getThread() === null) {
      throw new \Exception("Can not start the client if the server has not started");
    }

    if ($this->getWsClient() !== null) {
      throw  new \Exception("WebSocketClient is already started");
    }
    $hostname = Server::HOST;
    $this->wsClient = new WSocketClient("ws://{$hostname}:{$this->getFixedPort()}/", array("timeout" => Server::BIND_TIMEOUT));
  }


  /**
   * Waits for the server to be ready
   * @return bool
   */
  protected function waitForServer() {
    $serverSocket = @fsockopen(Server::HOST, $this->getFixedPort(), $errno, $errstr, Server::BIND_TIMEOUT);
    $startTime = time();
    while (!is_resource($serverSocket) && ((time() - $startTime) < Server::BIND_TIMEOUT)) {
      $serverSocket = @fsockopen(Server::HOST, $this->getFixedPort(), $errno, $errstr, Server::BIND_TIMEOUT);
    }

    if (!is_resource($serverSocket)) {
      echo "Server is not listening for socket connections, errors: $errno, $errstr...\n";
      return false;
    }
    fclose($serverSocket);
    return true;
  }

  /**
   * Starts the server
   * @throws \Exception
   * @return bool
   */
  public function start() {
    $command = "php /Users/juan/code/scm/pjsdriver/bin/wsserver {$this->getFixedPort()}";
    $this->thread = new Thread($command);
    if ($this->waitForServer() !== true) {
      throw new \Exception("Something bad happened could not start Websocket server");
    }
    $this->wsClientStart();
    $this->started = true;
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

  /**
   * To check if the server is up and running
   * @return bool
   */
  public function isStarted() {
    return $this->started;
  }

}
