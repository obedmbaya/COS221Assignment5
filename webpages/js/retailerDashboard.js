document.addEventListener('DOMContentLoaded', function() {
    var retailname = document.querySelector(".user-name");
    var retailemail = document.querySelector(".user-email");

    if (retailname) {
        retailname.textContent = localStorage.getItem("RetailerName") || "";
    }

    if (retailemail) {
        retailemail.textContent = localStorage.getItem("email") || "";
    }

    const retailerCtx = document.getElementById('retailerRatingsChart');
    if (retailerCtx) {
        let retailerChart = new Chart(retailerCtx, {
            type: 'bar',
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    label: 'Number of Reviews',
                    data: [0,0,0,0,0],
                    backgroundColor: '#4c4faf',
                    borderColor: '#3f3e8e',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Reviews'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Rating'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Your Products Rating Distribution'
                    }
                }
            }
        });
        getRatings().then(ratingsData => {
            if (ratingsData) {
                retailerChart.data.datasets[0].data = ratingsData.ratingArr;
                retailerChart.update();
            }
            const numRatings = ratingsData.ratingArr.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
            const elements = document.getElementsByClassName('stat-box');
            if (elements.length >= 2) {
                const thirdItem = elements[1];
                const statNumberDiv = thirdItem.querySelector('.stat-number');
                if (statNumberDiv) {
                    statNumberDiv.textContent = numRatings;
                } else {
                    console.log("No 'stat-number' div found inside the 2nd item.");
                }
            } else {
                console.log("There are < 2 elements with 'stat-box'.");
            }
            if (elements.length >= 3) {
                const thirdItem = elements[2];
                const statNumberDiv = thirdItem.querySelector('.stat-number');
                if (statNumberDiv) {
                    statNumberDiv.textContent = ratingsData.average.toFixed(1);
                } else {
                    console.log("No 'stat-number' div found inside the 3rd item.");
                }
            } else {
                console.log("There are < 3 elements with 'stat-box'.");
            }
        }).catch(error => {
            console.error('Error initializing chart with ratings:', error);
        });

        loadOverview();
    }

    loadCategories();
});

function getRatings() {
    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to load ratings.');
        return Promise.reject('No API key');
    }

    return fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            type: 'GetRetailerRatings',
            ApiKey: apiKey
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            return populateRatingsArr(data.data);
        } else {
            alert('Failed to load reviews: ' + (data.data || 'Unknown error'));
            return { ratingArr: [0, 0, 0, 0, 0], average: 0 };
        }
    })
    .catch(error => {
        console.error('Error loading reviews:', error);
        alert('An error occurred while loading reviews.');
        return { ratingArr: [0, 0, 0, 0, 0], average: 0 };
    });
}

function populateRatingsArr(data) {
    let n = 0;
    let total = 0;
    let ratings = [0, 0, 0, 0, 0];
    data.forEach(element => {
        const index = element.Rating - 1;
        const num = element.number;
        ratings[index] = num;
        n += num;
        total += num * (index + 1);
    });
    let avg = n > 0 ? total / n : 0;
    let output = {
        ratingArr: ratings,
        average: avg
    };
    return output;
}

function loadOverview() {
    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to load products.');
        return;
    }

    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            type: 'GetRetailerProducts',
            ApiKey: apiKey
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateOverview(data.data);
        } else {
            alert('Failed to load products: ' + (data.data || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error loading products:', error);
        alert('An error occurred while loading products.');
    });
}

