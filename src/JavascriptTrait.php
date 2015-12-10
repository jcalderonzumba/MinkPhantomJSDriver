<?php

namespace Zumba\Mink\Driver;

use Behat\Mink\Exception\DriverException;

/**
 * Class JavascriptTrait
 * @package Zumba\Mink\Driver
 */
trait JavascriptTrait {

  /**
   * Helper function to fix javascript code before sending it to the phantomjs API
   * @param $script
   * @return string
   */
  protected function fixJavascriptForUse($script){
    //Fix self returning piece of code;
    $scriptToUse = trim($script);
    $returningRegexp = "#^return(.+)#is";
    $selfFunctionRegexp = '#^function[\s\(]#is';
    if (preg_match($returningRegexp, $scriptToUse, $scriptMatch) === 1) {
      $scriptToUse = trim($scriptMatch[1]);
    } elseif (preg_match($selfFunctionRegexp, $scriptToUse, $scriptMatch) === 1) {
      //Fix self function without proper encapsulation to anonymous javascript functions
      $scriptToUse = sprintf("(%s)", preg_replace("#;$#", '', $scriptToUse));
    }
    return $scriptToUse;
  }

  /**
   * Executes a script on the browser
   * @param string $script
   */
  public function executeScript($script) {
    $this->browser->execute($this->fixJavascriptForUse($script));
  }

  /**
   * Evaluates a script and returns the result
   * @param string $script
   * @return mixed
   */
  public function evaluateScript($script) {
    return $this->browser->evaluate($this->fixJavascriptForUse($script));
  }

  /**
   * Waits some time or until JS condition turns true.
   *
   * @param integer $timeout timeout in milliseconds
   * @param string  $condition JS condition
   * @return boolean
   * @throws DriverException                  When the operation cannot be done
   */
  public function wait($timeout, $condition) {
    $start = microtime(true);
    $end = $start + $timeout / 1000.0;
    do {
      $result = $this->browser->evaluate($condition);
      usleep(100000);
    } while (microtime(true) < $end && !$result);

    return (bool)$result;
  }

}
