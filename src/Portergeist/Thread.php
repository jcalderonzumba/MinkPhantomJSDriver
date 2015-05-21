<?php

namespace Behat\PhantomJSExtension\Portergeist;

/**
 * Class Thread
 * @package Behat\PhantomJSExtension\Portergeist
 */
class Thread {
  /** @var resource */
  protected $process;
  protected $pipes;
  /** @var string */
  protected $buffer;
  protected $output;
  protected $error;
  /** @var int */
  protected $timeout;
  /** @var int */
  protected $startTime;
  /** @var  int */
  protected $pid;

  /**
   * Creates a "thread" with the given command
   * @param $command
   * @throws \Exception
   */
  public function __construct($command) {
    $this->buffer = "";
    $this->output = "";
    $this->startTime = time();
    $this->timeout = 0;
    $this->pid = null;
    $descriptor = array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("pipe", "w"));
    //Open the resource to execute $command
    $this->process = proc_open($command, $descriptor, $this->pipes);
    if (!is_resource($this->process)) {
      throw new \Exception("Could not execute command $command in thread");
    }
    //Set stdout and stderr to non-blocking
    stream_set_blocking($this->pipes[1], 0);
    stream_set_blocking($this->pipes[2], 0);

    $status = $this->getStatus();
    if ($status !== null && isset($status["pid"]) && !empty($status["pid"])) {
      $this->pid = intval($status["pid"]);
    }
  }

  /**
   * Returns the status of the process
   * @return array
   */
  public function getStatus() {
    if ($this->process !== null) {
      return proc_get_status($this->process);
    }
    return null;
  }

  /**
   * Closes the opened process and returns the process exit code
   * @return int
   */
  public function close() {
    fclose($this->pipes[0]);
    fclose($this->pipes[1]);
    fclose($this->pipes[2]);
    $exitCode = proc_close($this->process);
    $this->process = null;
    return $exitCode;
  }

  /**
   * Send an action to the process running in background
   * @param $action
   */
  public function sendAction($action) {
    fwrite($this->pipes[0], $action);
  }

  /**
   * @return int
   */
  public function getPid() {
    return $this->pid;
  }

  /**
   * @return string
   */
  public function getError() {
    $buffer = "";
    while (($line = fgets($this->pipes[2], 1024))) {
      $buffer .= $line;
    }
    return $buffer;
  }

  /**
   * Get the command output produced so far
   * @return string
   */
  function listen() {
    $buffer = $this->buffer;
    $this->buffer = "";
    while (($line = fgets($this->pipes[1], 1024))) {
      $buffer .= $line;
      $this->output .= $line;
    }
    return $buffer;
  }
}
