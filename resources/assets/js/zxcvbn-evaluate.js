$(document).ready(function () {
  evaluate();
  $("#password").keyup(function (event) {
    evaluate();
  });

  function evaluate() {
    var password = $("#password").val();
    if (password) {
      var result = zxcvbn(password);
      switch (result.score) {
        case 0:
          updateText("Sehr schwach"); break;
        case 1:
          updateText("Schwach"); break;
        case 2:
          updateText("Ok"); break;
        case 3:
          updateText("Stark"); break;
        case 4:
          updateText("Sehr stark"); break;
      }
    } else {
      updateText("-");
    }

    function updateText(text) {
      $("#strength-addon").text(text);
    }
  }
});
