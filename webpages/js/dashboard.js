document.addEventListener('DOMContentLoaded', function() {

    var username = document.querySelector(".user-name");
    var useremail = document.querySelector(".user-email");

    if (username) {
        username.textContent = localStorage.getItem("name") + " " +  localStorage.getItem("surname") || "";
    }

    if (useremail) {
        useremail.textContent = localStorage.getItem("email") || "";
    }



    initializeTabs(); // Initialize tab switching functionality
    initializeCharts(); // Sets up Chart.js charts
    
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

    document.querySelector(".logout-btn").addEventListener("click", function () {
        localStorage.clear();
        alert("You have been logged out successfully.");
        window.location.href = "index.php";
    });


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
        
        alert('Profile updated successfully!');
    }
});

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

function showUpdateReview(productId) {
    const updateForm = document.getElementById('updateReviewForm');
    const productData = userReviewData[productId];
    
    if (productData && updateForm) {
        currentEditingProduct = productId;
        
        document.getElementById('reviewProductName').value = productData.name;
        document.getElementById('reviewRating').value = productData.rating;
        document.getElementById('reviewText').value = productData.review;
        
        updateForm.style.display = 'block';
        updateForm.scrollIntoView({ behavior: 'smooth' });
    }
}

function saveReview() {
    if (!currentEditingProduct) return;
    
    const rating = document.getElementById('reviewRating').value;
    const reviewText = document.getElementById('reviewText').value.trim();
    
    if (!reviewText) {
        alert('Please enter a review text');
        return;
    }
    
    userReviewData[currentEditingProduct].rating = parseInt(rating);
    userReviewData[currentEditingProduct].review = reviewText;
    
    updateProductDisplay(currentEditingProduct, parseInt(rating));
    
    alert('Review updated successfully!');
    cancelUpdateReview();
}

function cancelUpdateReview() {
    const updateForm = document.getElementById('updateReviewForm');
    updateForm.style.display = 'none';
    
    document.getElementById('reviewProductName').value = '';
    document.getElementById('reviewRating').value = '1';
    document.getElementById('reviewText').value = '';
    
    currentEditingProduct = null;
}

function updateProductDisplay(productId, newRating) {
    const productItems = document.querySelectorAll('.product-item');
    const productData = userReviewData[productId];
    
    productItems.forEach(item => {
        const productName = item.querySelector('.product-name').textContent;
        if (productName === productData.name) {
            const starsSpan = item.querySelector('.stars');
            const ratingValue = item.querySelector('.rating-value');
            
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += i <= newRating ? '★' : '☆';
            }
            starsSpan.textContent = starsHtml;
            
            const ratingText = newRating === 1 ? '1 Star' : `${newRating} Stars`;
            ratingValue.textContent = ratingText;
        }
    });
}

