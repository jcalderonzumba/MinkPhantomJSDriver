<?php


namespace Behat\PhantomJSExtension\Portergeist;

/**
 * Class Server
 * @package Behat\PhantomJSExtension\Portergeist
 */
class Server {
  protected $socket;
  protected $fixedPort;
  protected $timeout;

  /**
   * @param int $fixedPort
   * @param int $timeout
   */
  public function __construct($fixedPort = null, $timeout = null) {
    $this->fixedPort = $fixedPort;
    $this->timeout = $timeout;
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
}
