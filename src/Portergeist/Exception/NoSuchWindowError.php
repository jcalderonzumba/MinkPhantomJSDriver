<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 17/05/15
 * Time: 19:06
 */

namespace Behat\PhantomJSExtension\Portergeist\Exception;


/**
 * Class NoSuchWindowError
 * @package Behat\PhantomJSExtension\Portergeist\Exception
 */
class NoSuchWindowError extends \Exception {
  /**
   * @param string $message
   */
  public function __construct($message) {
    parent::__construct($message);
  }
}
