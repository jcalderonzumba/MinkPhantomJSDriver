<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Trait BrowserAuthenticationTrait
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
trait BrowserAuthenticationTrait {
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
