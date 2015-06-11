<?php

namespace Behat\PhantomJSExtension\ServiceContainer\Driver;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class PhantomJSFactory
 * @package Behat\PhantomJSExtension\ServiceContainer\Driver
 */
class PhantomJSFactory implements DriverFactory {

  /**
   * Gets the name of the driver being configured.
   *
   * This will be the key of the configuration for the driver.
   *
   * @return string
   */
  public function getDriverName() {
    return 'phantomjs';
  }

  /**
   * Defines whether a session using this driver is eligible as default javascript session
   *
   * @return boolean
   */
  public function supportsJavascript() {
    return true;
  }

  /**
   * Setups configuration for the driver factory.
   *
   * @param ArrayNodeDefinition $builder
   */
  public function configure(ArrayNodeDefinition $builder) {
    $children = $builder->children();
    $children->scalarNode('phantom_server')->isRequired()->end();
  }

  /**
   * Builds the service definition for the driver.
   *
   * @param array $config
   *
   * @return Definition
   */
  public function buildDriver(array $config) {
    if (!class_exists('Behat\PhantomJSExtension\Driver\PhantomJSDriver')) {
      throw new \RuntimeException(
        'Install PhantomJSDriver in order to use phantomjs driver.'
      );
    }
    return new Definition('Behat\PhantomJSExtension\Driver\PhantomJSDriver',
      array($config["phantom_server"])
    );
  }
}
