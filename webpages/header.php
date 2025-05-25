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
    <!DOCTYPE html>
    <html lang="en">
    <body>
        <!-- Header -->
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