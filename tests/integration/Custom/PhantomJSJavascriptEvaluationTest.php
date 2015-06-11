<?php

namespace Behat\Mink\Tests\Driver\Basic;


use Behat\Mink\Tests\Driver\TestCase;

/**
 * Class PhantomJSJavascriptEvaluationTests
 * @package Behat\Mink\Tests\Driver\Basic
 */
class PhantomJSJavascriptEvaluationTest extends TestCase {
  /**
   * @dataProvider phantomJSProvideExecutedScript
   * @param $script
   */
  public function testExecuteScript($script) {
    $this->getSession()->visit($this->pathTo('/index.html'));

    $this->getSession()->executeScript($script);

    sleep(1);

    $heading = $this->getAssertSession()->elementExists('css', 'h1');
    $this->assertEquals('Hello world', $heading->getText());
  }

  /**
   * @return array
   */
  public function phantomJSProvideExecutedScript() {
    return array(
      array('document.querySelector("h1").textContent = "Hello world"'),
      array('document.querySelector("h1").textContent = "Hello world";'),
      array('(function () {document.querySelector("h1").textContent = "Hello world";})()'),
      array('(function () {document.querySelector("h1").textContent = "Hello world";})();'),
    );
  }

  /**
   * @dataProvider phantomJSProvideEvaluatedScript
   * @param $script
   */
  public function testEvaluateJavascript($script) {
    $this->getSession()->visit($this->pathTo('/index.html'));

    $this->assertSame(2, $this->getSession()->evaluateScript($script));
  }

  /**
   * @return array
   */
  public function phantomJSProvideEvaluatedScript() {
    return array(
      array('1 + 1'),
      array('1 + 1;'),
      array('(function () {return 1+1;})()'),
      array('(function () {return 1+1;})();'),
      array('(function () {return function () { return 1+1;}();})();'),
    );
  }
}
