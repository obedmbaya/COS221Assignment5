<?php
$pageTitle = "Signup";
$currentPage = "Signup";
include 'header.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>CompareIt - Sign Up</title>
    <link rel="stylesheet" href="css/signup.css">


</head>


<body>
    <div class="main-content">
        <div class="signup-container">
            <div class="logo">
                <h1>CompareIt</h1>
                <p>Join the future of price comparison</p>

            </div>

            <div class="success-message" id="successMessage">
                <strong>Account created successfully!</strong>
            </div>








            <form action="process_signup.php" method="POST" id="signup-form" enctype="multipart/form-data">

                <!-- Account Type Selection -->

                <div class="account-type-selector">
                    <h3>Choose Your Account Type</h3>
                    <div class="account-types">
                        <div class="account-type-option">
                            <input type="radio" id="customer" name="account_type" value="customer" checked>
                            <label for="customer" class="account-type-card">
                                <span class="icon">👤</span>
                                <h4>Customer</h4>
                                <p>Browse and compare product prices</p>
                            </label>
                        </div>

                        <div class="account-type-option">

                            <input type="radio" id="retailer" name="account_type" value="retailer">
                            <label for="retailer" class="account-type-card">
                                <span class="icon">🏪</span>
                                <h4>Retailer</h4>
                                <p>List your products and manage inventory</p>
                            </label>
                        </div>
                    </div>
                </div>










                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>

                </div>

                <!-- Business Name Field -->

                <div class="form-group retailer-fields" id="business-name-field">
                    <label for="business_name">Business Name</label>
                    <input type="text" id="business_name" name="business_name" placeholder="Enter your business name">

                </div>

                <div class="form-group retailer-fields" id="registration-number-field">
                    <label for="registration_number">Business Registration Number</label>
                    <input type="text" id="registration_number" name="registration_number" placeholder="Enter your business registration number">

                </div>



                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>


                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                        <div class="password-requirements">
                            Password must be at least 8 characters long and include a number and special character
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm password" required>


                    </div>
                </div>

                <div class="retailer-fields" id="retailer-verification-section">
                    <div class="verification-section">
                        <h4>
                            <span>🔒</span>
                            Retailer Verification required
                        </h4>
                        <p>
                            To maintain platform integrity, we require verification documents for retailer accounts.
                            Please upload one of the following:
                        </p>
                        <p><strong>Business License or Registration Certificate</strong></p>

                        <div class="file-upload-area" id="fileUploadArea">
                            <div class="upload-text">Click to upload document</div>
                            <div class="upload-subtext">(PDF, JPG, PNG - Max 5MB)</div>
                            <input type="file" id="verification_document" name="verification_document"
                                accept=".pdf,.jpg,.jpeg,.png" style="display:none;">

                        </div>

                        <div class="verification-status" id="verificationStatus">
                            Document uploaded successfully. Verification pending.
                        </div>
                    </div>
                </div>


                <button type="submit" class="submit-btn" id="submitBtn">
                    Create Account
                </button>
            </form>
            <div class="login-link">
                <!-- linking login page -->

                Already have an account? <a href="login.php">Log in</a>
            </div>
        </div>
    </div>




    <?php
    include 'footer.php';

    echo '<script src="js/signup.js"></script>';
    ?>
</body>