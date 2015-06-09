<?php

namespace Behat\PhantomJSExtension\Driver;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\DriverException;
use Behat\PhantomJSExtension\Portergeist\Browser\Browser;
use Behat\PhantomJSExtension\Portergeist\Cookie;

/**
 * Class PhantomJSDriver
 * @package Behat\Mink\Driver
 */
class PhantomJSDriver extends BasePhantomJSDriver {

  use SessionTrait;
  use NavigationTrait;
  use CookieTrait;
  use HeadersTrait;
  use JavascriptTrait;
  use MouseTrait;
  use PageContentTrait;
  use KeyboardTrait;
  use FormManipulationTrait;
  use WindowTrait;

  /**
   * Sets the basic auth user and password
   * @param string $user
   * @param string $password
   */
  public function setBasicAuth($user, $password) {
    $this->browser->setHttpAuth($user, $password);
  }

  /**
   * Gets the tag name of a given xpath
   * @param string $xpath
   * @return string
   * @throws DriverException
   */
  public function getTagName($xpath) {
    $elements = $this->findElement($xpath, 1);
    return $this->browser->tagName($elements["page_id"], $elements["ids"][0]);
  }

  /**
   * Gets the attribute value of a given element and name
   * @param string $xpath
   * @param string $name
   * @return string
   * @throws DriverException
   */
  public function getAttribute($xpath, $name) {
    $elements = $this->findElement($xpath, 1);
    return $this->browser->attribute($elements["page_id"], $elements["ids"][0], $name);
  }

  /**
   * Returns the value of a given xpath element
   * @param string $xpath
   * @return string
   * @throws DriverException
   */
  public function getValue($xpath) {
    $elements = $this->findElement($xpath, 1);
    return $this->browser->value($elements["page_id"], $elements["ids"][0]);
  }

  /**
   * @param string $xpath
   * @param string $value
   * @throws DriverException
   */
  public function setValue($xpath, $value) {
    $elements = $this->findElement($xpath, 1);
    //TODO: Check the return of this stuff to add more error control
    echo $this->browser->set($elements["page_id"], $elements["ids"][0], $value);
  }

  /**
   * Check if element given by xpath is visible or not
   * @param string $xpath
   * @return bool
   * @throws DriverException
   */
  public function isVisible($xpath) {
    //TODO: check the response of this
    $elements = $this->findElement($xpath, 1);
    return $this->browser->isVisible($elements["page_id"], $elements["ids"][0]);
  }

  /**
   * Drags one element to another
   * @param string $sourceXpath
   * @param string $destinationXpath
   * @throws DriverException
   */
  public function dragTo($sourceXpath, $destinationXpath) {
    $sourceElement = $this->findElement($sourceXpath, 1);
    $destinationElement = $this->findElement($destinationXpath, 1);
    echo $this->browser->drag($sourceElement["page_id"], $sourceElement["ids"][0], $destinationElement["ids"][0]);
  }

  /**
   * Upload a file to the browser
   * @param string $xpath
   * @param string $path
   * @throws DriverException
   */
  public function attachFile($xpath, $path) {
    if (!file_exists($path)) {
      throw new DriverException("Wow there the file does not exist, you can not upload it");
    }

    if (($realPath = realpath($path)) === false) {
      throw new DriverException("Wow there the file does not exist, you can not upload it");
    }

    $element = $this->findElement($xpath, 1);
    echo $this->browser->selectFile($element["page_id"], $element["ids"][0], $realPath);
  }

  /**
   * Returns the binary representation of the current page we are in
   * @throws DriverException
   * @return string
   */
  public function getScreenshot() {
    $options = array("full" => true, "selector" => null);
    //TODO: check why in the hell render_base64 does not work properly
    //What i'm about to do is not cool but is the way to do it until the other stuff is fixed
    $tmpDir = sys_get_temp_dir();
    $randomName = str_replace(".", "", str_replace(" ", "", microtime(false)));
    $filePath = sprintf("%s/phantomjs_driver_screenshot_%s.png", $tmpDir, $randomName);
    $this->browser->render($filePath, $options);
    //TODO: Maybe we should just not fail and render an empty image?
    if (!file_exists($filePath) || @filesize($filePath) === false || @filesize($filePath) <= 0) {
      throw new DriverException("Something happened during screenshot, bad stuff");
    }
    //now that we know the file exists and its size is greater than 0 bytes we assume all ok so we will just return the binary thing
    if (($binaryScreenshot = file_get_contents($filePath)) === false) {
      throw new DriverException("Something happened during screenshot, bad stuff");
    }
    return $binaryScreenshot;
  }

  /**
   * Puts the browser control inside the IFRAME
   * You own the control, make sure to go back to the parent calling this method with null
   * @param string $name
   */
  public function switchToIFrame($name = null) {
    //TODO: check response of the calls
    if ($name === null) {
      $this->browser->popFrame();
      return;
    }else {
      $this->browser->pushFrame($name);
    }
  }

  /**
   * Focus on an element
   * @param string $xpath
   * @throws DriverException
   */
  public function focus($xpath) {
    $element = $this->findElement($xpath, 1);
    echo $this->browser->trigger($element["page_id"], $element["ids"][0], "focus");
  }

  /**
   * Blur on element
   * @param string $xpath
   * @throws DriverException
   */
  public function blur($xpath) {
    $element = $this->findElement($xpath, 1);
    echo $this->browser->trigger($element["page_id"], $element["ids"][0], "blur");
  }

  /**
   * Finds elements with specified XPath query.
   * @param string $xpath
   * @return NodeElement[]
   * @throws DriverException                  When the operation cannot be done
   */
  public function find($xpath) {
    $elements = $this->browser->find("xpath", $xpath);
    $nodeElements = array();
    foreach ($elements["ids"] as $i => $id) {
      if (!empty($id)) {
        $nodeElements[] = new NodeElement(sprintf('(%s)[%d]', $xpath, $i + 1), $this->session);
      }
    }
    return $nodeElements;
  }

}
