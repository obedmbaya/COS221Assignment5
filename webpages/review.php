<?php
    $additional_css = '<link rel="stylesheet" href="css/review.css">';
    // $additional_js = '<script src="js/review.js"></script>';
    $page_title = "CompareIt | Reviews";
    include_once 'header.php';
?>
    
    <main>
        <!-- Product Review -->
        <section class="product-review-header">
            <div class="product-thumbnail" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403147989_800x.png?v=1686754731')"></div>
            <div class="product-info">
                <h1>HP Pavillion 15-inch Laptop</h1>
                <div class="product-rating">
                    <div class="stars">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star half-filled">★</span>
                    </div>
                    <span>4.5 out of 5 (42 Reviews)</span>
                </div>
                <p><a href="product.html">Back to Product</a></p>
            </div>
        </section>
        
        <!-- Reviews Section -->
        <section class="reviews-section">
            <div class="reviews-box">
                <h2 class="section-title">Customer Reviews</h2>
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
                    <div class="reviewer-name">Zaman</div>
                    <div class="review-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                    </div>
                    <div class="review-text">
                        Tung Tung Tung Tung Tung Sahoor
                    </div>
                </div>
                
                <!-- Review 2 -->
                <div class="review-item">
                    <div class="reviewer-name">Jared</div>
                    <div class="review-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star">★</span>
                    </div>
                    <div class="review-text">
                        I started elevating at the brr brr bata bing enlightened my ears
                    </div>
                </div>
                
                <!-- Review 3 -->
                <div class="review-item">
                    <div class="reviewer-name">Michael Deez Nutz</div>
                    <div class="review-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                    </div>
                    <div class="review-text">
                        TEEEN out of TEEEEN
                    </div>
                </div>
                
                <!-- Review 4 -->
                <div class="review-item">
                    <div class="reviewer-name">Josh</div>
                    <div class="review-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                    <div class="review-text">
                        As soon as I put these in my ears, I knew I didn't like the opposite gender!
                    </div>
                </div>
                
                <!-- Review 5 -->
                <div class="review-item">
                    <div class="reviewer-name">Ben Dover</div>
                    <div class="review-rating">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                    </div>
                    <div class="review-text">
                        A whole new WORLD!
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <div class="page-item active"><a href="#" class="page-link">1</a></div>
                    <div class="page-item"><a href="#" class="page-link">2</a></div>
                    <div class="page-item"><a href="#" class="page-link">3</a></div>
                    <div class="page-item"><a href="#" class="page-link">4</a></div>
                    <div class="page-item"><a href="#" class="page-link">5</a></div>
                    <div class="page-item"><a href="#" class="page-link">Next »</a></div>
                </div>
            </div>
            
            <!-- Write review section -->
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
        </section>
    </main>

    <script src="js/review.js"></script>
    
    <?php
        require_once 'footer.php';
    ?>
</body>
</html>