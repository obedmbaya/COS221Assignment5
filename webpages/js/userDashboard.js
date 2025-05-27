document.addEventListener("DOMContentLoaded", function () {

    const apiKey = localStorage.getItem("apiKey");
    if (!apiKey) {
        alert("You must be logged in to view your dashboard.");
        window.location.href = "login.php";
        return;
    }

    const payload = {
        type: "getUserDashboard",
        ApiKey: apiKey
    };
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../api/api.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.status === "success") {
                        const user = data.data.user;
                        const stats = data.data.stats;
                        // update user info
                        document.querySelector(".user-name").textContent = user.FirstName + " " + user.LastName;
                        document.querySelector(".user-email").textContent = user.Email;
                        // update stats
                        document.querySelectorAll(".stat-number")[0].textContent = stats.reviewsCount;
                        document.querySelectorAll(".stat-number")[1].textContent = stats.avgRating;
                        // update reviewed products
                        const productsList = document.querySelector(".products-list");
                        productsList.innerHTML = "";
                        stats.reviews.forEach(r => {
                            const item = document.createElement("div");
                            item.className = "product-item";
                            item.innerHTML = `
                                <span class="product-name">${r.ProductName}</span>
                                <div class="product-rating">
                                    <span class="stars">${"★".repeat(r.Rating)}${"☆".repeat(5 - r.Rating)}</span>
                                    <span class="rating-value">${r.Rating} Star${r.Rating > 1 ? 's' : ''}</span>
                                </div>
                                <div class="product-actions">
                                    <button class="view-btn" onclick="window.location.href='view.php?product=${r.ProductID}'">View</button>
                                    <button class="update-btn" onclick="showUpdateReview('${r.ProductID}')">Update</button>
                                </div>
                            `;
                            productsList.appendChild(item);
                        });
                    } else {
                        alert("Failed to load dashboard: " + data.data);
                    }
                } catch (e) {
                    alert("Invalid response from server.");
                    console.error(e);
                }
            } else {
                alert("Request failed. Status: " + xhr.status);
            }
        }
    };
    xhr.send(JSON.stringify(payload));

    // Edit Info Tab logic
    const editProfileForm = document.querySelector('#profile form');
    if (editProfileForm) {

        const apiKey2 = localStorage.getItem("apiKey");
        if (apiKey2) {
            var payload2 = { 
                type: "getUserDashboard", 
                ApiKey: apiKey2 
            };
            
            var xhrGet = new XMLHttpRequest();
            xhrGet.open("POST", "../api/api.php", true);
            xhrGet.setRequestHeader("Content-Type", "application/json");
            xhrGet.onreadystatechange = function () {
                if (xhrGet.readyState === 4 && xhrGet.status === 200) {
                    try {
                        var data2 = JSON.parse(xhrGet.responseText);
                        if (data2.status === "success") {
                            editProfileForm.querySelector('input[type="email"]').value = data2.data.user.Email;
                            localStorage.setItem("email", data2.data.user.Email);
                        }
                    } catch (e) {}
                }
            };
            xhrGet.send(JSON.stringify(payload2));
        }
        editProfileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var newEmail = editProfileForm.querySelector('input[type="email"]').value;
            var newPassword = editProfileForm.querySelectorAll('input[type="password"]')[0].value;
            var confirmPassword = editProfileForm.querySelectorAll('input[type="password"]')[1].value;
            if (newPassword && newPassword !== confirmPassword) {
                alert("Passwords do not match.");
                return;
            }
            var updatePayload = {
                type: "updateUserProfile",
                ApiKey: apiKey2,
                email: newEmail
            };
            if (newPassword) {
                updatePayload.password = newPassword;
            }
            var xhrUpdate = new XMLHttpRequest();
            xhrUpdate.open("POST", "../api/api.php", true);
            xhrUpdate.setRequestHeader("Content-Type", "application/json");
            xhrUpdate.onreadystatechange = function () {
                if (xhrUpdate.readyState === 4) {
                    if (xhrUpdate.status === 200) {
                        try {
                            var resp = JSON.parse(xhrUpdate.responseText);
                            
                            if (resp.status === "success") {
                                alert("Profile updated successfully.");
                                // Update the displayed email in the dashboard
                                document.querySelector(".user-email").textContent = newEmail;
                                // Clear password fields
                                editProfileForm.querySelectorAll('input[type="password"]').forEach(input => input.value = "");
                            } else {
                                alert("Update failed: " + resp.data);
                            }
                        } catch (e) {
                            alert("Invalid response from server.");
                        }
                    } else {
                        alert("Request failed. Status: " + xhrUpdate.status);
                    }
                }
            };
            xhrUpdate.send(JSON.stringify(updatePayload));
        });
    }

    // Tab navigation logic for switching between Overview and Edit Info
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all buttons and panes
            tabButtons.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));
            // Add active to clicked button and corresponding pane
            this.classList.add('active');
            const tab = this.getAttribute('data-tab');
            document.getElementById(tab).classList.add('active');
        });
    });
});