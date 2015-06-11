<?php

namespace Behat\PhantomJSExtension\Driver;

use Behat\Mink\Exception\DriverException;

/**
 * Class PageContentTrait
 * @package Behat\PhantomJSExtension\Driver
 */
trait PageContentTrait {

  /**
   * @return string
   */
  public function getContent() {
    return $this->browser->getBody();
  }

  /**
   * Given xpath, will try to get ALL the text, visible and not visible from such xpath
   * @param string $xpath
   * @return string
   * @throws DriverException
   */
  public function getText($xpath) {
    $elements = $this->findElement($xpath, 1);
    //allText works only with ONE element so it will be the first one and also returns new lines that we will remove
    $text = trim(str_replace("\n", " ", $this->browser->allText($elements["page_id"], $elements["ids"][0])));
    $text = preg_replace('/\s\s+/', ' ', $text);
    return $text;
  }

  /**
   * Returns the inner html of a given xpath
   * @param string $xpath
   * @return string
   * @throws DriverException
   */
  public function getHtml($xpath) {
    $elements = $this->findElement($xpath, 1);
    //allText works only with ONE element so it will be the first one
    return $this->browser->allHtml($elements["page_id"], $elements["ids"][0], "inner");
  }

  /**
   * Gets the outer html of a given xpath
   * @param string $xpath
   * @return string
   * @throws DriverException
   */
  public function getOuterHtml($xpath) {
    $elements = $this->findElement($xpath, 1);
    //allText works only with ONE element so it will be the first one
    return $this->browser->allHtml($elements["page_id"], $elements["ids"][0], "outer");
  }
}
