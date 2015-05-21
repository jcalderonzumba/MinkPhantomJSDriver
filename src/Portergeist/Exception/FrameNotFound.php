<?php

namespace Behat\PhantomJSExtension\Portergeist\Exception;

/**
 * Class FrameNotFound
 * @package Behat\PhantomJSExtension\Portergeist\Exception
 */
class FrameNotFound extends ClientError {

  /**
   * @return string
   */
  public function getName() {
    //TODO: check stuff here
    return current(reset($this->response["args"]));
  }

  /**
   * @return string
   */
  public function message() {
    //TODO: check the exception message stuff
    return "The frame " . $this->getName() . " was not not found";
  }
}
