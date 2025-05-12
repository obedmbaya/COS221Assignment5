<?php
    $additional_css = '<link rel="stylesheet" href="css/review.css">';
    // $additional_js = '<script src="js/profile.js"></script>';
    $page_title = "CompareIt | Reviews";
    include_once 'header.php';
?>
    
    
    <main>
        <!-- Product Review -->
        <section class="product-review-header">
            <div class="product-thumbnail">Product Image</div>
            <div class="product-info">
                <h1>!!!Headphones!!!</h1>
                <div class="product-rating">
                    <span class="stars">★★★★☆</span>
                    <span>4.2 out of 5 (42 Reviews)</span>
                </div>
                <p><a href="product.html">Back to Product</a></p>
            </div>
        </section>
        
        <!-- Simplified Review Summary -->
        <section class="review-summary-section">
            <!-- Not the best yet, still need to make good, really bad atm -->
            <div class="review-summary">
                <div class="review-stats">
                    <!-- Overall average need to add all and divide by num (will implement in JS) -->
                    <div class="review-average">
                        4.2
                        <div class="average-stars">★★★★☆</div>
                        <!-- Number of reviews, use with calc later -->
                        <div class="review-total">42 reviews</div>
                    </div>
                    <div class="rating-breakdown">
                        <div class="rating-row">
                            <!-- How many gave 5 stars -->
                             <!--  -->
                            <span class="rating-label">5★</span>
                            <!-- NUmber of 5 starts -->
                            <span class="rating-count">25</span>
                        </div>
                        <div class="rating-row">
                            <span class="rating-label">4★</span>
                            <span class="rating-count">11</span>
                        </div>
                        <div class="rating-row">
                            <span class="rating-label">3★</span>
                            <span class="rating-count">4</span>
                        </div>
                        <div class="rating-row">
                            <span class="rating-label">2★</span>
                            <span class="rating-count">2</span>
                        </div>
                        <div class="rating-row">
                            <span class="rating-label">1★</span>
                            <span class="rating-count">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <button class="add-review-btn" id="openReviewModal">Write a Review</button>
        </section>
        
        <!-- Reviews -->
        <section class="reviews-section">
            <h2 class="section-title">Customer Reviews (42)</h2>
            
            <!-- Review -->
            <div class="review-card">
                <div class="review-header">
                    <!-- Name -->
                    <span class="reviewer-info">Zaman</span>
                    <!-- Date -->
                    <span class="review-date">Tomorrow</span>
                </div>
                <!-- Whhat review they gave -->
                <div class="review-rating">★★★★★</div>
                <!-- Title -->
                <h3 class="review-title">womp womp</h3>
                <!-- Comment -->
                <p class="review-text">Tung Tung Tung Tung Tung Sahoor</p>
                <!-- Images -->
                <div class="review-images">
                    <div class="review-image">Image 1</div>
                    <div class="review-image">Image 2</div>
                </div>
            </div>
            
            <!-- Review -->
            <div class="review-card">
                <div class="review-header">
                    <span class="reviewer-info">Jared</span>
                    <span class="review-date">The day After</span>
                </div>
                <div class="review-rating">★★★★☆☆</div>
                <h3 class="review-title">I was on another planet!!!</h3>
                <p class="review-text">I started elevating at the brr brr bata bing enlightened my ears</p>

            </div>
            
            <!-- Review -->
            <div class="review-card">
                <div class="review-header">
                    <span class="reviewer-info">Michael Deez Nutz</span>
                    <span class="review-date">April 5, 2025</span>
                </div>
                <div class="review-rating">★★★★★</div>
                <h3 class="review-title">Worth every cent</h3>
                <p class="review-text">TEEEN out of TEEEEN</p>
                <div class="review-images">
                    <div class="review-image">Image 1</div>
                </div>
            </div>
            
            <!-- Review -->
            <div class="review-card">
                <div class="review-header">
                    <span class="reviewer-info">Josh</span>
                    <span class="review-date">Never</span>
                </div>
                <div class="review-rating">★★★☆☆</div>
                <h3 class="review-title">Changed who I am </h3>
                <p class="review-text">As soon as I put these in my ears, I knew I didn't like the opposite gender!</p>
            </div>
            
            <!-- Review -->
            <div class="review-card">
                <div class="review-header">
                    <span class="reviewer-info">Ben Dover</span>
                    <span class="review-date">March 27, 2025</span>
                </div>
                <div class="review-rating">★★★★★</div>
                <h3 class="review-title">Best headphones I've ever owned</h3>
                <p class="review-text">A whole new WORLD!</p>

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
        </section>
    </main>
    <?php
        require_once 'footer.php';
    ?>
</body>
</html>