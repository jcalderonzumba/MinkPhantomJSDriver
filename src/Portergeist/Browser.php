<?php

namespace Behat\PhantomJSExtension\Portergeist;

use Behat\PhantomJSExtension\Portergeist\Exception\BrowserError;

/**
 * Class Browser
 * @package Behat\PhantomJSExtension\Portergeist
 */
class Browser {

  /** @var Server */
  protected $server;
  /** @var Client */
  protected $client;
  /** @var mixed */
  protected $logger;
  /** @var  bool */
  protected $debug;


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
   * @return Client
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * @return mixed
   */
  public function getLogger() {
    return $this->logger;
  }

  /**
   * @return Server
   */
  public function getServer() {
    return $this->server;
  }

  /**
   * Restarts the browser
   */
  public function restart() {
    $this->getServer()->restart();
    $this->getClient()->restart();
  }

  /**
   * Send a visit command to the browser
   * @param $url
   * @return mixed
   */
  public function visit($url) {
    return $this->command('visit', $url);
  }

  /**
   * Gets the current url we are in
   * @return mixed
   */
  public function currentUrl() {
    return $this->command('current_url');
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
    $lambdaFunc = function ($data) use ($result) {
      return array($result['page_id'], $data);
    };
    return array_map($lambdaFunc, $result['ids']);
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
   * Click on given coordinates, THIS DOES NOT depend on the page, it just clicks on where we are right now
   * @param $coordX
   * @param $coordY
   * @return mixed
   */
  public function clickCoordinates($coordX, $coordY) {
    return $this->command('click_coordinates', $coordX, $coordY);
  }

  /**
   * Evaluates a script on the browser
   * @param $script
   * @return mixed
   */
  public function evaluate($script) {
    return $this->command('evaluate', $script);
  }

  /**
   * Executes a script on the browser
   * @param $script
   * @return mixed
   */
  public function execute($script) {
    return $this->command('execute', $script);
  }

  /**
   * @param $handle
   * @return mixed
   */
  public function withinFrame($handle) {
    //TODO: recheck this stuff as i'm not sure how it works
    return false;
  }

  /**
   * TODO: Don't know yet what this command does
   * @return mixed
   */
  public function windowHandle() {
    return $this->command('window_handle');
  }

  /**
   * TODO: Don't know yet what this command does
   * @return mixed
   */
  public function windowHandles() {
    return $this->command('window_handles');
  }

  /**
   * Change the browser focus to another window
   * @param $windowName
   * @return mixed
   */
  public function switchToWindow($windowName) {
    return $this->command('switch_to_window', $windowName);
  }

  /**
   * Opens a new window on the browser
   * @return mixed
   */
  public function openNewWindow() {
    return $this->command('open_new_window');
  }

  /**
   * Closes a window on the browser by a given name
   * @param $windowName
   * @return mixed
   */
  public function closeWindow($windowName) {
    return $this->command('close_window', $windowName);
  }

  /**
   * TODO: Not sure how to use this stuff
   * @param $windowName
   * @return bool
   */
  public function withinWindow($windowName) {
    return false;
  }

  /**
   * Click on a given page and element
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function click($pageId, $elementId) {
    return $this->command('click', $pageId, $elementId);
  }

  /**
   * Triggers a right click on a page an element
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function rightClick($pageId, $elementId) {
    return $this->command('right_click', $pageId, $elementId);
  }

  /**
   * Triggers a double click in a given page and element
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function doubleClick($pageId, $elementId) {
    return $this->command('double_click', $pageId, $elementId);
  }

  /**
   * Hovers over an element in a given page
   * @param $pageId
   * @param $elementId
   * @return mixed
   */
  public function hover($pageId, $elementId) {
    return $this->command('hover', $pageId, $elementId);
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
   * Scrolls the page by a given left and top coordinates
   * @param $left
   * @param $top
   * @return mixed
   */
  public function scrollTo($left, $top) {
    return $this->command('scroll_to', $left, $top);
  }

  /**
   * Check and fix render options
   * @param $options
   * @return mixed
   */
  protected function checkRenderOptions($options) {
    if (isset($options["full"]) && $options["full"] === true) {
      if (isset($options["selector"])) {
        $options["selector"] = null;
      }
    }
    if (!isset($options["full"])) {
      //if not defined then we assume false
      $options["full"] = false;
    }
    return $options;
  }

  /**
   * Renders a page or selection to a file given by path
   * @param string $path
   * @param array  $options
   * @return mixed
   */
  public function render($path, $options = array()) {
    $fixedOptions = $this->checkRenderOptions($options);
    return $this->command('render', $path, $fixedOptions["full"], $fixedOptions["selector"]);
  }

  /**
   * Renders base64 a page or selection to a file given by path
   * @param       $path
   * @param array $options
   * @return mixed
   */
  public function renderBase64($path, $options = array()) {
    $fixedOptions = $this->checkRenderOptions($options);
    return $this->command('render_base64', $path, $fixedOptions["full"], $fixedOptions["selector"]);
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
   * Sets the paper size, useful when saving to PDF
   * @param $paperSize
   * @return mixed
   */
  public function setPaperSize($paperSize) {
    return $this->command('set_paper_size', $paperSize);
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
   * @param $pageId
   * @param $elementId
   * @param $keys
   * @return mixed
   */
  public function sendKeys($pageId, $elementId, $keys) {
    return $this->command('send_keys', $pageId, $elementId, $this->normalizeKeys($keys));
  }

  /**
   * TODO: yet to do the Request, Response objects that original poltergeist does
   * @return mixed
   */
  public function networkTraffic() {
    $networkTraffic = $this->command('network_traffic');
    return $networkTraffic;
  }

  /**
   * Clear the network traffic data stored on the phantomjs code
   * @return mixed
   */
  public function clearNetworkTraffic() {
    return $this->command('clear_network_traffic');
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
   * Returns the headers of the current page
   * @return mixed
   */
  public function getHeaders() {
    return $this->command('get_headers');
  }

  /**
   * Given an array of headers, set such headers on the current page state
   * @param array $headers
   * @return mixed
   */
  public function setHeaders($headers) {
    return $this->command('set_headers', $headers);
  }

  /**
   * Adds headers to current page overriding the existing ones
   * @param $headers
   * @return mixed
   */
  public function addHeaders($headers) {
    return $this->command('add_headers', $headers);
  }

  /**
   * Adds a header to the page making it permanent if needed
   * @param $header
   * @param $permanent
   * @return mixed
   */
  public function addHeader($header, $permanent) {
    return $this->command('add_header', $header, $permanent);
  }

  /**
   * Gets the response headers after a request
   * @return mixed
   */
  public function responseHeaders() {
    return $this->command('response_headers');
  }

  /**
   * Gets the cookies on the browser
   * @return array
   */
  public function cookies() {
    $cookies = $this->command('cookies');
    $objCookies = array();
    foreach ($cookies as $cookie) {
      $objCookies[$cookie["name"]] = new Cookie($cookie);
    }
    return $objCookies;
  }

  /**
   * Sets a cookie on the browser, expires times is set in seconds
   * @param $cookie
   * @return mixed
   */
  public function setCookie($cookie) {
    if (isset($cookie["expires"])) {
      $cookie["expires"] = intval($cookie["expires"]) * 1000;
    }
    return $this->command('set_cookie', $cookie);
  }

  /**
   * Deletes a cookie on the browser if exists
   * @param $cookieName
   * @return bool
   */
  public function removeCookie($cookieName) {
    return $this->command('remove_cookie', $cookieName);
  }

  /**
   * Clear all the cookies
   * @return bool
   */
  public function clearCookies() {
    return $this->command('clear_cookies');
  }

  /**
   * Enables or disables the cookies con phantomjs
   * @param bool $enabled
   * @return bool
   */
  public function cookiesEnabled($enabled = true) {
    return $this->command('cookies_enabled', $enabled);
  }

  /**
   * Sets basic HTTP authentication
   * @param $user
   * @param $password
   * @return bool
   */
  public function setHttpAuth($user, $password) {
    return $this->command('set_http_auth', $user, $password);
  }

  /**
   * Set whether to fail or not on javascript errors found on the page
   * @param bool $enabled
   * @return bool
   */
  public function jsErrors($enabled = true) {
    return $this->command('set_js_errors', $enabled);
  }

  /**
   * Add desired extensions to phantomjs
   * @param $extensions
   */
  public function extensions($extensions) {
    foreach ($extensions as $extensionName) {
      $this->command('add_extension', $extensionName);
    }
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

  /**
   * @param $error
   * @return BrowserError
   */
  protected function getErrorClass($error) {
    $errorClassMap = array(
      'Poltergeist.JavascriptError'   => "JavascriptError",
      'Poltergeist.FrameNotFound'     => "FrameNotFound",
      'Poltergeist.InvalidSelector'   => "InvalidSelector",
      'Poltergeist.StatusFailError'   => "StatusFailError",
      'Poltergeist.NoSuchWindowError' => "NoSuchWindowError"
    );

    if (isset($error['error']['name']) && isset($errorClassMap[$error["error"]["name"]])) {
      return new $errorClassMap[$error["error"]["name"]]($error);
    }

    return new BrowserError($error);
  }

  /**
   * Goes back on the browser history if possible
   * @return bool
   * @throws BrowserError
   * @throws \Exception
   */
  public function goBack() {
    return $this->command('go_back');
  }

  /**
   * Goes forward on the browser history if possible
   * @return mixed
   * @throws BrowserError
   * @throws \Exception
   */
  public function goForward() {
    return $this->command('go_forward');
  }

  /**
   * Sends a command to the browser
   * @throws BrowserError
   * @throws \Exception
   * @return mixed
   */
  public function command() {
    try {
      $args = func_get_args();
      $commandName = $args[0];
      array_shift($args);
      $messageToSend = json_encode(array('name' => $commandName, 'args' => $args));
      //TODO: log message here
      $response = $this->getServer()->send($messageToSend);
      if (($jsonResponse = json_decode($response, true)) === null) {
        //TODO: add a proper exception class
        throw new \Exception("Could not decode the phantomjs server response");
      }
    } catch (\Exception $e) {
      $this->restart();
      throw $e;
    }
    if (isset($jsonResponse['error'])) {
      throw $this->getErrorClass($jsonResponse);
    }
    return $jsonResponse['response'];
  }

}
