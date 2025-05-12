<?php
    $additional_css = '<link rel="stylesheet" href="css/wishlist.css">';
    // $additional_js = '<script src="Link to js file goes here"></script>'; <!-- "js/header.js" for example  -->
    $page_title = "CompareIt | Wishlist";
    include_once 'header.php';
?>
    <main>
        <h1 class="page-title">My Wishlist</h1>
        <p class="page-description">Items you've saved for later. Add them to your cart whenever you're ready!</p>
        
        <div class="wishlist-container">
            <!-- Wishlist table header -->
            <div class="wishlist-header">
                <div>Product</div>
                <div>Details</div>
                <div>Price</div>
                <div>Availability</div>
                <div>Actions</div>
            </div>
            
            <!-- Wishlist items -->
            <div class="wishlist-item">
                <!-- pic -->
                <div class="item-image">Image</div>
                <!-- Name and Category... can add more if needed, more from the backend API, maybe ask Jared -->
                <div class="item-details">
                    <h3 class="item-name">Wireless Headphones</h3>
                    <p class="item-category">Electronics</p>
                </div>
                <!-- Price -->
                <div class="item-price">$129.99</div>
                <!-- Availability -->
                <div class="item-stock">In Stock</div>
                <div class="item-actions">
                    <!-- Butt 1 -->
                    <button class="btn btn-primary">Add to Cart</button>
                    <!-- Butt 2 -->
                    <button class="btn btn-danger">Remove</button>
                </div>
            </div>
            <!-- Item -->
            <div class="wishlist-item">
                <div class="item-image">Image</div>
                <div class="item-details">
                    <h3 class="item-name">Smart Watch</h3>
                    <p class="item-category">Electronics</p>
                </div>
                <div class="item-price">$199.99</div>
                <div class="item-stock">In Stock</div>
                <div class="item-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-danger">Remove</button>
                </div>
            </div>
            <!-- Item -->
            <div class="wishlist-item">
                <div class="item-image">Image</div>
                <div class="item-details">
                    <h3 class="item-name">Leather Wallet</h3>
                    <p class="item-category">Accessories</p>
                </div>
                <div class="item-price">$59.99</div>
                <div class="item-stock out-of-stock">Out of Stock</div>
                <div class="item-actions">
                    <!-- Butt 1 -->
                    <!-- !!!!OUT OF STOCK SO SHOULD BE DISABLED!!!! -->
                    <button class="btn btn-outline">Notify Me</button>
                    <!-- Butt 2 -->
                    <button class="btn btn-danger">Remove</button>
                </div>
            </div>
            <!-- Item -->
            <div class="wishlist-item">
                <div class="item-image">Image</div>
                <div class="item-details">
                    <h3 class="item-name">Running Shoes</h3>
                    <p class="item-category">Sports & Fitness</p>
                </div>
                <div class="item-price">$89.99</div>
                <div class="item-stock">In Stock</div>
                <div class="item-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-danger">Remove</button>
                </div>
            </div>
            
            <!-- If the wishlist is empty... but I always want something.... 
            <div class="empty-wishlist">
                <div class="empty-wishlist-icon">❤️</div>
                <h3>Your wishlist is empty</h3>
                <p>Add items you love to your wishlist. Review them anytime and easily move them to your cart.</p>
                <a href="#" class="btn btn-primary">Start Shopping</a>
            </div>
            -->
        </div>
        
    </main>
    <?php
        require_once 'footer.php';
    ?>
</body>
</html>