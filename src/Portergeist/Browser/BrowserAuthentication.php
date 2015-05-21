<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Class BrowserAuthentication
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
class BrowserAuthentication extends BrowserHeaders {
  /**
   * Sets basic HTTP authentication
   * @param $user
   * @param $password
   * @return bool
   */
  public function setHttpAuth($user, $password) {
    return $this->command('set_http_auth', $user, $password);
  }
}
