<?php

namespace Behat\PhantomJSExtension\Portergeist\Exception;


/**
 * Class JavascriptError
 * @package Behat\PhantomJSExtension\Portergeist\Exception
 */
class JavascriptError extends ClientError {

  /**
   * Get the javascript errors found during the use of the phantomjs
   * @return array
   */
  public function javascriptErrors() {
    $jsErrors = array();
    $errors = current(reset($this->response["args"]));
    foreach ($errors as $error) {
      $jsErrors[] = new JSErrorItem($error["message"], $error["stack"]);
    }
    return $jsErrors;
  }

  /**
   * Returns the javascript errors found
   * @return string
   */
  public function message() {
    //TODO: add each javascript error
    return "One or more errors were raised in the Javascript code on the page.
            If you don't care about these errors, you can ignore them by
            setting js_errors: false in your Poltergeist configuration (see documentation for details).";
  }
}