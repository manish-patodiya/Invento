function base_url(uri) {
  return BASE_URL + uri;
}
$(function () {
  "use strict";


  // To make Pace works on Ajax calls
  $(document).ajaxStart(function () {
    Pace.restart()
  })


  $("#frm-login").submit(function (e) {
    e.preventDefault();
    let login = {
      url: base_url("/auth/login"),
      data: $("#frm-login").serialize(),
      method: "post",
      dataType: "json",
      success: function (res) {
        if (res.status == 1) {
          console.log(res.roles);
          if (res.roles) {
            window.location = base_url("/dashboard");
          } else {
            window.location = base_url("/dashboard");
          }
        } else {
          $("#login-err").html(res.message);
          $("#login-err").show();
          $('#user-id').val(res.user_id);
        }
      }
    }
    $.ajax(login);
  })


});