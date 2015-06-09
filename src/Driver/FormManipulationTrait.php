<?php

namespace Behat\PhantomJSExtension\Driver;

use Behat\Mink\Exception\DriverException;

/**
 * Trait FormManipulationTrait
 * @package Behat\PhantomJSExtension\Driver
 */
trait FormManipulationTrait {
  /**
   * Submits a form given an xpath selector
   * @param string $xpath
   * @throws DriverException
   */
  public function submitForm($xpath) {
    $element = $this->findElement($xpath, 1);
    $tagName = $this->browser->tagName($element["page_id"], $element["ids"][0]);
    if (strcmp(strtolower($tagName), "form") !== 0) {
      throw new DriverException("Can not submit something that is not a form");
    }
    $this->browser->trigger($element["page_id"], $element["ids"][0], "submit");
  }

  /**
   * Selects an option
   * @param string $xpath
   * @param string $value
   * @param bool   $multiple
   * @throws DriverException
   */
  public function selectOption($xpath, $value, $multiple = false) {
    throw new DriverException("Not yet done until everything else easier is done $xpath $value $multiple");
  }

  /**
   * We click on the checkbox or radio when possible and needed
   * @param string $xpath
   * @throws DriverException
   */
  public function check($xpath) {
    if ($this->isChecked($xpath) === true) {
      return;
    }
    //only when the element is not checked we do stuff
    $elements = $this->findElement($xpath, 1);
    $this->browser->setAttribute($elements["page_id"], $elements["ids"][0], "checked", "checked");
  }

  /**
   * We click on the checkbox or radio when possible and needed
   * @param string $xpath
   * @throws DriverException
   */
  public function uncheck($xpath) {
    if ($this->isChecked($xpath) !== true) {
      return;
    }
    //only when the element is not checked we do stuff
    $elements = $this->findElement($xpath, 1);
    $this->browser->removeAttribute($elements["page_id"], $elements["ids"][0], "checked");
  }

  /**
   * Checks if the radio or checkbox is checked
   * @param string $xpath
   * @return bool
   * @throws DriverException
   */
  public function isChecked($xpath) {
    $elements = $this->findElement($xpath, 1);
    $data = $this->browser->attributes($elements["page_id"], $elements["ids"][0]);
    if ($data["type"] !== "checkbox" && $data["type"] !== "radio") {
      throw new DriverException("Can not check when the element is not checkbox or radio");
    }
    if (!isset($data["checked"])) {
      return false;
    }

    return (strcmp($data["checked"], "checked") === 0);
  }

  /**
   * Checks if the option is selected or not
   * @param string $xpath
   * @return bool
   * @throws DriverException
   */
  public function isSelected($xpath) {
    $elements = $this->findElement($xpath, 1);
    $tagName = $this->browser->tagName($elements["page_id"], $elements["ids"][0]);
    if (strcmp(strtolower($tagName), "option") !== 0) {
      throw new DriverException("Can not assert on element that is not an option");
    }

    $data = $this->browser->attributes($elements["page_id"], $elements["ids"][0]);
    if (!isset($data["selected"])) {
      return false;
    }

    return (strcmp($data["selected"], "selected") === 0);
  }
}
