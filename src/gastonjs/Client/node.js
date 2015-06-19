var __slice = [].slice;

Poltergeist.Node = (function () {
  var name, _fn, _i, _len, _ref;

  Node.DELEGATES = ['allText', 'visibleText', 'getAttribute', 'value', 'set',
    'setAttribute', 'isObsolete', 'removeAttribute', 'isMultiple',
    'select', 'tagName', 'find', 'getAttributes', 'isVisible',
    'position', 'trigger', 'parentId', 'parentIds', 'mouseEventTest',
    'scrollIntoView', 'isDOMEqual', 'isDisabled', 'deleteText',
    'containsSelection', 'allHTML', 'changed'];

  function Node(page, id) {
    this.page = page;
    this.id = id;
  }

  /**
   * Returns the parent Node of this Node
   * @return {Poltergeist.Node}
   */
  Node.prototype.parent = function () {
    return new Poltergeist.Node(this.page, this.parentId());
  };

  _ref = Node.DELEGATES;

  _fn = function (name) {
    return Node.prototype[name] = function () {
      var args = [];
      if (arguments.length >= 1) {
        args = __slice.call(arguments, 0)
      }
      return this.page.nodeCall(this.id, name, args);
    };
  };

  //Adding all the delegates from the agent Node to this Node
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    name = _ref[_i];
    _fn(name);
  }

  /**
   *  Gets an x,y position tailored for mouse event actions
   * @return {{x, y}}
   */
  Node.prototype.mouseEventPosition = function () {
    var middle, pos, viewport;

    viewport = this.page.viewportSize();
    pos = this.position();
    middle = function (start, end, size) {
      return start + ((Math.min(end, size) - start) / 2);
    };

    return {
      x: middle(pos.left, pos.right, viewport.width),
      y: middle(pos.top, pos.bottom, viewport.height)
    };
  };

  /**
   * Executes a phantomjs native mouse event
   * @param name
   * @return {{x, y}}
   */
  Node.prototype.mouseEvent = function (name) {
    var pos, test;

    this.scrollIntoView();
    pos = this.mouseEventPosition();
    test = this.mouseEventTest(pos.x, pos.y);

    if (test.status === 'success') {
      if (name === 'rightclick') {
        this.page.mouseEvent('click', pos.x, pos.y, 'right');
        this.trigger('contextmenu');
      } else {
        this.page.mouseEvent(name, pos.x, pos.y);
      }
      return pos;
    } else {
      throw new Poltergeist.MouseEventFailed(name, test.selector, pos);
    }
  };

  /**
   * Executes a mouse based drag from one node to another
   * @param other
   * @return {{x, y}}
   */
  Node.prototype.dragTo = function (other) {
    var otherPosition, position;

    this.scrollIntoView();
    position = this.mouseEventPosition();
    otherPosition = other.mouseEventPosition();
    this.page.mouseEvent('mousedown', position.x, position.y);
    return this.page.mouseEvent('mouseup', otherPosition.x, otherPosition.y);
  };

  /**
   * Checks if one node is equal to another
   * @param other
   * @return {boolean}
   */
  Node.prototype.isEqual = function (other) {
    return this.page === other.page && this.isDOMEqual(other.id);
  };

  return Node;

}).call(this);
