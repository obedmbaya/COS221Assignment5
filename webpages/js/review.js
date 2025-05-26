document.addEventListener('DOMContentLoaded', function () {
    var selectedRating = 0;
    var starEls = document.querySelectorAll('.rating-star');
    var postButton = document.querySelector('.post-review-btn');
    var textArea = document.querySelector('.review-textarea');
    var reviewBox = document.querySelector(".reviews-box");
    var productID = localStorage.getItem("ProductID") || 608; 
    if (!productID) {
        console.error("ProductID not found in localStorage or session.");
        return;
    }
    var userID = localStorage.getItem("UserID") || 1;
    if (!userID) {
        console.error("UserID not found in localStorage or session.");
        return;
    }

    // Star rating selection
    for (var i = 0; i < starEls.length; i++) {
        (function(idx){
            starEls[idx].addEventListener("click", function(){
                setRating(idx + 1);
            });
        })(i);
    }

    function setRating(rating) {
        selectedRating = rating;
        for (var j = 0; j < starEls.length; j++) {
            if (j < rating) {
                starEls[j].classList.add('selected');
            } else {
                starEls[j].classList.remove('selected');
            }
        }
        console.log("Rating set to:", rating);
    }
    window.setRating = setRating;

    // Fetch and display reviews from the database
    function loadReviews() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/PA_221_FRONTEND-LINKING/api.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        console.log(response.data); // Add this line

                        var items = reviewBox.querySelectorAll('.review-item');
                        items.forEach(function(item) { item.remove(); });

                        // insert reviews
                        response.data.forEach(function(review) {
                            var reviewDiv = document.createElement("div");
                            reviewDiv.className = "review-item";
                            var starsHtml = "";
                            for (var i = 0; i < review.Rating; i++) {
                                starsHtml += '<span class="star filled">★</span>';
                            }
                            for (var i = review.Rating; i < 5; i++) {
                                starsHtml += '<span class="star">★</span>';
                            }
                            reviewDiv.innerHTML = `
                                <div class="reviewer-name">${review.UserID ? 'User #' + review.UserID : 'Anonymous'}</div>
                                <div class="review-rating">${starsHtml}</div>
                                <div class="review-text">${review.Comment}</div>
                                <div class="review-date">${review.ReviewDate ? review.ReviewDate : ''}</div>
                            `;
                            // insert before pagination if it exists
                            var pagination = reviewBox.querySelector(".pagination");
                            if (pagination) {
                                reviewBox.insertBefore(reviewDiv, pagination);
                            } else {
                                reviewBox.appendChild(reviewDiv);
                            }
                        });
                    }
                } catch (e) {
                    alert("Server error: " + xhr.responseText);
                    console.log(xhr.responseText);
                }
            }
        };
        xhr.send(JSON.stringify({
            type: "getReview",
            ProductID: productID
        }));
    }

    // Post review button
    postButton.addEventListener("click", function(){
        var reviewText = textArea.value.trim();
        if (selectedRating === 0 || reviewText === "") {
            window.alert("Please select a rating and enter your review.");
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/PA_221_FRONTEND-LINKING/api.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log(xhr.responseText);
                if (xhr.status === 200 || xhr.status === 201) { // Accept 201 as success
                    loadReviews();
                    textArea.value = "";
                    selectedRating = 0;
                    for (var i = 0; i < starEls.length; i++) {
                        starEls[i].classList.remove('selected');
                    }
                } else {
                    alert("Failed to submit review.");
                }
            }
        };
        xhr.send(JSON.stringify({
            type: "insertReview",
            ProductID: productID,
            UserID: userID,
            Rating: selectedRating,
            Comment: reviewText
        }));
    });

    loadReviews();
});