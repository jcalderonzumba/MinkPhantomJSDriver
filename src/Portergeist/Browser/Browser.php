<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

use Behat\PhantomJSExtension\Portergeist\Client;
use Behat\PhantomJSExtension\Portergeist\Server;

/**
 * Class Browser
 * @package Behat\PhantomJSExtension\Portergeist
 */
class Browser extends BrowserWindow {

  /**
   * @param Server $server
   * @param Client $client
   * @param mixed  $logger
   */
  public function __construct(Server $server, Client $client, $logger = null) {
    $this->server = $server;
    $this->client = $client;
    $this->logger = $logger;
    $this->debug = false;
  }

  /**
   * Gets the status code of the request we are currently in
   * @return mixed
   */
  public function getStatusCode() {
    return $this->command('status_code');
  }

  /**
   * Returns the body of the response to a given request
   * @return mixed
   */
  public function getBody() {
    return $this->command('body');
  }

  /**
   * Returns the source of the current page
   * @return mixed
   */
  public function getSource() {
    return $this->command('source');
  }

  /**
   * Gets the current page title
   * @return mixed
   */
  public function getTitle() {
    return $this->command('title');
  }

  /**
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function getParents($pageId, $elementId) {
    return $this->command('parents', $pageId, $elementId);
  }

  /**
   * Find elements given a method and a selector
   * @param $method
   * @param $selector
   * @return array
   */
  public function find($method, $selector) {
    $result = $this->command('find', $method, $selector);
    $found["page_id"] = $result["page_id"];
    foreach ($result["ids"] as $id) {
      $found["ids"][] = $id;
    }
    return $found;
  }

  /**
   * Find elements within a page, method and selector
   * @param $pageId
   * @param $elementId
   * @param $method
   * @param $selector
   * @return mixed
   */
  public function findWithin($pageId, $elementId, $method, $selector) {
    return $this->command('find_within', $pageId, $elementId, $method, $selector);
  }

  /**
   * Returns the text of a given page and element
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function allText($pageId, $elementId) {
    return $this->command('all_text', $pageId, $elementId);
  }

  /**
   * Returns the inner or outer html of the given page and element
   * @param $pageId
   * @param $elementId
   * @param $type
   * @return mixed
   * @throws \Behat\PhantomJSExtension\Portergeist\Exception\BrowserError
   * @throws \Exception
   */
  public function allHtml($pageId, $elementId, $type = "inner") {
    return $this->command('all_html', $pageId, $elementId, $type);
  }

  /**
   * Returns ONLY the visible text of a given page and element
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function visibleText($pageId, $elementId) {
    return $this->command('visible_text', $pageId, $elementId);
  }

  /**
   * Deletes the text of a given page and element
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function deleteText($pageId, $elementId) {
    return $this->command('delete_text', $pageId, $elementId);
  }

  /**
   * Returns the attributes of an element in a given page
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function attributes($pageId, $elementId) {
    return $this->command('attributes', $pageId, $elementId);
  }

  /**
   * Returns the attribute of an element by name in a given page
   * @param $pageId
   * @param $elementId
   * @param $name
   * @return mixed
   */
  public function attribute($pageId, $elementId, $name) {
    return $this->command('attribute', $pageId, $elementId, $name);
  }

  /**
   * Set an attribute to the given element in the given page
   * @param $pageId
   * @param $elementId
   * @param $name
   * @param $value
   * @return mixed
   * @throws \Behat\PhantomJSExtension\Portergeist\Exception\BrowserError
   * @throws \Exception
   */
  public function setAttribute($pageId, $elementId, $name, $value) {
    return $this->command('set_attribute', $pageId, $elementId, $name, $value);
  }

  /**
   * Remove an attribute for a given page and element
   * @param $pageId
   * @param $elementId
   * @param $name
   * @return mixed
   * @throws \Behat\PhantomJSExtension\Portergeist\Exception\BrowserError
   * @throws \Exception
   */
  public function removeAttribute($pageId, $elementId, $name) {
    return $this->command('remove_attribute', $pageId, $elementId, $name);
  }

  /**
   * Returns the value of a given element in a page
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function value($pageId, $elementId) {
    return $this->command('value', $pageId, $elementId);
  }

  /**
   * Sets a value to a given element in a given page
   * @param $pageId
   * @param $elementId
   * @param $value
   * @return mixed
   */
  public function set($pageId, $elementId, $value) {
    return $this->command('set', $pageId, $elementId, $value);
  }

