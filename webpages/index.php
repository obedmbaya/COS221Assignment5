<?php
    $additional_css = '<link rel="stylesheet" href="css/styles.css">'; 
    // $additional_js = '<script src=""></script>';
    $page_title = "CompareIt | Home";
    include_once 'header.php';
?>
    <script src="index.js"></script>
    <div class="container">
        <!-- Filter Section -->
        <div class="filter-section">
            <h2>Filter</h2>
            <!--<div class="search-container">
                <span class="search-icon">
                    <img src="img/search.png" alt="Search">
                </span>
                <input type="text" placeholder="Search products...">
            </div>-->
            <div class="filter-group">
                <label for="sort-by">Sort by</label>
                <select id="sort-by">
                    <option value="name-asc">Name Ascending</option>
                    <option value="name-desc">Name Descending</option>
                    <option value="price-asc">Price Ascending</option>
                    <option value="price-desc">Price Descending</option>
                    <option value="newest">Newest</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="category">Category</label>
                <select id="category">
                    <option value="">All Categories</option>
                    <option value="computers">Computers (6)</option>
                    <option value="laptops" class="nested-option">- Laptop (4)</option>
                    <option value="desktops" class="nested-option">- Desktop (2)</option>
                    <option value="mobile-devices">Mobile devices (5)</option>
                    <option value="smartphones" class="nested-option">- Smartphone (4)</option>
                    <option value="tablets" class="nested-option">- Tablet (1)</option>
                    <option value="gaming">Gaming (4)</option>
                    <option value="console" class="nested-option">- Console (2)</option>
                    <option value="handheld-console" class="nested-option">- Handheld Console (1)</option>
                    <option value="gaming-pc" class="nested-option">- Gaming PC (1)</option>
                    <option value="camera">Camera (3)</option>
                    <option value="digital-camera" class="nested-option">- Digital Camera (1)</option>
                    <option value="video-camera" class="nested-option">- Video Camera (1)</option>
                    <option value="web-camera" class="nested-option">- Web Camera (1)</option>
                    <option value="accessories">Accessories (2)</option>
                    <option value="charger" class="nested-option">- Charger (1)</option>
                    <option value="phone-case" class="nested-option">- Phone Case (1)</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="brand">Brand</label>
                <select id="brand">
                    <option value="">All Brands</option>
                    <option value="hp">HP (2)</option>
                    <option value="apple">Apple (3)</option>
                    <option value="samsung">Samsung (1)</option>
                    <option value="nintendo">Nintendo (3)</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="price">Price Range</label>
                <select id="price">
                    <option value="">All Prices</option>
                    <option value="under-1000">Under R1000</option>
                    <option value="1000-2500">R1000 - R2500</option>
                    <option value="2500-10000">R2500 - R10000</option>
                    <option value="over-10000">Over R10000</option>
                </select>
            </div>
        </div>
        
        <!--Products-->
        <div class="products-section">

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://p3-ofp.static.pub//fes/cms/2024/07/05/umcrxcnsm2br1itju6gvundeb9s6tf364734.png')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Lenovo</div>
                    <div class="product-name">ThinkPad X1 Carbon</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Incredible Connection</span><span class="product-price">R25,799</span></div>
                    <div class="store-offer"><span class="store-name">Makro</span><span class="product-price">R26,199</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://hp.widen.net/content/aagkkclbag/png/aagkkclbag.png?w=800&dpi=72&color=ffffff00')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">HP</div>
                    <div class="product-name">All-in-One 24-df1006ni</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Game</span><span class="product-price">R12,999</span></div>
                    <div class="store-offer"><span class="store-name">Makro</span><span class="product-price">R13,299</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://images.samsung.com/is/image/samsung/p6pim/za/2302/gallery/za-galaxy-s23-s918-sm-s918bzkqafa-534860088?$684_547_PNG$')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Samsung</div>
                    <div class="product-name">Galaxy S23 Ultra</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Makro</span><span class="product-price">R19,499</span></div>
                    <div class="store-offer"><span class="store-name">Game</span><span class="product-price">R19,999</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://gmedia.playstation.com/is/image/SIEPDC/ps5-buy-now-image-block-01-ja-jp-20nov23?$1600px--t$')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Sony</div>
                    <div class="product-name">PlayStation 5</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Makro</span><span class="product-price">R12,999</span></div>
                    <div class="store-offer"><span class="store-name">Takealot</span><span class="product-price">R13,299</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/notebooks/xps-notebooks/9345/media-gallery/touch/gray/notebook-xps-13-9345-t-gray-gallery-2.psd?fmt=png-alpha&pscan=auto&scl=1&hei=402&wid=679&qlt=100,1&resMode=sharp2&size=679,402&chrss=full')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Dell</div>
                    <div class="product-name">XPS 13</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Makro</span><span class="product-price">R22,999</span></div>
                    <div class="store-offer"><span class="store-name">Takealot</span><span class="product-price">R23,499</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://store.storeimages.cdn-apple.com/1/as-images.apple.com/is/mbp16-spaceblack-gallery1-202410?wid=4000&hei=3074&fmt=jpeg&qlt=90&.v=Nys1UFFBTmI1T0VnWWNyeEZhdDFYbmpXSTNqQ2U1MjQxSHBKRkRoWUE0bmd1eUJ6eHZMSFFNMld6aTRncXNRUlJWYlIvRkkxemNIb09FY29ZRmVrUDJKN054NGh1S3I0S1ZMeHJJL1hOdmJnbjFYUVlyMjZtU1RuRXBGY1VsRU0')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Apple</div>
                    <div class="product-name">MacBook Pro 16"</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">iStore</span><span class="product-price">R28,999</span></div>
                    <div class="store-offer"><span class="store-name">Takealot</span><span class="product-price">R29,499</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://www.hponline.co.za/cdn/shop/files/laptops-7k8a0ea-40178403147989_800x.png?v=1686754731')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">HP</div>
                    <div class="product-name">Pavilion 15</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Game</span><span class="product-price">R10,499</span></div>
                    <div class="store-offer"><span class="store-name">Incredible Connection</span><span class="product-price">R11,199</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://static2-ecemea.acer.com/media/catalog/product/_/_/________a__acer-aspire-tc-895_elite19-main_small_rfkm_1_dt.betek.009.png?quality=80&bg-color=255,255,255&fit=bounds&height=500&width=500&canvas=500:500&format=jpeg')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Acer</div>
                    <div class="product-name">Aspire TC-895</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Takealot</span><span class="product-price">R10,499</span></div>
                    <div class="store-offer"><span class="store-name">Game</span><span class="product-price">R10,999</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://www.istore.co.za/media/catalog/product/cache/7cbfd4bf9761b066f119e95af17e67c5/i/p/iphone_15_plus_black_pdp_image_position-1__wwen_5.jpg')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Apple</div>
                    <div class="product-name">iPhone 15</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">iStore</span><span class="product-price">R23,499</span></div>
                    <div class="store-offer"><span class="store-name">Takealot</span><span class="product-price">R24,000</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://www.hificorp.co.za/api/catalog/product/g/a/galaxy_tab_s9_plus_graphite_product_image_front_ecommerce_01eb.png?width=700&height=700&store=hificorporation&image-type=image')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Samsung</div>
                    <div class="product-name">Galaxy Tab S9</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Makro</span><span class="product-price">R12,999</span></div>
                    <div class="store-offer"><span class="store-name">Game</span><span class="product-price">R13,299</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://store.nintendo.co.za/cdn/shop/products/HACA_011_imgeKBWA_F_R_ad-0_869x869.png?v=1632293466')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Nintendo</div>
                    <div class="product-name">Switch OLED</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Takealot</span><span class="product-price">R7,999</span></div>
                    <div class="store-offer"><span class="store-name">Game</span><span class="product-price">R8,299</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://www.incredible.co.za/api/catalog/product/c/a/can_ecommerce_7170.png?width=700&height=700&store=incredibleconnection&image-type=image')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Canon</div>
                    <div class="product-name">EOS 2000D</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer"><span class="store-name">Makro</span><span class="product-price">R6,999</span></div>
                    <div class="store-offer"><span class="store-name">Takealot</span><span class="product-price">R7,499</span></div>
                    <div class="product-actions"><div class="heart-icon">♡</div><a href="view.php"><button class="view-offers-btn">View Offers</button></a></div>
                </div>
            </div>

            <div class="product-card">
                <a href="view.php">
                    <div class="product-image" style="background-image: url('https://www.incredible.co.za/api/catalog/product/b/2/b2640322_td01_v1_ecommerce_7549.png?width=700&height=700&store=incredibleconnection&image-type=image')"></div>
                </a>
                <div class="product-details">
                    <div class="product-brand">Anker</div>
                    <div class="product-name">Anker 20W USB-C Fast Charger</div>
                    <h4 class="offers-heading">Current offers</h4>
                    <div class="store-offer">
                        <span class="store-name">Takealot</span>
                        <span class="product-price">R499</span>
                    </div>
                    <div class="store-offer">
                        <span class="store-name">Incredible Connection</span>
                        <span class="product-price">R529</span>
                    </div>
                    <div class="product-actions">
                        <div class="heart-icon">♡</div>
                        <a href="view.php"><button class="view-offers-btn">View Offers</button></a>
                    </div>
                </div>
            </div> 
        </div>    
    </div>

    <?php
        require_once 'footer.php';
    ?>
</body>

</html>
