/**
 * Profile page logic.
 * - Requires localStorage token
 * - Fetches & updates profile using jQuery AJAX
 * - Logout clears localStorage and deletes Redis session
 */

function apiPost(url, payload) {
  return $.ajax({
    url: url,
    method: "POST",
    contentType: "application/json",
    data: JSON.stringify(payload),
    dataType: "json",
  });
}

$(function () {
  const session = requireSessionOrRedirect();
  if (!session) return;

  function showMsg(type, text) {
    $("#msg").removeClass().addClass("alert alert-" + type).text(text).show();
  }

  function loadProfile() {
    showMsg("info", "Loading profile...");

    apiPost("../php/profile_get.php", { token: session.token })
      .done(function (res) {
        if (!res.ok) {
          showMsg("danger", res.message || "Failed to load profile");
          return;
        }

        $("#welcome").text("Welcome, " + (res.user && res.user.username ? res.user.username : "User"));
        $("#age").val(res.profile ? res.profile.age ?? "" : "");
        $("#dob").val(res.profile ? res.profile.dob ?? "" : "");
        $("#contact").val(res.profile ? res.profile.contact ?? "" : "");

        $("#msg").hide();
      })
      .fail(function (xhr) {
        const msg = (xhr.responseJSON && xhr.responseJSON.message) || "Failed to load profile";
        showMsg("danger", msg);

        // If session is invalid/expired, force logout locally
        if (xhr.status === 401) {
          clearSession();
          setTimeout(function () {
            window.location.href = "../html/login.html";
          }, 500);
        }
      });
  }

  $("#saveBtn").on("click", function (e) {
    e.preventDefault();

    const payload = {
      token: session.token,
      age: $("#age").val().trim(),
      dob: $("#dob").val().trim(),
      contact: $("#contact").val().trim(),
    };

    // Convert empty strings to null for cleaner backend validation
    if (payload.age === "") payload.age = null;
    if (payload.dob === "") payload.dob = null;
    if (payload.contact === "") payload.contact = null;

    showMsg("info", "Saving...");

    apiPost("../php/profile_update.php", payload)
      .done(function (res) {
        if (res.ok) {
          showMsg("success", res.message || "Saved");
        } else {
          showMsg("danger", res.message || "Save failed");
        }
      })
      .fail(function (xhr) {
        const msg = (xhr.responseJSON && xhr.responseJSON.message) || "Save failed";
        showMsg("danger", msg);
        if (xhr.status === 401) {
          clearSession();
          setTimeout(function () {
            window.location.href = "../html/login.html";
          }, 500);
        }
      });
  });

  $("#logoutBtn").on("click", function (e) {
    e.preventDefault();
    showMsg("info", "Logging out...");

    apiPost("../php/logout.php", { token: session.token })
      .always(function () {
        clearSession();
        window.location.href = "../html/login.html";
      });
  });

  loadProfile();
});

