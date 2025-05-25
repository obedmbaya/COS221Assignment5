<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
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
                    <h3 class="user-name">Puff Daddy</h3> <!-- user name -->
                    <p class="user-email">puffy@gmail.com</p><!-- user email -->
                </div>
            </div>
            <button class="logout-btn">Logout</button> <!-- logout button -->
        </div>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="products">Top Products</button>
            <button class="tab-btn" data-tab="profile">Edit Info</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Top Products Tab -->
            <div class="tab-pane active" id="products">
                <!-- Top Rated Products Section -->
                <div class="section-box">
                    <h3>Top Rated Products</h3>
                    <div class="products-list">
                        <div class="product-item">
                            <span class="product-name">iPhone 14 Pro Max</span> <!-- product name -->
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.8</span> <!-- product rating -->
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button> <!-- view button -->
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

                <!-- Product Specific Ratings -->
                <div class="section-box">
                    <h3>Product Ratings Analysis</h3>
                    <select id="customerProductSelect" class="product-select">
                        <option value="iphone14">iPhone 14 Pro Max</option>
                        <option value="samsung23">Samsung Galaxy S23 Ultra</option>
                        <option value="macbook">MacBook Pro M2</option>
                        <option value="sony">Sony WH-1000XM5</option>
                        <option value="ipad">iPad Pro 12.9</option>
                        <option value="airpods">AirPods Pro</option>
                        <option value="nintendo">Nintendo Switch OLED</option>
                    </select>
                    <canvas id="customerProductRatingsChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Edit Info Tab -->
            <div class="tab-pane" id="profile">
                <div class="section-box">
                    <h3>Edit Profile Information</h3>
                    <form>
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input type="email" value="puffy@gmail.com" placeholder="Enter new email">
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