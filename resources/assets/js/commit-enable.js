function checkChecks() {
  var allChecked = true;
  $(".commit-checkbox").each(function() {
    var checked = this.checked;
    allChecked = allChecked && checked;
  });
  return allChecked;
}

function setButtonState(enabled) {
  if (enabled) {
    $("#commit-next").removeClass("disabled");
  } else {
    $("#commit-next").addClass("disabled");
  }
}

$(document).ready(function() {

  setButtonState(checkChecks());
  $(".commit-checkbox").change(function() {
    setButtonState(checkChecks());
  });

});
