<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'E-Commerce Website'; ?></title>
    <link rel="stylesheet" href="css/header.css">
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
                <div class="logo">PriceCheck</div>
                <div class="search-bar">
                    <input type="text" placeholder="Search for products...">
                </div>
                <div class="header-icons">
                    <a href="#" title="Account">Account</a>
                    <a href="#" title="Wishlist">Wishlist</a>
                    <a href="#" title="Cart">Cart (0)</a>
                </div>
            </div>
        
    </header>