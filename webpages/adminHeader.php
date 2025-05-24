<?php
// adminHeader.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Dashboard for CompareIt">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Dashboard'; ?></title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/adminSidebar.css">
    <!-- <link rel="stylesheet" href="css/adminDash.css"> -->
    <?php
    if (isset($additional_css)) {
        echo $additional_css;
    }
    if (isset($additional_js)) {
        echo $additional_js;
    }
    ?>
</head>
<body>
    <header>
        <div class="header-container">
            <a href = "index.php"><div class="logo">CompareIt</div></a>
            <div class="search-bar">
                <img src="img/search.png" alt="Search" class="search-icon">
                <input type="text" placeholder="Search for products...">
            </div>
            <div class="header-icons">
                <a href="deals.php" title="Deals">Deals</a>
                <a href="wishlist.php" title="Wishlist">Wishlist (4)</a>
                <a href="login.php" title="Account">Account</a>
                <a href="manage.php" title="Account">Manage</a>
            </div>
        </div>
    </header>
    <!-- Admin content wrapper -->
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="retailerDash.php" <?php echo basename($_SERVER['PHP_SELF']) == 'retailerDash.php' ? 'class="active"' : ''; ?>>
                        <i>👤</i>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="retailerProducts.php" <?php echo basename($_SERVER['PHP_SELF']) == 'retailerProducts.php' ? 'class="active"' : ''; ?>>
                        <i>📦</i>
                        <span>Products</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i>🚪</i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main content area -->
        <div class="main-content">