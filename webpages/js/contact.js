document.addEventListener("DOMContentLoaded", function(){
    const contactForm = document.getElementById("contact-form");
    if (!contactForm) return;

    // Fetch and display contact submissions (if needed, similar to loadReviews)
    // function loadContacts() { ... }

    contactForm.addEventListener("submit", function(event){
        event.preventDefault();

        const phone = document.getElementById("phone").value;
        const email = document.getElementById("email").value;
        const message = document.getElementById("message").value;

        if (!phone || !email || !message) {
            alert("Please fill in all fields.");
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../api/api.php", true); 
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function(){
            if (xhr.readyState === 4) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (xhr.status === 200 && response.status === "success") {
                        alert("Message was sent successfully!");
                        contactForm.reset();
                    } else {
                        alert("Failed to send message: " + (response.data || "Unknown error"));
                        console.log(response.data);
                    }
                } catch (e) {
                    alert("Server error: " + xhr.responseText);
                }
            }
        };
        xhr.send(JSON.stringify({
            type: "saveContacts",
            phone: phone,
            email: email,
            message: message,
            ApiKey: localStorage.getItem("apiKey")
        }));
    });
});