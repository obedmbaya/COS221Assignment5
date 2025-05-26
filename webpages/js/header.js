document.addEventListener("DOMContentLoaded", function () {
    // Check if user is logged in by looking for apiKey in localStorage
    function checkLoginStatus() {
        var apiKey = localStorage.getItem("apiKey");
        var guestLinks = document.getElementById("guest-links");
        var userLinks = document.getElementById("user-links");

        if (apiKey) {
            guestLinks.style.display = "none";
            userLinks.style.display = "block";
        } else {
            guestLinks.style.display = "block";
            userLinks.style.display = "none";
        }
    }

    function handleLogout() {
        var logoutBtn = document.getElementById("logout-btn");
        if (logoutBtn) {
            logoutBtn.addEventListener("click", function (event) {
                event.preventDefault();
                localStorage.removeItem("apiKey"); // Clear the apiKey from localStorage
                window.location.href = "index.php"; // Redirect to index.php
            });
        }
    }

    checkLoginStatus();
    handleLogout();
    
    // Listen for changes in localStorage to update login status
    window.addEventListener("storage", function(event) {
        if (event.key === "apiKey") {
            checkLoginStatus();
        }
    });
});