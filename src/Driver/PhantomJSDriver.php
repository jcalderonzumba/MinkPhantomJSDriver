<?php

namespace Behat\PhantomJSExtension\Driver;

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
  }
}
