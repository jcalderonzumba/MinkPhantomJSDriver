function injectedFunction(documentForm) {
  document.getElementById("element_1").value = "THIS_IS_SPARTA";
  document.getElementById("element_3").selectedIndex = 1;
  return documentForm.submit();
}
