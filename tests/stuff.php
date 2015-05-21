<?php

function stuff(Browser $browser) {
  var_dump($browser->visit("http://ft.devsnt.com/"));
  var_dump($browser->currentUrl());
  $elements = $browser->find("xpath", '//*[@id="index_mobile"]/table/tbody/tr[1]');
  $text = $browser->allHtml($elements["page_id"], $elements["ids"][0], "inner");
  print_r($elements);
  echo $text;
  $text = $browser->allHtml($elements["page_id"], $elements["ids"][0], "outer");
  echo $text;
  var_dump($browser->visit("http://ft.devsnt.com/newad"));
  var_dump($browser->currentUrl());
  $elements = $browser->find("xpath", '//*[@id="no_telemarketers"]');
  print_r($elements);
  $data = $browser->attributes($elements["page_id"], $elements["ids"][0]);
  print_r($data);
  print_r($browser->tagName($elements["page_id"], $elements["ids"][0]));

  $elements = $browser->find("xpath", '//*[@id="phone_hidden"]');
  print_r($elements);
  $data = $browser->attributes($elements["page_id"], $elements["ids"][0]);
  print_r($data);
  var_dump($browser->setAttribute($elements["page_id"], $elements["ids"][0], "checked", "checked"));
  $elements = $browser->find("xpath", '//*[@id="phone_hidden"]');
  print_r($elements);
  $data = $browser->attributes($elements["page_id"], $elements["ids"][0]);
  print_r($data);
  var_dump($browser->removeAttribute($elements["page_id"], $elements["ids"][0], "checked"));
  $elements = $browser->find("xpath", '//*[@id="phone_hidden"]');
  print_r($elements);
  $data = $browser->attributes($elements["page_id"], $elements["ids"][0]);
  print_r($data);
}
