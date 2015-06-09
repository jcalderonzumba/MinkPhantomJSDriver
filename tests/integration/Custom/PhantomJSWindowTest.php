<?php

namespace Behat\Mink\Tests\Driver\Js;

use Behat\Mink\Tests\Driver\TestCase;

/**
 * Class PhantomJSWindowTest
 * @package Behat\Mink\Tests\Driver\Js
 */
class PhantomJSWindowTest extends TestCase {

  /**
   *
   */
  public function testResizeWindow() {
    $this->getSession()->visit($this->pathTo('/index.html'));
    $session = $this->getSession();

    $session->resizeWindow(400, 300);
    $session->wait(1000, 'false');
    $javascript = <<<JS
    (function(){
        var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = w.innerWidth || e.clientWidth || g.clientWidth,
        y = w.innerHeight|| e.clientHeight|| g.clientHeight;
        var size = {};
        size["width"]=x;
        size["height"]= y;
        return size;
    }());
JS;
    $pageSize = $session->evaluateScript($javascript);
    $this->assertEquals(400, $pageSize["width"]);
    $this->assertEquals(300, $pageSize["height"]);
  }
}
