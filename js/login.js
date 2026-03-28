/**
 * Login page logic.
 * Uses jQuery AJAX only.
 * On success: store session token in localStorage and redirect to profile page.
 */

$(function () {
  // If already logged in, go straight to profile
  const existing = getSession();
  if (existing && existing.token) {
    window.location.href = "../html/profile.html";
    return;
  }

  $("#loginBtn").on("click", function (e) {
    e.preventDefault();

    const payload = {
      identity: $("#identity").val().trim(),
      password: $("#password").val(),
    };

    $("#msg").removeClass().addClass("alert alert-info").text("Logging in...").show();

    $.ajax({
      url: "../php/login.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(payload),
      dataType: "json",
    })
      .done(function (res) {
        if (res.ok && res.session && res.session.token) {
          setSession({
            token: res.session.token,
            user: res.session.user,
            ttl_seconds: res.session.ttl_seconds,
            login_time: Date.now(),
          });
          window.location.href = "../html/profile.html";
        } else {
          $("#msg").removeClass().addClass("alert alert-danger").text(res.message || "Login failed").show();
        }
      })
      .fail(function (xhr) {
        const msg = (xhr.responseJSON && xhr.responseJSON.message) || "Login failed";
        $("#msg").removeClass().addClass("alert alert-danger").text(msg).show();
      });
  });
});

