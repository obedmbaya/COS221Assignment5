document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("login-form");

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    var payload = {
      type: "Login",
      email: document.getElementById("email").value,
      password: document.getElementById("password").value
    };

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/api/api.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
              localStorage.setItem("apiKey", response.data.apikey);
              localStorage.setItem("userType", response.data.userType);
              alert("Login successful!");
              window.location.href = "index.php";
            } else {
              alert("Login failed: " + JSON.stringify(response));
            }
          } catch (e) {
            alert("Invalid response from server.");
          }
        } else {
          alert("Login failed. Status: " + xhr.status);
        }
      }
    };

    xhr.send(JSON.stringify(payload));
  });
});
