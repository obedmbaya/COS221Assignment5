document.addEventListener('DOMContentLoaded', () => {
    const productId = getCurrentProductId();
    if (!productId) {
        console.error("No product ID found.");
        return;
    }

    fetchProductData(productId);
    fetchComparisonData(productId);

    // Attach event listener to the "More Reviews" button
    const moreReviewsBtn = document.querySelector('.see-more-btn');
    if (moreReviewsBtn) {
        moreReviewsBtn.addEventListener('click', () => navigateToProduct(productId));
    }

    // Attach event listener to the "More Offers" button
    const moreOffersBtn = document.querySelector('.more-offers-btn');
    if (moreOffersBtn) {
        moreOffersBtn.addEventListener('click', () => navigateToProduct(productId));
    }
});

function getCurrentProductId() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id') || sessionStorage.getItem('currentProductId');
    return productId;
}

function fetchProductData(productId) {
    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            type: "ViewProduct",
            apikey: localStorage.getItem("apiKey"),
            ProductID: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            renderProduct(data.data);
        } else {
            console.error("Error fetching product:", data);
        }
    })
    .catch(error => {
        console.error("Network error:", error);
    });
}

function fetchComparisonData(productId) {
    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            type: "compare",
            apikey: localStorage.getItem("apiKey"),
            ProductID: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            renderRetailers(data.data);
        } else {
            console.error("Error fetching comparison data:", data);
        }
    })
    .catch(error => {
        console.error("Network error:", error);
    });
}

function renderProduct(product) {
    const nameElement = document.querySelector('.product-name');
    if (nameElement) nameElement.textContent = product.ProductName;

    const brandElement = document.querySelector('.product-brand');
    if (brandElement) brandElement.textContent = product.Brand;

    const priceElement = document.querySelector('.view-product-price');
    if (priceElement) priceElement.textContent = `R${product.Price}`;

    const mainImageElement = document.querySelector('.product-main-image');
    if (mainImageElement) {
        mainImageElement.style.backgroundImage = `url('${product.IMG_Reference}')`;
    }

    const descriptionElement = document.querySelector('.description-content');
    if (descriptionElement) descriptionElement.textContent = product.Description;
}

function renderRetailers(retailers) {
    const storeBox = document.querySelector('.store-options-box');
    if (!storeBox) return;

    storeBox.innerHTML = '';

    retailers.forEach(retailer => {
        const storeOption = document.createElement('div');
        storeOption.className = 'store-option';

        const storeName = document.createElement('div');
        storeName.className = 'store-name';
        storeName.textContent = retailer.RetailerName;

        const storePrice = document.createElement('div');
        storePrice.className = 'store-price';
        storePrice.textContent = `R${retailer.Price}`;

        const link = document.createElement('a');
        link.href = retailer.URL;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';

        storeOption.appendChild(storeName);
        storeOption.appendChild(storePrice);
        link.appendChild(storeOption);
        storeBox.appendChild(link);
    });
}

function navigateToProduct(productId) {
    sessionStorage.setItem('currentProductId', productId);
    window.location.href = `review.php?id=${productId}`;
}