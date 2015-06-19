<?php

namespace Zumba\PhantomJSExtension\ServiceContainer;

use Zumba\PhantomJSExtension\ServiceContainer\Driver\PhantomJSFactory;
use Behat\MinkExtension\ServiceContainer\MinkExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class PhantomJSExtension
 * @package Zumba\PhantomJSExtension\ServiceContainer
 */
class PhantomJSExtension implements ExtensionInterface {

  /**
   * {@inheritdoc}
   * @return string
   */
  public function getConfigKey() {
    return 'phantomjs';
  }

  /**
   * {@inheritdoc}
   * @param ExtensionManager $extensionManager
   */
  public function initialize(ExtensionManager $extensionManager) {
    /** @var MinkExtension $minkExtension */
    $minkExtension = $extensionManager->getExtension('mink');
    if (null !== $minkExtension) {
      $minkExtension->registerDriverFactory(new PhantomJSFactory());
    }
  }

  /**
   * {@inheritdoc}
   * @param ArrayNodeDefinition $builder
   */
  public function configure(ArrayNodeDefinition $builder) {
  }

  /**
   * @param ContainerBuilder $container
   */
  public function process(ContainerBuilder $container) {

  }

  /**
   * {@inheritdoc}
   * @param ContainerBuilder $container
   * @param array            $config
   */
  public function load(ContainerBuilder $container, array $config) {

  }
}
