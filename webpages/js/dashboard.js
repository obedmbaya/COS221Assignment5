document.addEventListener('DOMContentLoaded', function() {
    initializeTabs(); // Initialize tab switching functionality
    initializeCharts(); // Sets up Chart.js charts
    loadUserReviewStats(); // Load user review stats for Reviews Made and Average Rating
    
    const productSelect = document.getElementById('productSelect');
    const customerProductSelect = document.getElementById('customerProductSelect');
    
    if (productSelect) {
        productSelect.addEventListener('change', updateProductChart);
    }
    
    if (customerProductSelect) {
        customerProductSelect.addEventListener('change', updateCustomerProductChart);
    }

    if (document.getElementById('users')) {
        loadUsers();
    }
});

function initializeTabs() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            tabBtns.forEach(tab => tab.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');

            if (targetTab === 'users') {
                loadUsers();
            }
        });
    });
}

let overallRatingsChart;
let productRatingsChart;
let customerProductRatingsChart;

function initializeCharts() {
    const overallCtx = document.getElementById('overallRatingsChart');
    if (overallCtx) {
        overallRatingsChart = new Chart(overallCtx, {
            type: 'bar',
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    label: 'Number of Reviews',
                    data: [245, 189, 567, 2156, 9733],
                    backgroundColor: [
                        '#e74c3c',
                        '#f39c12',
                        '#f1c40f',
                        '#2ecc71',
                        '#27ae60'
                    ],
                    borderColor: [
                        '#c0392b',
                        '#e67e22',
                        '#f39c12',
                        '#27ae60',
                        '#229954'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Reviews'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Rating'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Overall Review Distribution'
                    }
                }
            }
        });
    }
    
    const productCtx = document.getElementById('productRatingsChart');
    if (productCtx) {
        productRatingsChart = new Chart(productCtx, {
            type: 'bar',
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    label: 'Number of Reviews',
                    data: [12, 8, 45, 234, 567],
                    backgroundColor: '#4c4faf',
                    borderColor: '#3f3e8e',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Reviews'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Rating'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'iPhone 14 Pro Max - Rating Distribution'
                    }
                }
            }
        });
    }
    
    const customerCtx = document.getElementById('customerProductRatingsChart');
    if (customerCtx) {
        customerProductRatingsChart = new Chart(customerCtx, {
            type: 'bar',
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    label: 'Number of Reviews',
                    data: [12, 8, 45, 234, 567],
                    backgroundColor: '#4c4faf',
                    borderColor: '#3f3e8e',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Reviews'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Rating'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'iPhone 14 Pro Max - Rating Distribution'
                    }
                }
            }
        });
    }
}

const productData = {
    iphone14: {
        name: 'iPhone 14 Pro Max',
        data: [12, 8, 45, 234, 567]
    },
    samsung23: {
        name: 'Samsung Galaxy S23 Ultra',
        data: [15, 12, 38, 198, 445]
    },
    macbook: {
        name: 'MacBook Pro M2',
        data: [5, 3, 22, 156, 389]
    },
    sony: {
        name: 'Sony WH-1000XM5',
        data: [18, 25, 67, 245, 334]
    },
    ipad: {
        name: 'iPad Pro 12.9',
        data: [8, 6, 34, 189, 423]
    },
    airpods: {
        name: 'AirPods Pro',
        data: [22, 19, 56, 267, 398]
    },
    nintendo: {
        name: 'Nintendo Switch OLED',
        data: [28, 31, 78, 289, 356]
    },
};

function updateProductChart() {
    const selectedProduct = document.getElementById('productSelect').value;
    const data = productData[selectedProduct];
    
    if (productRatingsChart && data) {
        productRatingsChart.data.datasets[0].data = data.data;
        productRatingsChart.options.plugins.title.text = data.name + ' - Rating Distribution';
        productRatingsChart.update();
    }
}

function updateCustomerProductChart() {
    const selectedProduct = document.getElementById('customerProductSelect').value;
    const data = productData[selectedProduct];
    
    if (customerProductRatingsChart && data) {
        customerProductRatingsChart.data.datasets[0].data = data.data;
        customerProductRatingsChart.options.plugins.title.text = data.name + ' - Rating Distribution';
        customerProductRatingsChart.update();
    }
}

function editProduct(productId) {
    const editForm = document.getElementById('editProductForm');
    const products = {
        '001': {
            name: 'iPhone 14 Pro Max',
            brand: 'Apple',
            description: 'Latest iPhone with advanced camera system',
            image: 'https://example.com/iphone14.jpg'
        },
        '002': {
            name: 'Samsung Galaxy S23 Ultra',
            brand: 'Samsung',
            description: 'Premium Android smartphone with S Pen',
            image: 'https://example.com/samsung23.jpg'
        },
        '003': {
            name: 'MacBook Pro M2',
            brand: 'Apple',
            description: 'Professional laptop with M2 chip',
            image: 'https://example.com/macbook.jpg'
        }
    };
    
    const product = products[productId];
    if (product) {
        document.getElementById('editProductName').value = product.name;
        document.getElementById('editProductBrand').value = product.brand;
        document.getElementById('editProductDescription').value = product.description;
        document.getElementById('editProductImage').value = product.image;
        editForm.style.display = 'block';
        editForm.scrollIntoView({ behavior: 'smooth' });
    }
}

function saveProduct() {
    const name = document.getElementById('editProductName').value;
    const brand = document.getElementById('editProductBrand').value;
    const description = document.getElementById('editProductDescription').value;
    const image = document.getElementById('editProductImage').value;
    
    if (!name || !brand || !description) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert('Product saved successfully!');
    cancelEdit();
}

