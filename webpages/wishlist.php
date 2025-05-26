<?php
    $additional_css = '<link rel="stylesheet" href="css/wishlist.css">';
    // $additional_js = '<script src="Link to js file goes here"></script>'; <!-- "js/header.js" for example  -->
    $page_title = "CompareIt | Wishlist";
    include_once 'header.php';
?>
    <main>
        <div class="wishlist-title">
            <h1 class="page-title">My Wishlist</h1>
            <p class="page-description">Items you've saved for later. Add them to your cart whenever you're ready!</p>
        </div>
        
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
                <div class="item-image" style="background-image: url('https://www.incredible.co.za/api/catalog/product/b/2/b2640322_td01_v1_ecommerce_7549.png?width=700&height=700&store=incredibleconnection&image-type=image')"></div>
                <!-- Name and Category... can add more if needed, more from the backend API, maybe ask Jared -->
                <div class="item-details">
                    <h3 class="item-name">Anker 20W USB-C Fast Charger</h3>
                    <p class="item-category">Accessories</p>
                </div>
                <!-- Price -->
                <div class="item-price">R499</div>
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
                <div class="item-image" style="background-image: url('https://store.nintendo.co.za/cdn/shop/products/HACA_011_imgeKBWA_F_R_ad-0_869x869.png?v=1632293466')"></div>
                <div class="item-details">
                    <h3 class="item-name">Nintendo Switch OLED</h3>
                    <p class="item-category">Gaming</p>
                </div>
                <div class="item-price">R7999</div>
                <div class="item-stock">In Stock</div>
                <div class="item-actions">
                    <button class="btn btn-primary">Add to Cart</button>
                    <button class="btn btn-danger">Remove</button>
                </div>
            </div>
            <!-- Item -->
            <div class="wishlist-item">
                <div class="item-image" style="background-image: url('https://images.samsung.com/is/image/samsung/p6pim/za/2302/gallery/za-galaxy-s23-s918-sm-s918bzkqafa-534860088?$684_547_PNG$')"></div>
                <div class="item-details">
                    <h3 class="item-name">Samsung Galaxy S23 Ultra</h3>
                    <p class="item-category">Phone</p>
                </div>
                <div class="item-price">R19,499</div>
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
                <div class="item-image" style="background-image: url('https://p3-ofp.static.pub//fes/cms/2024/07/05/umcrxcnsm2br1itju6gvundeb9s6tf364734.png')"></div>
                <div class="item-details">
                    <h3 class="item-name">Lenovo ThinkPad X1 Carbon</h3>
                    <p class="item-category">Computer</p>
                </div>
                <div class="item-price">R25,799</div>
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