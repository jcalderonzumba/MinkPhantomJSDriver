<?php

namespace Behat\PhantomJSExtension\Driver;

use Behat\Mink\Driver\CoreDriver;
use Behat\Mink\Exception\DriverException;
use Behat\Mink\Session;
use Behat\PhantomJSExtension\Portergeist\Browser\Browser;

/**
 * Class BasePhantomJSDriver
 * @package Behat\PhantomJSExtension\Driver
 */
class BasePhantomJSDriver extends CoreDriver {

  /** @var  Session */
  protected $session;
  /** @var  Browser */
  protected $browser;
  /** @var  string */
  protected $phantomHost;
  /** @var  \Twig_Loader_Filesystem */
  protected $templateLoader;
  /** @var  \Twig_Environment */
  protected $templateEnv;

  /**
   * Instantiates the driver
   * @param $phantomHost      browser "api" oriented host
   */
  public function __construct($phantomHost) {
    \Twig_Autoloader::register();
    $this->phantomHost = $phantomHost;
    $this->browser = new Browser($phantomHost);
    $this->templateLoader = new \Twig_Loader_Filesystem(realpath(__DIR__ . '/../Resources/Script'));
    $this->templateEnv = new \Twig_Environment($this->templateLoader, array('cache' => '/tmp/jcalderonzumba/phantomjs', 'strict_variables' => true));
  }

  /**
   * Helper to find a node element given an xpath
   * @param string $xpath
   * @param int    $max
   * @returns int
   * @throws DriverException
   */
  protected function findElement($xpath, $max = 1) {
    $elements = $this->browser->find("xpath", $xpath);
    if (!isset($elements["page_id"]) || !isset($elements["ids"]) || count($elements["ids"]) !== $max) {
      throw new DriverException("Failed to get elements with given $xpath");
    }
    return $elements;
  }

  /**
   * {@inheritdoc}
   * @param Session $session
   */
  public function setSession(Session $session) {
    $this->session = $session;
  }

  /**
   * @return Browser
   */
  public function getBrowser() {
    return $this->browser;
  }

  /**
   * @return \Twig_Environment
   */
  public function getTemplateEnv() {
    return $this->templateEnv;
  }

  /**
   * Returns a javascript script via twig template engine
   * @param $templateName
   * @param $viewData
   * @return string
   */
  public function javascriptTemplateRender($templateName, $viewData) {
    /** @var $templateEngine \Twig_Environment */
    $templateEngine = $this->getTemplateEnv();
    return $templateEngine->render($templateName, $viewData);
  }

}
