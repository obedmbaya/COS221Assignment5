document.addEventListener('DOMContentLoaded', function() {
    loadUserReviewStats(); // Load user review stats

    // Tab navigation
    const navLinks = document.querySelectorAll('.nav-tabs a');
    const sections = document.querySelectorAll('.tab-content > div');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            
            navLinks.forEach(l => l.classList.remove('active'));
            sections.forEach(s => s.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // Logout handler
    const logoutLink = document.querySelector('.logout');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            localStorage.removeItem('apiKey');
            window.location.href = 'login.php'; // Adjust redirect as needed
        });
    }
});

function loadUserReviewStats() {
    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to load review statistics.');
        return;
    }

    // Fetch products to map ProductIDs to names
    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            type: 'getAllProducts'
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON (products):', text);
            throw new Error('Server returned invalid JSON');
        }
    })
    .then(productData => {
        if (productData.status !== 'success') {
            alert('Failed to load products: ' + (productData.data || 'Unknown error'));
            return;
        }

        const productMap = {};
        productData.products.forEach(product => {
            productMap[product.ProductID] = product.ProductName;
        });

        // Fetch user reviews
        fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'getUserReviews',
                ApiKey: apiKey
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON (reviews):', text);
                throw new Error('Server returned invalid JSON');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                const reviews = Array.isArray(data.data) ? data.data : [];
                updateReviewStats(reviews, productMap);
            } else {
                alert('Failed to load reviews: ' + (data.data || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            alert('An error occurred while loading reviews. Please try again.');
        });
    })
    .catch(error => {
        console.error('Error loading products:', error);
        alert('An error occurred while loading products. Please try again.');
    });
}

function updateReviewStats(reviews, productMap) {
    const reviewsMadeElement = document.querySelector('.stats .stat-card:first-child h3');
    const averageRatingElement = document.querySelector('.stats .stat-card:nth-child(2) h3 .avg_rating');
    
    if (!reviewsMadeElement || !averageRatingElement) {
        console.error('Review stats elements not found');
        return;
    }

    // Calculate number of reviews
    const reviewCount = reviews.length;
    reviewsMadeElement.textContent = reviewCount;

    // Calculate average rating
    const totalRating = reviews.reduce((sum, review) => sum + parseInt(review.Rating), 0);
    const averageRating = reviewCount > 0 ? (totalRating / reviewCount).toFixed(1) : 0;
    averageRatingElement.textContent = averageRating;

    // Update reviewed products
    updateReviewedProducts(reviews, productMap);
}

function updateReviewedProducts(reviews, productMap) {
    const productList = document.querySelector('.product-list');
    if (!productList) return;

    productList.innerHTML = ''; // Clear existing products

    reviews.forEach(review => {
        const productName = productMap[review.ProductID] || 'Unknown Product';
        const rating = parseInt(review.Rating);
        const starsHtml = '★'.repeat(rating) + '☆'.repeat(5 - rating);
        const ratingText = rating === 1 ? '1 Star' : `${rating} Stars`;

        const productDiv = document.createElement('div');
        productDiv.className = 'product';
        productDiv.innerHTML = `
            <div class="product-details">
                <h3 class="product-name">${productName}</h3>
                <p><span class="stars">${starsHtml}</span> <strong class="rating-value">${ratingText}</strong></p>
            </div>
            <div class="actions">
                <a href="#" class="view-content">View</a>
                <a href="#" class="update-content" onclick="showUpdateReview('${review.ReviewID}', '${review.ProductID}')">Update</a>
            </div>
        `;
        productList.appendChild(productDiv);
    });
}

