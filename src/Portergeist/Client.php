<?php

namespace Behat\PhantomJSExtension\Portergeist;

/**
 * Class Client
 * @package Behat\PhantomJSExtension\Portergeist
 */
class Client {


  public function stop() {
    return true;
  }

  public function start() {
    return true;
  }

  /**
   * Restarts the client
   */
  public function restart() {
    //TODO: implement the stop and start methods
    $this->stop();
    $this->start();
  }
}
