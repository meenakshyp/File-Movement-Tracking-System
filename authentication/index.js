
function myFunction() {
    var x = document.getElementById("password");
  var icon = document.getElementById("togglePassword");
    if (x.type === "password") {
        x.type = "text";
       icon.classList.remove("fas", "fa-eye");
icon.classList.add("fas", "fa-eye-slash");
    } else {
        x.type = "password";
      icon.classList.remove("fas", "fa-eye-slash");
icon.classList.add("fas", "fa-eye");
    }
}
