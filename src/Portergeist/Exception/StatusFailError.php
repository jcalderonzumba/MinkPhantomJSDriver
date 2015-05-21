<?php

namespace Behat\PhantomJSExtension\Portergeist\Exception;


/**
 * Class StatusFailError
 * @package Behat\PhantomJSExtension\Portergeist\Exception
 */
class StatusFailError extends ClientError {
  /**
   * @return string
   */
  public function message() {
    return "Request failed to reach server, check DNS and/or server status";
  }
}