function populateOverview(data) {
    let numProducts = data.length;
    const elements = document.getElementsByClassName('stat-box');
    if (elements.length >= 1) {
        const firstItem = elements[0];
        const statNumberDiv = firstItem.querySelector('.stat-number');
        if (statNumberDiv) {
            statNumberDiv.textContent = numProducts;
        } else {
            console.log("No 'stat-number' div found inside the 1st item.");
        }
    } else {
        console.log("There are < 1 element with 'stat-box'.");
    }

    const prodList = document.getElementsByClassName('products-list');
    if (prodList[0]) {
        prodList[0].innerHTML = "";
    }

    let i = 0;
    data.forEach(element => {
        if (i < 5 && prodList[0]) {
            prodList[0].appendChild(createProductItem(element.ProductName, element.Rating));
            i++;
        }
    });

    const productTableBody = document.querySelector('.product-table tbody');
    if (productTableBody) {
        productTableBody.innerHTML = "";
        data.forEach(element => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${element.ProductID || 'N/A'}</td>
                <td>${element.ProductName}</td>
                <td>${element.CategoryName}</td>
                <td>${element.Brand}</td>
                <td>R${parseFloat(element.Price).toFixed(2)}</td>
                <td>
                    <button class="remove-btn" onclick="deleteProduct(${element.ProductID})">Remove</button>
                </td>
            `;
            productTableBody.appendChild(row);
        });
    }
}

function createProductItem(productName, rating) {
    const productItem = document.createElement('div');
    productItem.className = 'product-item';

    const nameSpan = document.createElement('span');
    nameSpan.className = 'product-name';
    nameSpan.textContent = productName;

    const ratingDiv = document.createElement('div');
    ratingDiv.className = 'product-rating';

    const starCount = 5;
    const filledStars = Math.round(rating);
    const stars = '★'.repeat(filledStars) + '☆'.repeat(starCount - filledStars);

    const starsSpan = document.createElement('span');
    starsSpan.className = 'stars';
    starsSpan.textContent = stars;

    const ratingSpan = document.createElement('span');
    ratingSpan.className = 'rating-value';
    ratingSpan.textContent = rating;

    const viewButton = document.createElement('button');
    viewButton.className = 'view-btn';
    viewButton.textContent = 'View';
    viewButton.onclick = () => window.location.href = 'view.php';

    ratingDiv.appendChild(starsSpan);
    ratingDiv.appendChild(ratingSpan);
    productItem.appendChild(nameSpan);
    productItem.appendChild(ratingDiv);
    productItem.appendChild(viewButton);

    return productItem;
}

function deleteProduct(productId) {
    const apiKey = localStorage.getItem('apiKey');
    const retailerEmail = localStorage.getItem('email');

    if (!apiKey || !retailerEmail) {
        alert('Please log in to delete products.');
        return;
    }

    if (!confirm('Are you sure you want to delete this product?')) {
        return;
    }

    const payload = {
        type: 'deleteProduct',
        ApiKey: apiKey,
        ProductID: parseInt(productId),
        RetailerEmail: retailerEmail
    };

    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Product deleted successfully!');
            loadOverview();
        } else {
            alert('Failed to delete product: ' + (data.data || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error deleting product:', error);
        alert('An error occurred while deleting the product.');
    });
}

function addProduct() {
    const apiKey = localStorage.getItem('apiKey');
    const retailerEmail = localStorage.getItem('email');

    if (!apiKey || !retailerEmail) {
        alert('Please log in to add products.');
        return;
    }

    const name = document.getElementById('productName')?.value;
    const categoryId = document.getElementById('productCategory')?.value;
    const description = document.getElementById('productDescription')?.value;
    const price = document.getElementById('productPrice')?.value;
    const brand = document.getElementById('productBrand')?.value;
    const image = document.getElementById('productImageUrl')?.value;

    if (!name || !categoryId || !description || !price || !brand) {
        alert('Please fill in all required fields');
        return;
    }

    const payload = {
        type: 'addProduct',
        ApiKey: apiKey,
        ProductName: name,
        CategoryID: parseInt(categoryId),
        Brand: brand,
        IMG_Reference: image,
        Description: description,
        RetailerEmail: retailerEmail,
        Price: parseFloat(price)
    };

    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Product added successfully!');
            document.getElementById('addProductForm')?.reset();
            loadOverview();
        } else {
            alert('Failed to add product: ' + (data.data || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error adding product:', error);
        alert('An error occurred while adding the product.');
    });
}

function loadCategories() {
    fetch('../api/api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            type: 'getAllCategories'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            populateCategoryDropdowns(data.data);
        } else {
            console.error('Failed to load categories:', data.data || 'Unknown error');
        }
    })
    .catch(error => {
        console.error('Error loading categories:', error);
    });
}

function populateCategoryDropdowns(categories) {
    const addCategorySelect = document.getElementById('productCategory');

    if (addCategorySelect) {
        addCategorySelect.innerHTML = '<option value="">Select category</option>';
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.CategoryID;
            option.textContent = category.CategoryName;
            addCategorySelect.appendChild(option);
        });
    }
}