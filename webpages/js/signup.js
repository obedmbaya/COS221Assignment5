    document.addEventListener("DOMContentLoaded", function () {
      document.getElementById("registerForm").addEventListener("submit", function (event) {
        event.preventDefault();

        var payload = {
          type: "Register",
          name: document.getElementById("name").value,
          surname: document.getElementById("surname").value,
          email: document.getElementById("email").value,
          password: document.getElementById("password").value,
          user_type: document.getElementById("user_type").value
        };

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "signupapi.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              try {
                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                  localStorage.setItem("apiKey", response.data.apikey);
                  alert("Registration successful! API key saved.");
                } else {
                  alert("Registration failed: " + JSON.stringify(response));
                }
              } catch (e) {
                alert("Invalid response from server.");
              }
            } else {
              alert("Request failed. Status: " + xhr.status);
            }
          }
        };

        xhr.send(JSON.stringify(payload));
      });
    });