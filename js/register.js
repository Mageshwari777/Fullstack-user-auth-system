/**
 * Registration page logic.
 * Uses jQuery AJAX (no form submission).
 */

$(function () {
  $("#registerBtn").on("click", function (e) {
    e.preventDefault();

    const payload = {
      username: $("#username").val().trim(),
      email: $("#email").val().trim(),
      password: $("#password").val(),
    };

    $("#msg").removeClass().addClass("alert alert-info").text("Creating account...").show();

    $.ajax({
      url: "../php/register.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(payload),
      dataType: "json",
    })
      .done(function (res) {
        if (res.ok) {
          $("#msg").removeClass().addClass("alert alert-success").text(res.message).show();
          setTimeout(function () {
            window.location.href = "../html/login.html";
          }, 700);
        } else {
          $("#msg").removeClass().addClass("alert alert-danger").text(res.message || "Registration failed").show();
        }
      })
      .fail(function (xhr) {
        const msg = (xhr.responseJSON && xhr.responseJSON.message) || "Registration failed";
        $("#msg").removeClass().addClass("alert alert-danger").text(msg).show();
      });
  });
});

