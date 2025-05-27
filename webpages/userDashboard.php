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
            <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button> <!-- logout button -->
        </div>

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="overview">Overview</button>
            <button class="tab-btn" data-tab="profile">Edit Info</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Overview Tab -->
            <div class="tab-pane active" id="overview">
                <!-- Statistics Boxes -->
                <div class="stats-section">
                    <div class="stat-box">
                        <h3>Reviews Made</h3> <!-- Displaying total number of reviews made by user -->
                        <div class="stat-number">47</div>
                    </div>
                    <div class="stat-box">
                        <h3>Average Rating</h3> <!-- Displaying user's average rating given -->
                        <div class="stat-number">4.2</div>
                    </div>
                </div>

                <!-- Reviewed Products Section -->
                <div class="section-box">
                    <h3>Reviewed Products</h3>
                    <div class="products-list">
                        <div class="product-item">
                            <span class="product-name">iPhone 14 Pro Max</span> <!-- product name -->
                            <div class="product-rating">
                                <span class="stars">★★★★☆</span>
                                <span class="rating-value">4 Stars</span> <!-- user's rating for this product -->
                            </div>
                            <div class="product-actions">
                                <button class="view-btn" onclick="window.location.href='view.php'">View</button> <!-- view button -->
                                <button class="update-btn" onclick="showUpdateReview('iphone14')">Update</button> <!-- update button -->
                            </div>
                        </div>
                        <div class="product-item">
                            <span class="product-name">Samsung Galaxy S23 Ultra</span>
                            <div class="product-rating">
                                <span class="stars">★★★☆☆</span>
                                <span class="rating-value">3 Stars</span>
                            </div>
                            <div class="product-actions">
                                <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                                <button class="update-btn" onclick="showUpdateReview('samsung23')">Update</button>
                            </div>
                        </div>
                        <div class="product-item">
                            <span class="product-name">MacBook Pro M2</span>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">5 Stars</span>
                            </div>
                            <div class="product-actions">
                                <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                                <button class="update-btn" onclick="showUpdateReview('macbook')">Update</button>
                            </div>
                        </div>
                        <div class="product-item">
                            <span class="product-name">Sony WH-1000XM5</span>
                            <div class="product-rating">
                                <span class="stars">★☆☆☆☆</span>
                                <span class="rating-value">1 Star</span>
                            </div>
                            <div class="product-actions">
                                <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                                <button class="update-btn" onclick="showUpdateReview('sony')">Update</button>
                            </div>
                        </div>
                        <div class="product-item">
                            <span class="product-name">iPad Pro 12.9</span>
                            <div class="product-rating">
                                <span class="stars">★★★★☆</span>
                                <span class="rating-value">4 Stars</span>
                            </div>
                            <div class="product-actions">
                                <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                                <button class="update-btn" onclick="showUpdateReview('ipad')">Update</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Review Form -->
                <div class="section-box" id="updateReviewForm" style="display: none;">
                    <h3>Update Review</h3>
                    <form>
                        <div class="form-group">
                            <label>Product:</label>
                            <input type="text" id="reviewProductName" readonly>
                        </div>
                        <div class="form-group">
                            <label>Rating:</label>
                            <select id="reviewRating" class="rating-select">
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Review:</label>
                            <textarea id="reviewText" rows="4" placeholder="Enter your review here..."></textarea>
                        </div>
                        <div class="actions">
                            <button type="button" class="success" onclick="saveReview()">Save</button>
                            <button type="button" class="remove" onclick="cancelUpdateReview()">Cancel</button>
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

    <script src="js/userDashboard.js"></script>
</body>
</html>