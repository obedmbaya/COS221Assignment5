<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                    <h3 class="user-name"></h3> <!-- Displaying Admin Name -->
                    <p class="user-email"></p> <!-- Displaying Admin Email -->
                </div>
            </div>
            <!-- <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button> Logout Button -->
            <button class="logout-btn">Logout</button>
        </div>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="overview">Overview</button>
            <button class="tab-btn" data-tab="users">Manage Users</button>
            <button class="tab-btn" data-tab="products">Manage Products</button>
            <button class="tab-btn" data-tab="profile">Edit Info</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Overview Tab -->
            <div class="tab-pane active" id="overview">
                <!-- Statistics Boxes -->
                <div class="stats-section">
                    <div class="stat-box">
                        <h3>Users</h3> <!-- Displaying total number of users -->
                        <div class="stat-number" id="usercounts"></div>
                    </div>
                    <div class="stat-box">
                        <h3>Retailers</h3> <!-- Displaying total number of retailers -->
                        <div class="stat-number" id="retailercounts"></div>
                    </div>
                    <div class="stat-box">
                        <h3>Products</h3> <!-- Displaying total number of products -->
                        <div class="stat-number" id="productcounts"></div>
                    </div>
                    <div class="stat-box">
                        <h3>Reviews</h3> <!-- Displaying total number of reviews -->
                        <div class="stat-number" id="reviewcounts"></div>
                    </div>
                </div>

                <!-- Top Rated Products -->
                <div class="section-box">
                    <h3>Top Rated Products</h3>
                    <div class="products-list">
                        <div class="product-item">
                            <span class="product-name">iPhone 14 Pro Max</span> <!-- Displaying product name -->
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.8</span> <!-- Displaying product rating -->
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button> <!-- Button to view product details -->
                        </div>
                        <div class="product-item">
                            <span class="product-name">Samsung Galaxy S23 Ultra</span>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.7</span>
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                        </div>
                        <div class="product-item">
                            <span class="product-name">MacBook Pro M2</span>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.9</span>
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                        </div>
                        <div class="product-item">
                            <span class="product-name">Sony WH-1000XM5</span>
                            <div class="product-rating">
                                <span class="stars">★★★★☆</span>
                                <span class="rating-value">4.6</span>
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                        </div>
                        <div class="product-item">
                            <span class="product-name">iPad Pro 12.9</span>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.8</span>
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                        </div>
                    </div>
                </div>

                <!-- Overall Ratings Chart -->
                <div class="section-box">
                    <h3>Overall Reviews Distribution</h3>
                    <canvas id="overallRatingsChart" width="400" height="200"></canvas>
                </div>

                <!-- Product Specific Ratings -->
                <div class="section-box">
                    <h3>Product Specific Ratings</h3>
                    <select id="productSelect" class="product-select">
                        <option value="iphone14">iPhone 14 Pro Max</option>
                        <option value="samsung23">Samsung Galaxy S23 Ultra</option>
                        <option value="macbook">MacBook Pro M2</option>
                        <option value="sony">Sony WH-1000XM5</option>
                        <option value="ipad">iPad Pro 12.9</option>
                    </select>
                    <canvas id="productRatingsChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Manage Users Tab -->
            <div class="tab-pane" id="users">
                <div class="section-box">
                    <h3>User Management</h3>
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Alice Johnson</td>
                                <td>alice.johnson@gmail.com</td>
                                <td>Standard User</td>
                                <td>
                                    <button class="success">Make Admin</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Bob Smith</td>
                                <td>bob.smith@takelot.com</td>
                                <td>Retailer</td>
                                <td>
                                    <button class="success">Make Admin</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Carol Wilson</td>
                                <td>carol.wilson@gmail.com</td>
                                <td>Standard User</td>
                                <td>
                                    <button class="success">Make Admin</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>David Brown</td>
                                <td>david.brown@admin.com</td>
                                <td>Admin</td>
                                <td>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Tony</td>
                                <td>tony3000@starkindustries.com</td>
                                <td>Retailer</td>
                                <td>
                                    <button class="success">Make Admin</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                                <th>Brand</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>001</td>
                                <td>iPhone 14 Pro Max</td>
                                <td>Apple</td>
                                <td>Latest iPhone with advanced camera system</td>
                                <td>R25,999</td>
                                <td>
                                    <button class="success" onclick="editProduct('001')">Modify</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>002</td>
                                <td>Samsung Galaxy S23 Ultra</td>
                                <td>Samsung</td>
                                <td>Premium Android smartphone with S Pen</td>
                                <td>R22,999</td>
                                <td>
                                    <button class="success" onclick="editProduct('002')">Modify</button>
                                    <button class="remove">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>003</td>
                                <td>MacBook Pro M4</td>
                                <td>Apple</td>
                                <td>Professional laptop with M2 chip</td>
                                <td>R49,999</td>
                                <td>
                                    <button class="success" onclick="editProduct('003')">Modify</button>
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
                                <label>Brand:</label>
                                <input type="text" id="editProductBrand" placeholder="Enter brand">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description:</label>
                            <textarea id="editProductDescription" rows="3" placeholder="Enter product description"></textarea>
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

            <!-- Edit Info Tab -->
            <div class="tab-pane" id="profile">
                <div class="section-box">
                    <h3>Edit Profile Information</h3>
                    <form>
                        <div class="form-group">
                            <label for="email-update">Email Address:</label>
                            <input id="email-update" type="email" value="don.alpha@gmail.com" placeholder="Enter new email">
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
</body>
</html>