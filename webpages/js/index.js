
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
});

async function loadProducts() {
    try {
        const response = await fetch('api.php', {
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
        }
    } catch (error) {
        console.error('Error fetching products:', error);
    }
}

function displayProducts(products) {
    const productsSection = document.querySelector('.products-section');
    
    productsSection.innerHTML = '';

  
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
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'Compare',
                apikey: 'tre456gfr5678ihgty789o',
                ProductID: parseInt(productId)
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
            <span class="product-price">R${parseFloat(offer.Price).toLocaleString()}</span>
        `;
        offersContainer.appendChild(offerDiv);
    });
}

// Store product ID for view page navigation
function navigateToProduct(productId) {
    sessionStorage.setItem('currentProductId', productId);
    window.location.href = `view.php?id=${productId}`;
}

