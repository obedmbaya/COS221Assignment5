document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    loadProducts();
});

async function initializeFilters() {
    try {
        // Fetch brands
        const brandsResponse = await fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'getAllBrands'
            })
        });
        const brandsData = await brandsResponse.json();
        if (brandsData.status === 'success' && brandsData.data) {
            populateBrands(brandsData.data);
        }

        // Fetch categories
        const categoriesResponse = await fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'getAllCategories'
            })
        });
        const categoriesData = await categoriesResponse.json();
        if (categoriesData.status === 'success' && categoriesData.data) {
            populateCategories(categoriesData.data);
        }

        // Add event listeners for filters and search
        document.querySelector('.search-bar').addEventListener('input', debounce(loadProducts, 500));
        document.getElementById('sort-by').addEventListener('change', loadProducts);
        document.getElementById('category').addEventListener('change', loadProducts);
        document.getElementById('brand').addEventListener('change', loadProducts);
        document.getElementById('price').addEventListener('change', loadProducts);
    } catch (error) {
        console.error('Error initializing filters:', error);
    }
}

function populateBrands(brands) {
    const brandSelect = document.getElementById('brand');
    brandSelect.innerHTML = '<option value="">All Brands</option>';
    brands.forEach(brand => {
        const option = document.createElement('option');
        option.value = brand;
        option.textContent = brand;
        brandSelect.appendChild(option);
    });
}

function populateCategories(categories) {
    const categorySelect = document.getElementById('category');
    categorySelect.innerHTML = '<option value="">All Categories</option>';
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.CategoryName;
        option.textContent = category.CategoryName;
        categorySelect.appendChild(option);
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

async function loadProducts() {
    try {
        const searchInput = document.querySelector('.search-bar').value;
        const sortBy = document.getElementById('sort-by').value;
        const category = document.getElementById('category').value;
        const brand = document.getElementById('brand').value;
        const price = document.getElementById('price').value;

        // If no filters or search terms are provided, use getAllProducts
        if (!searchInput && !brand && !category && !price) {
            const response = await fetch('../api/api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    type: 'getAllProducts'
                })
            });

            const data = await response.json();
            if (data.status === 'success' && data.products) {
                displayProducts(data.products);
            } else {
                displayProducts([]);
            }
            return;
        }

        // Otherwise, use the search endpoint with filters
        const searchParams = {
            type: 'search',
            search: {},
            fuzzy: 'true'
        };

        if (searchInput) {
            searchParams.search.ProductName = searchInput;
        }
        if (brand) {
            searchParams.search.Brand = brand;
        }
        if (category) {
            searchParams.search.CategoryName = category;
        }
        if (sortBy) {
            if (sortBy === 'name-asc') {
                searchParams.sort = 'ProductName';
                searchParams.order = 'ASC';
            } else if (sortBy === 'name-desc') {
                searchParams.sort = 'ProductName';
                searchParams.order = 'DESC';
            } else if (sortBy === 'price-asc') {
                searchParams.sort = 'Price';
                searchParams.order = 'ASC';
            } else if (sortBy === 'price-desc') {
                searchParams.sort = 'Price';
                searchParams.order = 'DESC';
            }
            // Note: 'newest' sort is not supported by the search endpoint
        }
        if (price) {
            searchParams.limit = 100; // Fetch more to filter
        }

        const response = await fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(searchParams)
        });

        const data = await response.json();

        if (data.status === 'success' && data.data) {
            let products = data.data;
            // Client-side price filtering
            if (price) {
                products = products.filter(product => {
                    const priceValue = parseFloat(product.Price);
                    if (price === 'under-1000') return priceValue < 1000;
                    if (price === '1000-2500') return priceValue >= 1000 && priceValue <= 2500;
                    if (price === '2500-10000') return priceValue >= 2500 && priceValue <= 10000;
                    if (price === 'over-10000') return priceValue > 10000;
                    return true;
                });
            }
            displayProducts(products);
        } else {
            displayProducts([]);
        }
    } catch (error) {
        console.error('Error fetching products:', error);
        displayProducts([]);
    }
}

function displayProducts(products) {
    const productsSection = document.querySelector('.products-section');
    productsSection.innerHTML = '';

    if (products.length === 0) {
        productsSection.innerHTML = '<p>No products found.</p>';
        return;
    }

    products.forEach(product => {
        const productCard = createProductCard(product);
        productsSection.appendChild(productCard);
    });
}

function createProductCard(product) {
    const productCard = document.createElement('div');
    productCard.className = 'product-card';
    productCard.setAttribute('data-product-id', product.ProductID);

    productCard.innerHTML = `
        <a href="view.php?id=${product.ProductID}">
            <div class="product-image" style="background-image: url('${product.IMG_Reference}')"></div>
        </a>
        <div class="product-details">
            <div class="product-brand">${product.Brand}</div>
            <div class="product-name">${product.ProductName}</div>
            <h4 class="offers-heading">Current offers</h4>
            <div class="store-offers-container" id="offers-${product.ProductID}">
                <div class="loading-offers">Loading offers...</div>
            </div>
            <div class="product-actions">
                <div class="heart-icon">♡</div>
                <button class="view-offers-btn" onclick="navigateToProduct(${product.ProductID})">View Offers</button>
            </div>
        </div>
    `;

    loadProductOffers(product.ProductID);

    return productCard;
}

async function loadProductOffers(productId) {
    const offersContainer = document.getElementById(`offers-${productId}`);
    
    try {
        const response = await fetch('../api/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'compare',
                apikey: localStorage.getItem("apiKey"),
                ProductID: productId
            })
        });

        const data = await response.json();
        
        if (data.status === 'success' && data.data && data.data.length > 0) {
            displayProductOffers(productId, data.data);
        } else {
            offersContainer.innerHTML = `
                <div class="store-offer">
                    <span class="store-name">No offers</span>
                    <span class="product-price">available</span>
                </div>
            `;
        }
    } catch (error) {
        offersContainer.innerHTML = `
            <div class="store-offer">
                <span class="store-name">Error loading</span>
                <span class="product-price">offers</span>
            </div>
        `;
    }
}

function displayProductOffers(productId, offers) {
    const offersContainer = document.getElementById(`offers-${productId}`);
    offersContainer.innerHTML = '';

    offers.forEach(offer => {
        const offerDiv = document.createElement('div');
        offerDiv.className = 'store-offer';
        offerDiv.innerHTML = `
            <span class="store-name">${offer.RetailerName}</span>
            <span class="product-price">R${parseFloat(offer.Price).toLocaleString('en-ZA', { minimumFractionDigits: 0 })}</span>
        `;
        offersContainer.appendChild(offerDiv);
    });
}

function navigateToProduct(productId) {
    sessionStorage.setItem('currentProductId', productId);
    window.location.href = `view.php?id=${productId}`;
}