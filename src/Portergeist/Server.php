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
   * @throws \Exception
   */
  public function send($message) {
    //TODO: do the actual message send with a curl HTTP REQUEST
    throw new \Exception("NOT IMPLEMENTED $message");
  }
}
