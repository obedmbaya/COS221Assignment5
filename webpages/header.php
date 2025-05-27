<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CompareIt helps you find the best deals online by comparing prices across top retailers.">
    <meta name="keywords" content="CompareIt, price comparison, deals, sales, e-commerce, shop smart">
    <meta name="author" content="ctrl + alt + elite">

    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <link rel="icon" href="img/favicon.png" type="image/x-icon">
    <title><?php echo isset($page_title) ? $page_title : 'E-Commerce Website'; ?></title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <?php
    // Add page-specific CSS if needed
    if (isset($additional_css)) {
        echo $additional_css;
    }
    if (isset($additional_js)) {
        echo $additional_js;
    }
    ?>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="launch.php"><div class="brand-logo">CompareIt</div></a>
            <div class="search-bar">
                <input type="text" placeholder="Search for products...">
            </div>
            <div class="header-icons">
                <!-- Links for non-logged-in users -->
                <div id="guest-links" style="display: none;">
                    <a href="login.php" title="Login">Login</a>
                    <a href="signup.php" title="Sign Up">Sign Up</a> 
                </div>
                
                <!-- Links for logged-in users -->
                <div id="user-links" style="display: none;">
                    <a href="userDashboard.php" title="Dashboard" id="dashboardlink">Dashboard</a> <!-- needs a script to check user type and go to appropriate dashboard -->
                    <a href="#" id="logout-button" title="Logout">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Include the header JavaScript -->
    <script src="js/header.js"></script>
