<?php
namespace Behat\PhantomJSExtension\Tests\Server;

use Symfony\Component\Process\Process;

/**
 * Class LocalWebServer
 * @package Behat\PhantomJSExtension\Tests\Server
 */
class LocalWebServer {
  /** @var  Process */
  protected $process;
  /** @var LocalWebServer */
  private static $instance = null;

  /**
   * Private constructor for an local web server instance
   */
  private function __construct() {
    $this->process = new Process("php -S 127.0.0.1:6789 -t www/web/ www/web/index.php", __DIR__);
    $this->process->start();
    $this->waitForServerStart();
  }

  protected function waitForServerStart() {
    $notReady = true;
    echo "Waiting for local server to startup...\n";
    while ($notReady) {
      $sock = @fsockopen("127.0.0.1", 6789, $errno, $errstr, 5);
      if (is_resource($sock)) {
        fclose($sock);
        $notReady = false;
      } else {
        echo "Not ready yet $errno, $errstr\n";
        sleep(1);
      }
    }
    echo "Local server ready to start testing...\n";
  }

  /**
   * Creates or returns the local server instance
   * @return LocalWebServer
   */
  public static function getInstance() {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * @return Process
   */
  public function getProcess() {
    return $this->process;
  }
}
