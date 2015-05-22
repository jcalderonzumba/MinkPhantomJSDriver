Poltergeist = (function () {
  function Poltergeist(port, width, height) {
    var that;
    this.browser = new Poltergeist.Browser(this, width, height);
    this.connection = new Poltergeist.Connection(this, port);

    this.commandServer = new Poltergeist.Server(6085);
    this.commandServer.start();

    that = this;
    phantom.onError = function (message, stack) {
      return that.onError(message, stack);
    };
    this.running = false;
  }

  //SERVER COMMAND RUNNING START

  /**
   * Tries to execute a command send by a client and returns the command response
   * or error if something happened
   * @param command
   * @param serverResponse
   * @return {boolean}
   */
  Poltergeist.prototype.serverRunCommand = function (command, serverResponse) {
    var error, commandResponse;
    this.running = true;
    try {
      return this.browser.serverRunCommand(command, serverResponse);
    } catch (_error) {
      error = _error;
      if (error instanceof Poltergeist.Error) {
        return this.serverSendError(error, serverResponse);
      } else {
        return this.serverSendError(new Poltergeist.BrowserError(error.toString(), error.stack), serverResponse);
      }
    }
  };

  /**
   * Sends error back to the client
   * @param error
   * @param serverResponse
   * @return {boolean}
   */
  Poltergeist.prototype.serverSendError = function (error, serverResponse) {
    var errorObject = {
      error: {
        name: error.name || 'Generic',
        args: error.args && error.args() || [error.toString()]
      }
    };
    return this.commandServer.sendError(serverResponse, 500, errorObject);
  };

  /**
   * Send the response back to the client
   * @param response        Data to send to the client
   * @param serverResponse  Phantomjs response object associated to the client request
   * @return {boolean}
   */
  Poltergeist.prototype.serverSendResponse = function (response, serverResponse) {
    return this.commandServer.send(serverResponse, {response: response});
  };

  //SERVER COMMAND RUNNING END


  Poltergeist.prototype.runCommand = function (command) {
    var error;
    this.running = true;
    try {
      return this.browser.runCommand(command.name, command.args);
    } catch (_error) {
      error = _error;
      if (error instanceof Poltergeist.Error) {
        return this.sendError(error);
      } else {
        return this.sendError(new Poltergeist.BrowserError(error.toString(), error.stack));
      }
    }
  };

  Poltergeist.prototype.sendResponse = function (response) {
    return this.send({
      response: response
    });
  };

  Poltergeist.prototype.sendError = function (error) {
    return this.send({
      error: {
        name: error.name || 'Generic',
        args: error.args && error.args() || [error.toString()]
      }
    });
  };

  Poltergeist.prototype.send = function (data) {
    if (this.running) {
      this.connection.send(data);
      return this.running = false;
    }
  };

  return Poltergeist;

})();

window.Poltergeist = Poltergeist;
