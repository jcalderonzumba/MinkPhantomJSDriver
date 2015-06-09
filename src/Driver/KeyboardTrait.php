<?php

namespace Behat\PhantomJSExtension\Driver;

use Behat\Mink\Exception\DriverException;

/**
 * Class KeyboardTrait
 * @package Behat\PhantomJSExtension\Driver
 */
trait KeyboardTrait {
  /**
   * @param string $xpath
   * @param string $char
   * @param string $modifier
   * @throws DriverException
   */
  public function keyPress($xpath, $char, $modifier = null) {
    //TODO: implement the modifier support
    if ($modifier !== null) {
      throw new DriverException("Modifier support for keypress is not yet developed");
    }

    $element = $this->findElement($xpath, 1);
    $this->browser->sendKeys($element["page_id"], $element["ids"][0], array($char));
  }
}
