<?php
    $additional_css = '<link rel="stylesheet" href="css/styles.css">';
    // $additional_js = '<script src="Link to js file goes here"></script>'; <!-- "js/header.js" for example  -->
    $page_title = "Profile";
    include_once 'header.php';
?>
    <div class="view-container">
        <div class="product-top-section">
            <div class="product-main-box">
                <div class="product-image-section">
                    <div class="product-main-image" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403147989_800x.png?v=1686754731')">
                        <div class="carousel-controls">
                            <button class="carousel-control" onclick="prevImage()">&lt;</button>
                            <button class="carousel-control" onclick="nextImage()">&gt;</button>
                        </div>
                    </div>
                    <div class="image-thumbnails">
                        <div class="image-thumbnail active" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403147989_800x.png?v=1686754731')" onclick="selectImage(0)"></div>
                        <div class="image-thumbnail" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403344597_800x.jpg?v=1686754737')" onclick="selectImage(1)"></div>
                        <div class="image-thumbnail" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403475669_800x.jpg?v=1686754742')" onclick="selectImage(2)"></div>
                        <div class="image-thumbnail" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403573973_800x.jpg?v=1686754929')" onclick="selectImage(3)"></div>
                        <div class="image-thumbnail" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403803349_800x.jpg?v=1686754926')" onclick="selectImage(4)"></div>
                        <div class="image-thumbnail" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403999957_800x.jpg?v=1686755091')" onclick="selectImage(5)"></div>
                    </div> 
                </div>
                
                <!-- Main product info section -->
                <div class="product-info-section">
                    <h1 class="product-name">HP Pavilion 15-inch Laptop</h1>
                    <div class="product-brand">HP</div>
                    <div class="price-label">From</div>
                    <div class="view-product-price">R12,999</div>
                </div>
            </div>
            
            <!-- Price comparison section -->
            <div class="store-options-box">
                <div class="store-option">
                    <div class="store-name">Incredible Connection</div>
                    <div class="store-price">R12,999</div>
                </div>
                <div class="store-option">
                    <div class="store-name">Evetech</div>
                    <div class="store-price">R13,499</div>
                </div>
                <div class="store-option">
                    <div class="store-name">Takealot</div>
                    <div class="store-price">R14,999</div>
                </div>
                <button class="wishlist-btn">
                    <span class="wishlist-heart-icon">♥</span>
                    ADD TO WISHLIST
                </button>
            </div>
        </div>
        
        <!-- Description section -->
        <div class="description-box">
            <h2 class="section-title">Description</h2>
            <div class="description-content">
                <p>The HP Pavilion 15-inch Laptop combines performance and design to help you tackle any task. Powered by the latest 12th Gen Intel Core i7 processor and featuring 16GB of RAM, this laptop lets you breeze through the most demanding applications. The 15.6-inch Full HD display brings content to life with vibrant colors and sharp details.</p>
                <p>With a sleek aluminum chassis, this laptop isn't just powerful but also portable and stylish. The backlit keyboard makes typing comfortable even in low-light environments, and the precision touchpad provides smooth navigation.</p>
                <p>The laptop includes a 512GB solid-state drive (SSD) for storage, providing ample space for your files while ensuring quick boot times and fast application loads. Connectivity options include USB Type-C, USB Type-A, HDMI, and a headphone/microphone combo jack.</p>
            </div>
        </div>
        
        <!-- Product info section -->
        <div class="product-info-box">
            <h2 class="section-title">Product Information</h2>
            <table class="info-table">
                <tr>
                    <td>Category</td>
                    <td>Computer / Laptop</td>
                </tr>
                <tr>
                    <td>Brand</td>
                    <td>HP</td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td>Black</td>
                </tr>
                <tr>
                    <td>Materials</td>
                    <td>Aluminum</td>
                </tr>
                <tr>
                    <td>What's in the box</td>
                    <td>1x Laptop, 1x Power adapter, 1x Quick start guide</td>
                </tr>
            </table>
        </div>
        
        <!-- Recommended products section -->
        <div class="recommended-box">
            <h2 class="section-title">Recommended Items</h2>
            <div class="carousel-nav">
                <button class="carousel-nav-btn" onclick="scrollRecommended(-220)">❮</button>
                <button class="carousel-nav-btn" onclick="scrollRecommended(220)">❯</button>
            </div>
            <div class="recommended-carousel">
                <div class="recommended-item">
                    <div class="rec-item-image" style="background-image: url('https://p3-ofp.static.pub//fes/cms/2024/07/05/umcrxcnsm2br1itju6gvundeb9s6tf364734.png')"></div>
                    <div class="rec-item-details">
                        <div class="rec-item-brand">Lenovo</div>
                        <div class="rec-item-name">ThinkPad X1 Carbon</div>
                        <div class="rec-item-price">R25,799</div>
                    </div>
                </div>
                
                <div class="recommended-item">
                    <div class="rec-item-image" style="background-image: url('https://hp.widen.net/content/aagkkclbag/png/aagkkclbag.png?w=800&dpi=72&color=ffffff00')"></div>
                    <div class="rec-item-details">
                        <div class="rec-item-brand">HP</div>
                        <div class="rec-item-name">All-in-One 24-df1006ni</div>
                        <div class="rec-item-price">R12,999</div>
                    </div>
                </div>
                
                <div class="recommended-item">
                    <div class="rec-item-image" style="background-image: url('https://images.samsung.com/is/image/samsung/p6pim/za/2302/gallery/za-galaxy-s23-s918-sm-s918bzkqafa-534860088?$684_547_PNG$')"></div>
                    <div class="rec-item-details">
                        <div class="rec-item-brand">Samsung</div>
                        <div class="rec-item-name">Galaxy S23 Ultra</div>
                        <div class="rec-item-price">R19,499</div>
                    </div>
                </div>
                
                <div class="recommended-item">
                    <div class="rec-item-image" style="background-image: url('https://gmedia.playstation.com/is/image/SIEPDC/ps5-buy-now-image-block-01-ja-jp-20nov23?$1600px--t$')"></div>
                    <div class="rec-item-details">
                        <div class="rec-item-brand">Sony</div>
                        <div class="rec-item-name">PlayStation 5</div>
                        <div class="rec-item-price">R12,999</div>
                    </div>
                </div>
                
                <div class="recommended-item">
                    <div class="rec-item-image" style="background-image: url('https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/notebooks/xps-notebooks/9345/media-gallery/touch/gray/notebook-xps-13-9345-t-gray-gallery-2.psd?fmt=png-alpha&pscan=auto&scl=1&hei=402&wid=679&qlt=100,1&resMode=sharp2&size=679,402&chrss=full')"></div>
                    <div class="rec-item-details">
                        <div class="rec-item-brand">Dell</div>
                        <div class="rec-item-name">XPS 13</div>
                        <div class="rec-item-price">R22,999</div>
                    </div>
                </div>
                
                <div class="recommended-item">
                    <div class="rec-item-image" style="background-image: url('https://store.storeimages.cdn-apple.com/1/as-images.apple.com/is/mbp16-spaceblack-gallery1-202410?wid=4000&hei=3074&fmt=jpeg&qlt=90&.v=Nys1UFFBTmI1T0VnWWNyeEZhdDFYbmpXSTNqQ2U1MjQxSHBKRkRoWUE0bmd1eUJ6eHZMSFFNMld6aTRncXNRUlJWYlIvRkkxemNIb09FY29ZRmVrUDJKN054NGh1S3I0S1ZMeHJJL1hOdmJnbjFYUVlyMjZtU1RuRXBGY1VsRU0')"></div>
                    <div class="rec-item-details">
                        <div class="rec-item-brand">Apple</div>
                        <div class="rec-item-name">MacBook Pro 16"</div>
                        <div class="rec-item-price">R28,999</div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Reviews section -->
        <div class="reviews-section">
            <div class="reviews-box">
                <h2 class="section-title">Reviews</h2>
                <div class="overall-rating">
                    <div class="rating-value">4.5</div>
                    <div class="stars">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star half-filled">★</span>
                    </div>
                </div>
                
                <!-- Review 1 -->
                <div class="review-item">
                    <div class="reviewer-name">JohnDoe123</div>
                    <div class="review-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                    </div>
                    <div class="review-text">
                        This laptop exceeded my expectations. The performance is great and the battery life is amazing. I've been using it for work and gaming, and it handles everything I throw at it.
                    </div>
                </div>
                
                <!-- Review 2 -->
                <div class="review-item">
                    <div class="reviewer-name">TechLover22</div>
                    <div class="review-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star">★</span>
                    </div>
                    <div class="review-text">
                        Great value for money. The display is fantastic and the keyboard is comfortable. I knocked a star off because the webcam quality could be better, but overall I'm very satisfied.
                    </div>
                </div>
                
                <!-- Review 3 -->
                <div class="review-item">
                    <div class="reviewer-name">ComputerEnthusiast</div>
                    <div class="review-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star">★</span>
                    </div>
                    <div class="review-text">
                        I purchased this laptop for university, and it has been perfect. Fast, reliable, and the battery lasts all day. The only downside is that it gets a bit warm during intensive tasks.
                    </div>
                </div>
                
                <a href="review.php"><button class="see-more-btn">More Reviews</button></a>
            </div>
            
            <!--Write review section-->
            <div class="write-review-box">
                <h2 class="section-title">Write a Review</h2>
                <div class="rate-this-product">Rate this product:</div>
                <div class="rating-stars">
                    <span class="rating-star" onclick="setRating(1)">★</span>
                    <span class="rating-star" onclick="setRating(2)">★</span>
                    <span class="rating-star" onclick="setRating(3)">★</span>
                    <span class="rating-star" onclick="setRating(4)">★</span>
                    <span class="rating-star" onclick="setRating(5)">★</span>
                </div>
                <textarea class="review-textarea" placeholder="Write your review here..."></textarea>
                <button class="post-review-btn">POST</button>
            </div>
        </div>
    </div>

    <?php
        require_once 'footer.php';
    ?>

    <script src="js/view.js"></script>
</body>

</html>
