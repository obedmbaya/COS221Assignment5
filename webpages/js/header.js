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

    var UserType = localStorage.getItem("userType");
    var link = document.querySelector("#dashboardlink");

    if (UserType === "Retailer") {
        link.setAttribute("href", "retailerDashboard.php");
    } else if (UserType === "Standard") {
        link.setAttribute("href", "userDashboard.php");
    } else {
        link.setAttribute("href", "adminDashboard.php");
    }

    // function handleLogout() {
    //     var logoutBtn = document.getElementById("logout-btn");
    //     if (logoutBtn) {
    //         logoutBtn.addEventListener("click", function (event) {
    //             event.preventDefault();
    //             localStorage.clear();
    //             window.location.href = "index.php"; // Redirect to index.php
    //         });
    //     }
    // }

    document.getElementById("logout-button").addEventListener("click", function () {
        localStorage.clear();
        alert("You have been logged out successfully.");
        window.location.href = "index.php";
    });

    checkLoginStatus();
    //handleLogout();
    
    // Listen for changes in localStorage to update login status
    window.addEventListener("storage", function(event) {
        if (event.key === "apiKey") {
            checkLoginStatus();
        }
    });
});