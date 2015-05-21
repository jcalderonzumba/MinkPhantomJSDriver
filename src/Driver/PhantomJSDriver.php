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
  /** @var  bool */
  protected $started;

  /**
   * Starts a session to be used by the driver client
   */
  public function start() {
    $this->browser = new Browser($this->getServer(), $this->getClient());
    $this->started = true;
  }

  /**
   * Tells if the session is started or not
   * @return bool
   */
  public function isStarted() {
    return $this->started;
  }

  /**
   * Stops the session completely, clean slate for the browser
   * @return bool
   */
  public function stop() {
    //TODO: for the moment until found otherwise we will just reset, meaning GOODBYE all cookies
    return $this->reset();
  }

  /**
   * Clears the cookies in the browser, all of them
   * @return bool
   */
  public function reset() {
    echo $this->getBrowser()->clearCookies();
    $this->started = false;
    return true;
  }

  /**
   * Visits a given url
   * @param string $url
   */
  public function visit($url) {
    echo $this->browser->visit($url);
  }

  /**
   * Gets the current url if any
   * @return string
   */
  public function getCurrentUrl() {
    return $this->browser->currentUrl();
  }

  /**
   * @return string
   */
  public function getContent() {
    //TODO: check if this is what we need instead of getBody
    return $this->browser->getSource();
  }

  /**
   * {@inheritdoc}
   */
  public function reload() {
    echo $this->browser->reload();
  }

  /**
   * Goes forward if possible
   */
  public function forward() {
    echo $this->browser->goForward();
  }

  /**
   * Goes back if possible
   */
  public function back() {
    echo $this->browser->goBack();
  }

  /**
   * Sets the basic auth user and password
   * @param string $user
   * @param string $password
   */
  public function setBasicAuth($user, $password) {
    echo $this->browser->setHttpAuth($user, $password);
  }


  /**
   * Gets the current request response headers
   * Should be called only after a request, other calls are undefined behaviour
   * @return array
   */
  public function getResponseHeaders() {
    //TODO: Check the output form of this response and fix it to suit the driver needs
    return $this->browser->responseHeaders();
  }

  /**
   * Current request status code response
   * @return int
   */
  public function getStatusCode() {
    return $this->browser->getStatusCode();
  }

  /**
   * Sets a cookie on the browser, if null value then delete it
   * @param string $name
   * @param string $value
   */
  public function setCookie($name, $value = null) {
    if ($value === null) {
      $this->browser->removeCookie($name);
    }
    if ($value !== null) {
      $cookie = array("name" => $name, "value" => $value, $this->getCurrentUrl());
      $this->browser->setCookie($cookie);
    }
  }

  /**
   * Gets a cookie by its name if exists, else it will return null
   * @param string $name
   * @return string
   */
  public function getCookie($name) {
    //TODO: Check if this works as expected
    $cookies = $this->browser->cookies();
    foreach ($cookies as $cookie) {
      if ($cookie instanceof Cookie && strcmp($cookie->getName(), $name) === 0) {
        return $cookie->getValue();
      }
    }
  }

  /**
   * The name say its all
   * @param string $name
   * @param string $value
   */
  public function setRequestHeader($name, $value) {
    $header = array("name" => $name, "value" => $value);
    //TODO: as a limitation of the driver it self, we will send permanent for the moment
    echo $this->browser->addHeader($header, true);
  }

  /**
   * Returns the current page window name
   * @return string
   */
  public function getWindowName() {
    return $this->browser->windowName();
  }

  /**
   * Executes a script on the browser
   * @param string $script
   */
  public function executeScript($script) {
    echo $this->browser->execute($script);
  }

  /**
   * Evaluates a script and returns the result
   * @param string $script
   * @return mixed
   */
  public function evaluateScript($script) {
    //TODO: check how this works..
    return $this->browser->evaluate($script);
  }


  /**
   * Given xpath, will try to get ALL the text, visible and not visible from such xpath
   * @param string $xpath
   * @return string
   * @throws DriverException
   */
  public function getText($xpath) {
    $elements = $this->findElement($xpath, 1);
    //allText works only with ONE element so it will be the first one
    return $this->browser->allText($elements["page_id"], $elements["ids"][0]);
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
   * Clicks if possible on an element given by xpath
   * @param string $xpath
   * @return mixed
   * @throws DriverException
   */
  public function click($xpath) {
    //TODO: check the output of this
    $elements = $this->findElement($xpath, 1);
    echo $this->browser->click($elements["page_id"], $elements["ids"][0]);
  }

  /**
   * {@inheritdoc}
   */
  /**
   * Double click on element found via xpath
   * @param string $xpath
   * @throws DriverException
   */
  public function doubleClick($xpath) {
    //TODO: check the output of this
    $elements = $this->findElement($xpath, 1);
    echo $this->browser->doubleClick($elements["page_id"], $elements["ids"][0]);
  }

  /**
   * Right click on element found via xpath
   * @param string $xpath
   * @throws DriverException
   */
  public function rightClick($xpath) {
    //TODO: check the output of this
    $elements = $this->findElement($xpath, 1);
    echo $this->browser->rightClick($elements["page_id"], $elements["ids"][0]);
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
   * Checks if the radio or checkbox is checked
   * @param string $xpath
   * @return bool
   * @throws DriverException
   */
  public function isChecked($xpath) {
    //TODO: test stuff
    $elements = $this->findElement($xpath, 1);
    $data = $this->browser->attributes($elements["page_id"], $elements["ids"][0]);
    if ($data["type"] !== "checkbox" && $data["type"] !== "radio") {
      throw new DriverException("Can not check when the element is not checkbox or radio");
    }
    if (!isset($data["checked"])) {
      return false;
    }

    return (strcmp($data["checked"], "checked") === 0);
  }

  /**
   * Checks if the option is selected or not
   * @param string $xpath
   * @return bool
   * @throws DriverException
   */
  public function isSelected($xpath) {
    $elements = $this->findElement($xpath, 1);
    $tagName = $this->browser->tagName($elements["page_id"], $elements["ids"][0]);
    if (strcmp(strtolower($tagName), "option") !== 0) {
      throw new DriverException("Can not assert on element that is not an option");
    }

    $data = $this->browser->attributes($elements["page_id"], $elements["ids"][0]);
    if (!isset($data["selected"])) {
      return false;
    }

    return (strcmp($data["selected"], "selected") === 0);
  }

  /**
   * We click on the checkbox or radio when possible and needed
   * @param string $xpath
   * @throws DriverException
   */
  public function check($xpath) {
    if ($this->isChecked($xpath) === true) {
      return;
    }
    //only when the element is not checked we do stuff
    $elements = $this->findElement($xpath, 1);
    $this->browser->setAttribute($elements["page_id"], $elements["ids"][0], "checked", "checked");
  }

  /**
   * We click on the checkbox or radio when possible and needed
   * @param string $xpath
   * @throws DriverException
   */
  public function uncheck($xpath) {
    if ($this->isChecked($xpath) !== true) {
      return;
    }
    //only when the element is not checked we do stuff
    $elements = $this->findElement($xpath, 1);
    $this->browser->removeAttribute($elements["page_id"], $elements["ids"][0], "checked");
  }

  /**
   * Selects an option
   * @param string $xpath
   * @param string $value
   * @param bool   $multiple
   * @throws DriverException
   */
  public function selectOption($xpath, $value, $multiple = false) {
    throw new DriverException("Not yet done until everything else easier is done");
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
   * Submits a form given an xpath selector
   * @param string $xpath
   * @throws DriverException
   */
  public function submitForm($xpath) {
    $element = $this->findElement($xpath, 1);
    $tagName = $this->browser->tagName($element["page_id"], $element["ids"][0]);
    if (strcmp(strtolower($tagName), "form") !== 0) {
      throw new DriverException("Can not submit something that is not a form");
    }
    echo $this->browser->trigger($element["page_id"], $element["ids"][0], "submit");
  }

  /**
   * Return all the window handles currently present in phantomjs
   * @return array
   */
  public function getWindowNames() {
    return $this->browser->windowHandles();
  }

  /**
   * Switches to window by name if possible
   * @param $name
   */
  public function switchToWindow($name = null) {
    if ($name === null) {
      //Nothing to do, we stay on the window we are in
      return;
    }
    //TODO: this stuff throws error on browser.js so check it when testing
    echo $this->browser->switchToWindow($name);
  }

  /**
   * Puts the browser control inside the IFRAME
   * You own the control, make sure to go back to the parent calling this method with null
   * @param string $name
   */
  public function switchToIFrame($name = null) {
    //TODO: check response of the calls
    if ($name === null) {
      echo $this->browser->popFrame();
      return;
    }
    echo $this->browser->pushFrame($name);
  }

  /**
   * Resizing a window with specified size
   * @param int    $width
   * @param int    $height
   * @param string $name
   * @throws DriverException
   */
  public function resizeWindow($width, $height, $name = null) {
    if ($name !== null) {
      //TODO: add this on the phantomjs stuff
      throw new DriverException("Resizing other window than the main one is not supported yet");
    }
    echo $this->browser->resize($width, $height);
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
   * Generates a mouseover event on the given element by xpath
   * @param string $xpath
   * @throws DriverException
   */
  public function mouseOver($xpath) {
    $element = $this->findElement($xpath, 1);
    echo $this->browser->trigger($element["page_id"], $element["ids"][0], "mouseover");
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
    //TODO: test this implementation, if needed add wrappers
    $start = microtime(true);
    $end = $start + $timeout / 1000.0;
    do {
      $result = $this->browser->evaluate($condition);
      usleep(100000);
    } while (microtime(true) < $end && !$result);

    return (bool)$result;
  }

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
    echo $this->browser->sendKeys($element["page_id"], $element["ids"][0], array($char));
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
