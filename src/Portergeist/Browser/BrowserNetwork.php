<?php

namespace Behat\PhantomJSExtension\Portergeist\Browser;

/**
 * Class BrowserNetwork
 * @package Behat\PhantomJSExtension\Portergeist\Browser
 */
class BrowserNetwork extends BrowserAuthentication {
  /**
   * TODO: yet to do the Request, Response objects that original poltergeist does
   * @return mixed
   */
  public function networkTraffic() {
    $networkTraffic = $this->command('network_traffic');
    return $networkTraffic;
  }

  /**
   * Clear the network traffic data stored on the phantomjs code
   * @return mixed
   */
  public function clearNetworkTraffic() {
    return $this->command('clear_network_traffic');
  }
}
