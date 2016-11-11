<?php

namespace Behat\Mink\Tests\Driver;

use Zumba\Mink\Driver\PhantomJSDriver;

/**
 * Class PhantomJSConfig
 * @package Behat\Mink\Tests\Driver
 */
class PhantomJSConfig extends AbstractConfig {

  /**
   * @return PhantomJSConfig
   */
  public static function getInstance() {
    return new self();
  }

  /**
   * @return bool
   */
  protected function supportsCss() {
    return true;
  }

  /**
   * @return bool
   */
  protected function supportsJs() {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function createDriver() {
    $phantomJSHost = $_SERVER["DRIVER_URL"];
    return new PhantomJSDriver($phantomJSHost, $_SERVER["TEMPLATE_CACHE_DIR"]);
  }

  /**
   * @param string $testCase
   * @param string $test
   * @return string
   */
  public function skipMessage($testCase, $test) {
    if ($testCase == "Behat\\Mink\\Tests\\Driver\\Basic\\BasicAuthTest" && $test == "testSetBasicAuth") {
      //TODO: Fix this error
      return "TODO: figure out why when sending a bad user is still giving the good login";
    }

    if ($testCase == "Behat\\Mink\\Tests\\Driver\\Form\\Html5Test" && $test == "testHtml5Types") {
      //TODO: Fix this.
      return "TODO: phantomjs does not seem to deal with the color type properly";
    }

    return parent::skipMessage($testCase, $test);
  }

}
