(function () {
  function getElement(xpath) {
    var result;
    result = document.evaluate('//*[@id="category_group"]', document, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
    if (result.snapshotLength !== 1) {
      return null;
    }
    return result.snapshotItem(0);
  }

  function isSelect(element) {
    if (element === null) {
      return false;
    }
    return (element.tagName.toLowerCase() == "select");
  }

  function isRadioInput(element) {
    if (element === null) {
      return false;
    }
    return ((element.tagName.toLowerCase() == "input") && element.getAttribute("type").toLowerCase() == "radio");
  }

  function doOptionSelect(element, value) {
    var i;
    for (i = 0; element.options.length; i++) {
      if (element.options[i].value == value) {
        element.selectedIndex = i;
        return true;
      }
    }
    return false;
  }

  var element = getElement("whatever");
  if (!isSelect(element) && !isRadioInput(element)) {
    return false;
  }

  if (isSelect(element)) {
    return doOptionSelect(element, "2010");
  }

  if (isRadioInput(element)) {
    return doRadioSelect(element, "2010");
  }

  return false;
}());
