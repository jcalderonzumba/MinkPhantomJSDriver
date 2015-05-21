<?php

namespace Behat\PhantomJSExtension\Portergeist;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Class WebSocketServer
 * @package Behat\PhantomJSExtension\Portergeist
 */
class WebSocketServer implements MessageComponentInterface {
  /** @var \SplObjectStorage */
  protected $clients;

  /**
   * Constructor
   * TODO: add a proper logging method, not only to stdout
   */
  public function __construct() {
    $this->clients = new \SplObjectStorage;
  }

  /**
   * Handles the opened connection from a client
   * @param ConnectionInterface $conn
   */
  public function onOpen(ConnectionInterface $conn) {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);
    echo "New connection! ({$conn->resourceId})\n";
  }

  /**
   * Message received handler to send to other clients
   * @param ConnectionInterface $from
   * @param string              $msg
   */
  public function onMessage(ConnectionInterface $from, $msg) {
    $numRecv = count($this->clients) - 1;
    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n", $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
    foreach ($this->clients as $client) {
      if ($from !== $client) {
        // The sender is not the receiver, send to each client connected
        $client->send($msg);
      }
    }
  }

  /**
   * On connection close handler
   * @param ConnectionInterface $conn
   */
  public function onClose(ConnectionInterface $conn) {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->clients->detach($conn);
    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  /**
   * Handles stuff on error
   * @param ConnectionInterface $conn
   * @param \Exception          $error
   */
  public function onError(ConnectionInterface $conn, \Exception $error) {
    echo "An error has occurred: {$error->getMessage()}\n";
    $conn->close();
  }
}