document.addEventListener('submit', function(e) {
    if (e.target.closest('#edit-info')) {
        e.preventDefault();
        
        const email = e.target.querySelector('input[name="email"]').value;
        const password = e.target.querySelector('input[name="password"]').value;
        const confirmPassword = e.target.querySelector('input[name="confirm-password"]').value;
        
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        
        if (password && !passwordRegex.test(password)) {
            alert('Password must be at least 8 characters long and contain numbers, special symbols, uppercase and lowercase letters.');
            return;
        }
        
        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }

        const apiKey = localStorage.getItem('apiKey');
        if (!apiKey) {
            alert('Please log in to update profile.');
            return;
        }

        fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'editInfo',
                api_key: apiKey,
                email: email,
                password: password
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON (profile):', text);
                throw new Error('Server returned invalid JSON');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                alert('Profile updated successfully!');
            } else {
                alert('Failed to update profile: ' + (data.data || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error updating profile:', error);
            alert('An error occurred while updating profile.');
        });
    }
});

let userReviewData = {};

function showUpdateReview(reviewId, productId) {
    const updateForm = document.getElementById('updateReviewForm');
    const apiKey = localStorage.getItem('apiKey');
    
    if (!apiKey) {
        alert('Please log in to update reviews.');
        return;
    }

    // Fetch the specific review
    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            type: 'getReview',
            ReviewID: reviewId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON (review):', text);
            throw new Error('Server returned invalid JSON');
        }
    })
    .then(data => {
        if (data.status === 'success' && data.data.length > 0) {
            const review = data.data[0];
            userReviewData[reviewId] = {
                reviewId: reviewId,
                productId: productId,
                name: document.querySelector(`.product:has([onclick*="${reviewId}"]) .product-name`).textContent,
                rating: review.Rating,
                review: review.Comment || ''
            };

            document.getElementById('reviewProductName').value = userReviewData[reviewId].name;
            document.getElementById('reviewRating').value = review.Rating;
            document.getElementById('reviewText').value = review.Comment || '';

            updateForm.style.display = 'block';
            updateForm.scrollIntoView({ behavior: 'smooth' });
        } else {
            alert('Failed to load review: ' + (data.data || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error loading review:', error);
        alert('An error occurred while loading the review.');
    });
}

function saveReview() {
    const reviewId = Object.keys(userReviewData).find(id => userReviewData[id].reviewId);
    const productId = userReviewData[reviewId]?.productId;
    const rating = document.getElementById('reviewRating').value;
    const reviewText = document.getElementById('reviewText').value.trim();
    const apiKey = localStorage.getItem('apiKey');

    if (!reviewId || !productId || !apiKey) {
        alert('Invalid review or not logged in.');
        return;
    }

    if (!reviewText) {
        alert('Please enter a review text');
        return;
    }

    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            type: 'updateReview',
            api_key: apiKey,
            ReviewID: reviewId,
            Rating: parseInt(rating),
            Comment: reviewText
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON (update review):', text);
            throw new Error('Server returned invalid JSON');
        }
    })
    .then(data => {
        if (data.status === 'success') {
            updateProductDisplay(reviewId, parseInt(rating));
            alert('Review updated successfully!');
            cancelUpdateReview();
            loadUserReviewStats(); // Refresh stats
        } else {
            alert('Failed to update review: ' + (data.data || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error updating review:', error);
        alert('An error occurred while updating the review.');
    });
}

function cancelUpdateReview() {
    const updateForm = document.getElementById('updateReviewForm');
    updateForm.style.display = 'none';

    document.getElementById('reviewProductName').value = '';
    document.getElementById('reviewRating').value = '1';
    document.getElementById('reviewText').value = '';

    userReviewData = {};
}

function updateProductDisplay(reviewId, newRating) {
    const productItems = document.querySelectorAll('.product');
    const reviewData = userReviewData[reviewId];

    productItems.forEach(item => {
        const productName = item.querySelector('.product-name').textContent;
        if (productName === reviewData.name) {
            const starsSpan = item.querySelector('.stars');
            const ratingValue = item.querySelector('.rating-value');

            let starsHtml = '★'.repeat(newRating) + '☆'.repeat(5 - newRating);
            starsSpan.textContent = starsHtml;

            const ratingText = newRating === 1 ? '1 Star' : `${newRating} Stars`;
            ratingValue.textContent = ratingText;
        }
    });
}