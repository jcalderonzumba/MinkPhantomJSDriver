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
   * @param $serverOptions
   * @param $workingDir
   */
  private function __construct($serverOptions, $workingDir) {
    $this->process = new Process("php -S 127.0.0.1:6789 $serverOptions", $workingDir);
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
   * @param        $serverOptions
   * @param string $workingDir
   * @return LocalWebServer
   */
  public static function getInstance($serverOptions, $workingDir = __DIR__) {
    if (null === self::$instance) {
      self::$instance = new self($serverOptions, $workingDir);
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
