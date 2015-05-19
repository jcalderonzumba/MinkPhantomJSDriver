var __bind = function (fn, me) {
  return function () {
    return fn.apply(me, arguments);
  };
};

Poltergeist.Connection = (function () {
  function Connection(owner, port) {
    this.owner = owner;
    this.port = port;
    this.commandReceived = __bind(this.commandReceived, this);
    this.socket = new WebSocket("ws://127.0.0.1:" + this.port + "/");
    this.socket.onmessage = this.commandReceived;
    this.socket.onclose = function () {
      return phantom.exit();
    };
  }

  Connection.prototype.commandReceived = function (message) {
    if (message.data == "are_you_ready") {
      return this.send({response: "i_am_ready"});
    } else {
      return this.owner.runCommand(JSON.parse(message.data));
    }
  };

  Connection.prototype.send = function (message) {
    return this.socket.send(JSON.stringify(message));
  };

  return Connection;

})();
