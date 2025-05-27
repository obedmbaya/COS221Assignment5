document.addEventListener("DOMContentLoaded", function () {
    //localStorage.removeItem("apiKey"); // Clear the apiKey from localStorage
    localStorage.clear();
    
    setTimeout(function() {
        window.location.href = "index.php"; // Redirect to index.php after 1 second
        alert("You have been logged out successfully.");
    }, 1000); 
});