  /**
   * Selects a file to send to the browser to a given page
   * @param $pageId
   * @param $elementId
   * @param $value
   * @return mixed
   */
  public function selectFile($pageId, $elementId, $value) {
    return $this->command('select_file', $pageId, $elementId, $value);
  }

  /**
   * Gets the tag name of a given element and page
   * @param $pageId
   * @param $elementId
   * @return string
   */
  public function tagName($pageId, $elementId) {
    return strtolower($this->command('tag_name', $pageId, $elementId));
  }

  /**
   * Tells whether an element on a page is visible or not
   * @param $pageId
   * @param $elementId
   * @return bool
   */
  public function isVisible($pageId, $elementId) {
    $response = $this->command('visible', $pageId, $elementId);
    //TODO: Check whether we get ALWAYS a boolean, otherwise do transformations when needed
    if (is_bool($response)) {
      return $response;
    }
    //TODO: Until check is done we consider everything to be visible
    return true;
  }

  /**
   * @param $pageId
   * @param $elementId
   * @return bool
   */
  public function isDisabled($pageId, $elementId) {
    $response = $this->command('disabled', $pageId, $elementId);
    //TODO: Check whether we get ALWAYS a boolean, otherwise do transformations when needed
    if (is_bool($response)) {
      return $response;
    }
    //TODO: Until check is done we consider everything NOT to be disabled
    return false;
  }

  /**
   * Back to the parent of the iframe if possible
   * @return mixed
   * @throws \Behat\PhantomJSExtension\Portergeist\Exception\BrowserError
   * @throws \Exception
   */
  public function popFrame() {
    return $this->command("pop_frame");
  }

  /**
   * Goes into the iframe to do stuff
   * @param string $name
   * @param int    $timeout
   * @return mixed
   * @throws \Behat\PhantomJSExtension\Portergeist\Exception\BrowserError
   * @throws \Exception
   */
  public function pushFrame($name, $timeout = null) {
    return $this->command("push_frame", $name, $timeout);
  }

  /**
   * Drag an element to a another in a given page
   * @param $pageId
   * @param $fromId
   * @param $toId
   * @return mixed
   */
  public function drag($pageId, $fromId, $toId) {
    return $this->command('drag', $pageId, $fromId, $toId);
  }

  /**
   * Selects a value in the given element and page
   * @param $pageId
   * @param $elementId
   * @param $value
   * @return mixed
   */
  public function select($pageId, $elementId, $value) {
    return $this->command('select', $pageId, $elementId, $value);
  }

  /**
   * Triggers an event to a given element on the given page
   * @param $pageId
   * @param $elementId
   * @param $event
   * @return mixed
   */
  public function trigger($pageId, $elementId, $event) {
    return $this->command('trigger', $pageId, $elementId, $event);
  }

  /**
   * @return mixed
   */
  public function reset() {
    return $this->command('reset');
  }

  /**
   * Zoom factor for a web page
   * @param $zoomFactor
   * @return mixed
   */
  public function setZoomFactor($zoomFactor) {
    return $this->command('set_zoom_factor', $zoomFactor);
  }

  /**
   * Resize the current page
   * @param $width
   * @param $height
   * @return mixed
   */
  public function resize($width, $height) {
    return $this->command('resize', $width, $height);
  }

  /**
   * TODO: not sure how to do the normalizeKeys stuff
   *       fix when needed
   * @param $keys
   * @return mixed
   */
  protected function normalizeKeys($keys) {
    return $keys;
  }

  /**
   * TODO: not sure what this does, needs to do normalizeKeys
   * @param int   $pageId
   * @param int   $elementId
   * @param array $keys
   * @return mixed
   */
  public function sendKeys($pageId, $elementId, $keys) {
    return $this->command('send_keys', $pageId, $elementId, $this->normalizeKeys($keys));
  }

  /**
   * Check if two elements are the same on a give
   * @param $pageId
   * @param $firstId
   * @param $secondId
   * @return bool
   */
  public function equals($pageId, $firstId, $secondId) {
    //TODO: check the actual return of the command to see if the return is boolean
    return $this->command('equals', $pageId, $firstId, $secondId);
  }

  /**
   * Set a blacklist of urls that we are not supposed to load
   * @param array $blackList
   * @return bool
   */
  public function urlBlacklist($blackList) {
    return $this->command('set_url_blacklist', $blackList);
  }

  /**
   * Set the debug mode on the browser
   * @param bool $enable
   * @return bool
   */
  public function debug($enable = false) {
    $this->debug = $enable;
    return $this->command('set_debug', $this->debug);
  }
}