function cancelEdit() {
    const editForm = document.getElementById('editProductForm');
    editForm.style.display = 'none';
    
    document.getElementById('editProductName').value = '';
    document.getElementById('editProductBrand').value = '';
    document.getElementById('editProductDescription').value = '';
    document.getElementById('editProductImage').value = '';
}

function loadUsers() {
    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to load users.');
        return;
    }

    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            type: 'LoadUsers',
            api_key: apiKey
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateUserTable(data.data);
        } else {
            alert('Failed to load users: ' + (data.data || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error loading users:', error);
        alert('An error occurred while loading users.');
    });
}

function populateUserTable(users) {
    const tableBody = document.querySelector('#users table tbody');
    if (!tableBody) return;

    tableBody.innerHTML = '';

    users.forEach(user => {
        const row = document.createElement('tr');
        const fullName = `${user.FirstName} ${user.LastName}`;
        const userType = user.UserType || 'Standard User';
        const isAdmin = userType === 'Admin';
        row.innerHTML = `
            <td>${fullName}</td>
            <td>${user.Email}</td>
            <td>${userType}</td>
            <td>
                ${isAdmin ? '' : '<button class="success" onclick="toggleAdminStatus(\'' + user.Email + '\', \'' + userType + '\')">Make Admin</button>'}
                <button class="remove" onclick="removeUser(\'' + user.Email + '\')">Remove</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

function toggleAdminStatus(email, currentType) {
    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to modify user status.');
        return;
    }

    const isMakingAdmin = currentType !== 'Admin';
    const confirmMessage = isMakingAdmin
        ? 'Are you sure you want to make this user an admin?'
        : 'Are you sure you want to remove admin privileges?';

    if (confirm(confirmMessage)) {
        fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'editUser',
                api_key: apiKey,
                edit_email: email,
                updates: {
                    UserType: isMakingAdmin ? 'Admin' : 'Standard User'
                }
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(isMakingAdmin ? 'User promoted to admin successfully!' : 'Admin privileges removed successfully!');
                loadUsers();
            } else {
                alert('Failed to update user status: ' + (data.data || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error updating user status:', error);
            alert('An error occurred while updating user status.');
        });
    }
}

function removeUser(email) {
    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to remove a user.');
        return;
    }

    if (confirm('Are you sure you want to remove this user? This action cannot be undone.')) {
        fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'removeUser',
                api_key: apiKey,
                remove_email: email
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('User removed successfully!');
                loadUsers();
            } else {
                alert('Failed to remove user: ' + (data.data || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error removing user:', error);
            alert('An error occurred while removing the user.');
        });
    }
}

function loadUserReviewStats() {
    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to load review statistics.');
        return;
    }

    // Fetch all products to map ProductIDs to names
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
        return response.text(); // Use text() to inspect response
    })
    .then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON:', text);
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
                console.error('Invalid JSON:', text);
                throw new Error('Server returned invalid JSON');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                // Handle both cases: reviews found or no reviews
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
    const reviewsMadeElement = document.querySelector('.stat-card p strong');
    const averageRatingElement = document.querySelector('.stat-card:nth-child(2) p strong');
    
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

    // Update reviewed products list dynamically
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

        const productItem = document.createElement('div');
        productItem.className = 'product-item';
        productItem.innerHTML = `
            <div class="product-details">
                <h3 class="product-name">${productName}</h3>
                <p><span class="stars">${starsHtml}</span> <span class="rating-value">${ratingText}</span></p>
            </div>
            <div class="product-actions">
                <a href="#" class="view-btn">View</a>
                <a href="#" class="update-btn" onclick="showUpdateReview('${review.ReviewID}', '${review.ProductID}')">Update</a>
            </div>
        `;
        productList.appendChild(productItem);
    });
}

document.addEventListener('click', function(e) {
    const isProductsTab = document.getElementById('products')?.classList.contains('active');
    const isUsersTab = document.getElementById('users')?.classList.contains('active');
    
    if (e.target.textContent === 'Make Admin' && isUsersTab) {
        const email = e.target.closest('tr').querySelector('td:nth-child(2)').textContent;
        toggleAdminStatus(email, 'Standard User');
    } else if (e.target.textContent === 'Remove' && e.target.classList.contains('remove')) {
        if (isProductsTab) {
            const confirmMessage = 'Are you sure you want to remove this product? This action cannot be undone.';
            if (confirm(confirmMessage)) {
                const row = e.target.closest('tr');
                row.remove();
                alert('Product removed successfully');
            }
        } else if (isUsersTab) {
            const email = e.target.closest('tr').querySelector('td:nth-child(2)').textContent;
            removeUser(email);
        }
    }
});

document.addEventListener('submit', function(e) {
    if (e.target.closest('.tab-pane[id="profile"]')) {
        e.preventDefault();
        
        const name = e.target.querySelector('input[type="text"]').value;
        const email = e.target.querySelector('input[type="email"]').value;
        const password = e.target.querySelector('input[type="password"]').value;
        const confirmPassword = e.target.querySelectorAll('input[type="password"]')[1].value;
        
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
                name: name,
                email: email,
                password: password
            })
        })
        .then(response => response.json())
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
            console.error('Invalid JSON:', text);
            throw new Error('Server returned invalid JSON');
        }
    })
    .then(data => {
        if (data.status === 'success' && data.data.length > 0) {
            const review = data.data[0];
            userReviewData[reviewId] = {
                reviewId: reviewId,
                productId: productId,
                name: document.querySelector(`.product-item:has([onclick*="${reviewId}"]) .product-name`).textContent,
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
            console.error('Invalid JSON:', text);
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
    const productItems = document.querySelectorAll('.product-item');
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