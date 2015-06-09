<?php

namespace Behat\PhantomJSExtension\Tests;

/**
 * Class BrowserCookiesTest
 * @package Behat\PhantomJSExtension\Tests
 */
class BrowserCookiesTest extends BrowserCommandsTestCase {

  public function testCookiesAreEmpty() {
    $this->assertEmpty($this->browser->cookies());
  }

  public function testCookiesAreNotEmpty() {
    $this->visitUrl($this->getTestPageBaseUrl() . "/testCookiesAreNotEmpty/");
    $cookies = $this->browser->cookies();
    $this->assertCount(2, $cookies);
    foreach ($cookies as $cookie) {
      $this->assertInstanceOf('Behat\PhantomJSExtension\Portergeist\Cookie', $cookie);
    }
  }

  public function testClearCookies() {
    //First we visit the page with cookies
    $this->testCookiesAreNotEmpty();
    //Then we issue a cookie clear
    $this->assertTrue($this->browser->clearCookies());
    //Then if we ask again it should be empty
    $this->assertEmpty($this->browser->cookies());
    //Then if we ask the basic page it should be empty
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertEmpty($this->browser->cookies());
  }

  public function testRemoveCookie() {
    //First we visit the page with cookies
    $this->testCookiesAreNotEmpty();
    //Then we issue the cookie removal with something that does not exists
    $this->assertTrue($this->browser->removeCookie("DOES_NOT_EXITS"));
    $this->assertCount(2, $this->browser->cookies());
    //Now we issue a cookie removal that exists
    $this->assertTrue($this->browser->removeCookie("a_cookie"));
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertCount(1, $this->browser->cookies());
  }

  public function testSetCookie() {
    $cookie = array("name" => "mycookie", "value" => "myvalue", "path" => "/", "domain" => "127.0.0.1");
    $this->visitUrl($this->getTestPageBaseUrl() . "/static/basic.html");
    $this->assertEmpty($this->browser->cookies());
    $this->assertTrue($this->browser->setCookie($cookie));
    $this->browser->reload();
    $this->assertArrayHasKey("mycookie", $this->browser->cookies());
  }

  public function testCookiesDisabled() {
    $this->assertTrue($this->browser->cookiesEnabled(false));
    $this->visitUrl($this->getTestPageBaseUrl() . "/testCookiesAreNotEmpty/");
    //Should be zero since we have disabled the cookies
    $this->assertEmpty($this->browser->cookies());
  }

  public function testCookiesEnabled(){
    $this->assertTrue($this->browser->cookiesEnabled(true));
    $this->testCookiesAreNotEmpty();
  }

}
