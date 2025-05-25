<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retailer Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
    <?php
        require_once 'header.php';
    ?>

    <div class="dashboard-container">
        <!-- User Info Section -->
        <div class="user-info-section">
            <div class="user-profile">
                <img src="img/user.png" alt="User" class="user-icon">
                <div class="user-details">
                    <h3 class="user-name">Retailer Name</h3>
                    <p class="user-email">retailer@example.com</p>
                </div>
            </div>
            <button class="logout-btn">Logout</button>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="overview">Overview</button>
            <button class="tab-btn" data-tab="products">Manage Products</button>
            <button class="tab-btn" data-tab="add-product">Add Product</button>
            <button class="tab-btn" data-tab="profile">Edit Info</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Overview Tab -->
            <div class="tab-pane active" id="overview">
                <!-- Statistics Boxes -->
                <div class="stats-section">
                    <div class="stat-box">
                        <h3>Total Products</h3>
                        <div class="stat-number">42</div>
                    </div>
                    <div class="stat-box">
                        <h3>Active Products</h3>
                        <div class="stat-number">38</div>
                    </div>
                    <div class="stat-box">
                        <h3>Total Reviews</h3>
                        <div class="stat-number">1,245</div>
                    </div>
                    <div class="stat-box">
                        <h3>Avg Rating</h3>
                        <div class="stat-number">4.2</div>
                    </div>
                </div>

                <!-- Top Rated Products -->
                <div class="section-box">
                    <h3>Your Top Rated Products</h3>
                    <div class="products-list">
                        <div class="product-item">
                            <span class="product-name">Premium Headphones</span>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.8</span>
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                        </div>
                        <div class="product-item">
                            <span class="product-name">Wireless Earbuds</span>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.7</span>
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                        </div>
                        <div class="product-item">
                            <span class="product-name">Bluetooth Speaker</span>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.6</span>
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                        </div>
                    </div>
                </div>

                <!-- Product Ratings Chart -->
                <div class="section-box">
                    <h3>Your Products Rating Distribution</h3>
                    <canvas id="retailerRatingsChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Manage Products Tab -->
            <div class="tab-pane" id="products">
                <div class="section-box">
                    <h3>Product Management</h3>
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Department</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>R001</td>
                                <td>Premium Headphones</td>
                                <td>Electronics</td>
                                <td>Audio</td>
                                <td>R1,299</td>
                                <td>
                                    <button class="success" onclick="editProduct('R001')">Modify</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>R002</td>
                                <td>Wireless Earbuds</td>
                                <td>Electronics</td>
                                <td>Audio</td>
                                <td>R899</td>
                                <td>
                                    <button class="success" onclick="editProduct('R002')">Modify</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>R003</td>
                                <td>Bluetooth Speaker</td>
                                <td>Electronics</td>
                                <td>Audio</td>
                                <td>R1,599</td>
                                <td>
                                    <button class="success" onclick="editProduct('R003')">Modify</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Product Edit Form -->
                <div class="section-box" id="editProductForm" style="display: none;">
                    <h3>Edit Product</h3>
                    <form>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Product Name:</label>
                                <input type="text" id="editProductName" placeholder="Enter product name">
                            </div>
                            <div class="form-group">
                                <label>Category:</label>
                                <select id="editProductCategory" class="form-control">
                                    <option value="">Select category</option>
                                    <option>Electronics</option>
                                    <option>Clothing</option>
                                    <option>Home & Garden</option>
                                    <option>Sports & Outdoors</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description:</label>
                            <textarea id="editProductDescription" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Price (ZAR):</label>
                                <input type="number" id="editProductPrice" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label>Department:</label>
                                <input type="text" id="editProductDepartment" placeholder="e.g. Men's Fashion, Kitchenware">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Image URL:</label>
                            <input type="url" id="editProductImage" placeholder="Enter image URL">
                        </div>
                        <div class="actions">
                            <button type="button" class="success" onclick="saveProduct()">Save</button>
                            <button type="button" class="remove" onclick="cancelEdit()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add Product Tab -->
            <div class="tab-pane" id="add-product">
                <div class="section-box">
                    <h3>Add New Product</h3>
                    <form id="addProductForm">
                        <div class="form-group">
                            <label>Product Image:</label>
                            <input type="file" id="productImage" class="form-control" accept="image/*">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Product Name:</label>
                                <input type="text" id="productName" placeholder="Enter product name">
                            </div>
                            <div class="form-group">
                                <label>Category:</label>
                                <select id="productCategory" class="form-control">
                                    <option value="">Select category</option>
                                    <option>Electronics</option>
                                    <option>Clothing</option>
                                    <option>Home & Garden</option>
                                    <option>Sports & Outdoors</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description:</label>
                            <textarea id="productDescription" rows="3" placeholder="Enter product description"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Price (ZAR):</label>
                                <input type="number" id="productPrice" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label>Department:</label>
                                <input type="text" id="productDepartment" placeholder="e.g. Men's Fashion, Kitchenware">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Image URL:</label>
                            <input type="url" id="productImageUrl" placeholder="Enter image URL">
                        </div>
                        <div class="actions">
                            <button type="button" class="success" onclick="addProduct()">Add Product</button>
                            <button type="reset" class="remove">Clear Form</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Info Tab -->
            <div class="tab-pane" id="profile">
                <div class="section-box">
                    <h3>Edit Profile Information</h3>
                    <form>
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input type="email" value="retailer@example.com" placeholder="Enter new email">
                        </div>
                        <div class="form-group">
                            <label>New Password:</label>
                            <input type="password" placeholder="Enter new password">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password:</label>
                            <input type="password" placeholder="Confirm new password">
                        </div>
                        <p class="password-hint">Password must be at least 8 characters long and contain numbers, special symbols, uppercase and lowercase letters.</p>
                        <button type="submit" class="success">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
        require_once 'footer.php';
    ?>

    <script src="js/dashboard.js"></script>
    <script>
        // Initialize retailer-specific charts
        document.addEventListener('DOMContentLoaded', function() {
            // Retailer ratings chart
            const retailerCtx = document.getElementById('retailerRatingsChart');
            if (retailerCtx) {
                new Chart(retailerCtx, {
                    type: 'bar',
                    data: {
                        labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                        datasets: [{
                            label: 'Number of Reviews',
                            data: [15, 12, 45, 234, 567],
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
                                text: 'Your Products Rating Distribution'
                            }
                        }
                    }
                });
            }
        });

        // Product management functions
        function editProduct(productId) {
            const editForm = document.getElementById('editProductForm');
            const products = {
                'R001': {
                    name: 'Premium Headphones',
                    category: 'Electronics',
                    description: 'High-quality over-ear headphones with noise cancellation',
                    price: '1299',
                    department: 'Audio',
                    image: 'https://example.com/headphones.jpg'
                },
                'R002': {
                    name: 'Wireless Earbuds',
                    category: 'Electronics',
                    description: 'True wireless earbuds with 24hr battery life',
                    price: '899',
                    department: 'Audio',
                    image: 'https://example.com/earbuds.jpg'
                },
                'R003': {
                    name: 'Bluetooth Speaker',
                    category: 'Electronics',
                    description: 'Portable waterproof speaker with 20hr playtime',
                    price: '1599',
                    department: 'Audio',
                    image: 'https://example.com/speaker.jpg'
                }
            };
            
            const product = products[productId];
            if (product) {
                document.getElementById('editProductName').value = product.name;
                document.getElementById('editProductCategory').value = product.category;
                document.getElementById('editProductDescription').value = product.description;
                document.getElementById('editProductPrice').value = product.price;
                document.getElementById('editProductDepartment').value = product.department;
                document.getElementById('editProductImage').value = product.image;
                editForm.style.display = 'block';
                editForm.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function saveProduct() {
            // Get form values
            const name = document.getElementById('editProductName').value;
            const category = document.getElementById('editProductCategory').value;
            const description = document.getElementById('editProductDescription').value;
            const price = document.getElementById('editProductPrice').value;
            const department = document.getElementById('editProductDepartment').value;
            const image = document.getElementById('editProductImage').value;
            
            // Validate form
            if (!name || !category || !description || !price || !department) {
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
            document.getElementById('editProductCategory').value = '';
            document.getElementById('editProductDescription').value = '';
            document.getElementById('editProductPrice').value = '';
            document.getElementById('editProductDepartment').value = '';
            document.getElementById('editProductImage').value = '';
        }

        function addProduct() {
            // Get form values
            const name = document.getElementById('productName').value;
            const category = document.getElementById('productCategory').value;
            const description = document.getElementById('productDescription').value;
            const price = document.getElementById('productPrice').value;
            const department = document.getElementById('productDepartment').value;
            const image = document.getElementById('productImageUrl').value;
            
            // Validate form
            if (!name || !category || !description || !price || !department) {
                alert('Please fill in all required fields');
                return;
            }
            
            alert('Product added successfully!');
            document.getElementById('addProductForm').reset();
        }
    </script>
</body>
</html>