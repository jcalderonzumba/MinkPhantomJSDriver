<?php

namespace Behat\PhantomJSExtension\Portergeist\Exception;


/**
 * Class BrowserError
 * @package Behat\PhantomJSExtension\Portergeist\Exception
 */
class BrowserError extends ClientError {

  /**
   * Gets the name of the browser error
   * @return string
   */
  public function getName() {
    return $this->response["name"];
  }

  /**
   * @return JSErrorItem
   */
  public function javascriptError() {
    //TODO: this need to be check, i don't know yet what comes in response
    return new JSErrorItem($this->response["args"][0], $this->response["args"][1]);
  }

  /**
   * Returns error message
   * TODO: check how to proper implement if we have exceptions
   * @return string
   */
  public function message() {
    return "There was an error inside the PhantomJS portion of Portergeist.
            This is probably a bug, so please report it\n\n" . $this->javascriptError();
  }
}
