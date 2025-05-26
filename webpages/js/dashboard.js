// Dashboard JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs(); // Initialize tab switching functionality
    
    initializeCharts(); //Sets up Chart.js charts
    
    const productSelect = document.getElementById('productSelect'); // Product selection for admin
    const customerProductSelect = document.getElementById('customerProductSelect'); // Product selection for customer
    
    if (productSelect) { // Add event listener for product selection in admin dashboard
        productSelect.addEventListener('change', updateProductChart);
    }
    
    if (customerProductSelect) { // Add event listener for product selection in customer dashboard
        customerProductSelect.addEventListener('change', updateCustomerProductChart);
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
            this.classList.add('active'); // Highlight the clicked tab button
            document.getElementById(targetTab).classList.add('active'); // Show the corresponding tab pane
        });
    });
}

let overallRatingsChart; // Overall ratings chart for admin
let productRatingsChart; // Product-specific ratings chart for admin
let customerProductRatingsChart; // Customer product ratings chart

function initializeCharts() {
    // Overall ratings chart (admin only)
    const overallCtx = document.getElementById('overallRatingsChart');
    if (overallCtx) {
        overallRatingsChart = new Chart(overallCtx, { // Overall ratings distribution chart
            // Using Chart.js to create a bar chart for overall ratings
            type: 'bar',
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    label: 'Number of Reviews',
                    data: [245, 189, 567, 2156, 9733], // Sample review count data for overall ratings e.g. data[0] = num of 1 Star reviews, data[1] = num of 2 Stars reviews, etc.
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
    
    // Product-specific ratings chart (admin)
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
    
    // Customer product ratings chart
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

// Sample product data
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

// Update product chart for admin
function updateProductChart() {
    const selectedProduct = document.getElementById('productSelect').value; // Get selected product from dropdown
    const data = productData[selectedProduct]; // Get product data based on selection
    
    if (productRatingsChart && data) { 
        productRatingsChart.data.datasets[0].data = data.data; // Update chart data with selected product's ratings
        productRatingsChart.options.plugins.title.text = data.name + ' - Rating Distribution'; // Update chart title with product name
        productRatingsChart.update(); // Refresh the chart to reflect changes
    }
}

// Update product chart for customer
function updateCustomerProductChart() {
    const selectedProduct = document.getElementById('customerProductSelect').value;
    const data = productData[selectedProduct];
    
    if (customerProductRatingsChart && data) {
        customerProductRatingsChart.data.datasets[0].data = data.data;
        customerProductRatingsChart.options.plugins.title.text = data.name + ' - Rating Distribution';
        customerProductRatingsChart.update();
    }
}

// Product management functions
function editProduct(productId) {
    const editForm = document.getElementById('editProductForm'); // Get the edit product form element
    const products = { // Sample product data for editing
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
    // Get form values
    const name = document.getElementById('editProductName').value;
    const brand = document.getElementById('editProductBrand').value;
    const description = document.getElementById('editProductDescription').value;
    const image = document.getElementById('editProductImage').value;
    
    // Validate form
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
    
    // Clear form
    document.getElementById('editProductName').value = '';
    document.getElementById('editProductBrand').value = '';
    document.getElementById('editProductDescription').value = '';
    document.getElementById('editProductImage').value = '';
}

// User management functions
document.addEventListener('click', function(e) {
    if (e.target.textContent === 'Make Admin') {
        if (confirm('Are you sure you want to make this user an admin?')) {
            e.target.textContent = 'Remove Admin';
            e.target.classList.remove('success');
            e.target.classList.add('remove');
            // Update user type in the same row
            const row = e.target.closest('tr');
            const userTypeCell = row.querySelector('td:nth-child(3)');
            userTypeCell.textContent = 'Admin';
        }
    } else if (e.target.textContent === 'Remove Admin') {
        if (confirm('Are you sure you want to remove admin privileges?')) {
            e.target.textContent = 'Make Admin';
            e.target.classList.remove('remove');
            e.target.classList.add('success');
            // Update user type in the same row
            const row = e.target.closest('tr');
            const userTypeCell = row.querySelector('td:nth-child(3)');
            userTypeCell.textContent = 'Standard User';
        }
    } else if (e.target.textContent === 'Remove' && e.target.classList.contains('remove')) {
        const isProductsTab = document.getElementById('products').classList.contains('active');
        const isUsersTab = document.getElementById('users').classList.contains('active');
        
        let confirmMessage = '';
        let successMessage = '';
        
        if (isProductsTab) {
            confirmMessage = 'Are you sure you want to remove this product? This action cannot be undone.';
            successMessage = 'Product removed successfully';
        } else if (isUsersTab) {
            confirmMessage = 'Are you sure you want to remove this user? This action cannot be undone.';
            successMessage = 'User removed successfully';
        }
        // Show confirmation dialog with appropriate message
        if (confirm(confirmMessage)) {
            // Remove the entire table row from the DOM
            const row = e.target.closest('tr');
            row.remove();
            // Show success message
            alert(successMessage);
        }
    }
});

// Profile form submission
document.addEventListener('submit', function(e) {
    if (e.target.closest('.tab-pane[id="profile"]')) {
        e.preventDefault();
        
        const password = e.target.querySelector('input[type="password"]').value;
        const confirmPassword = e.target.querySelectorAll('input[type="password"]')[1].value;
        
        // Password validation
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        
        if (password && !passwordRegex.test(password)) {
            alert('Password must be at least 8 characters long and contain numbers, special symbols, uppercase and lowercase letters.');
            return;
        }
        
        if (password !== confirmPassword) {
            alert('Passwords do not match.');
            return;
        }
        
        alert('Profile updated successfully!');
    }
});

// Sample product review data for the update functionality
const userReviewData = {
    iphone14: {
        name: 'iPhone 14 Pro Max',
        rating: 4,
        review: 'Great phone with excellent camera quality. Battery life could be better.'
    },
    samsung23: {
        name: 'Samsung Galaxy S23 Ultra',
        rating: 3,
        review: 'Good phone but has some software issues. Camera is impressive though.'
    },
    macbook: {
        name: 'MacBook Pro M2',
        rating: 5,
        review: 'Absolutely fantastic laptop! Perfect for development work and the M2 chip is incredibly fast.'
    },
    sony: {
        name: 'Sony WH-1000XM5',
        rating: 1,
        review: 'Disappointing product. Sound quality is not as advertised and build quality feels cheap.'
    },
    ipad: {
        name: 'iPad Pro 12.9',
        rating: 4,
        review: 'Excellent tablet for creative work. The display is stunning and Apple Pencil works perfectly.'
    }
};

let currentEditingProduct = null;

// Function to show the update review form
function showUpdateReview(productId) {
    const updateForm = document.getElementById('updateReviewForm');
    const productData = userReviewData[productId];
    
    if (productData && updateForm) {
        currentEditingProduct = productId;
        
        // Populate the form with existing data
        document.getElementById('reviewProductName').value = productData.name;
        document.getElementById('reviewRating').value = productData.rating;
        document.getElementById('reviewText').value = productData.review;
        
        // Show the form
        updateForm.style.display = 'block';
        updateForm.scrollIntoView({ behavior: 'smooth' });
    }
}

// Function to save the updated review
function saveReview() {
    if (!currentEditingProduct) return;
    
    const rating = document.getElementById('reviewRating').value;
    const reviewText = document.getElementById('reviewText').value.trim();
    
    // Validate form
    if (!reviewText) {
        alert('Please enter a review text');
        return;
    }
    
    // Update the product data
    userReviewData[currentEditingProduct].rating = parseInt(rating);
    userReviewData[currentEditingProduct].review = reviewText;
    
    // Update the display in the products list
    updateProductDisplay(currentEditingProduct, parseInt(rating));
    
    alert('Review updated successfully!');
    cancelUpdateReview();
}

// Function to cancel the review update
function cancelUpdateReview() {
    const updateForm = document.getElementById('updateReviewForm');
    updateForm.style.display = 'none';
    
    // Clear form
    document.getElementById('reviewProductName').value = '';
    document.getElementById('reviewRating').value = '1';
    document.getElementById('reviewText').value = '';
    
    currentEditingProduct = null;
}

// Function to update the product display with new rating
function updateProductDisplay(productId, newRating) {
    const productItems = document.querySelectorAll('.product-item');
    const productData = userReviewData[productId];
    
    productItems.forEach(item => {
        const productName = item.querySelector('.product-name').textContent;
        if (productName === productData.name) {
            const starsSpan = item.querySelector('.stars');
            const ratingValue = item.querySelector('.rating-value');
            
            // Update stars display
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += i <= newRating ? '★' : '☆';
            }
            starsSpan.textContent = starsHtml;
            
            // Update rating text
            const ratingText = newRating === 1 ? '1 Star' : `${newRating} Stars`;
            ratingValue.textContent = ratingText;
        }
    });
}