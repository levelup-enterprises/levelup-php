///////////////////////////////////////////
// Alerts
///////////////////////////////////////////

function alertBox(cond, text) {
  $("#alert")
    .addClass(cond)
    .html(text),
    $("#alert").show();
  function fade() {
    $("#alert").fadeOut(1000);
  }
  setTimeout(fade, 2000);
}

function clearAlert() {
  $("#alert")
    .removeClass()
    .html(""),
    $("#alert").hide();
}
