 // Initialize retailer-specific charts
 document.addEventListener('DOMContentLoaded', function() {

    var retailname = document.querySelector(".user-name");
    var retailemail = document.querySelector(".user-email");

    if (retailname) {
        retailname.textContent = localStorage.getItem("RetailerName") || "";
    }

    if (retailemail) {
        retailemail.textContent = localStorage.getItem("email") || "";
    }

    // document.querySelector(".logout-btn").addEventListener("click", function () {
    //     localStorage.clear();
    //     alert("You have been logged out successfully.");
    //     window.location.href = "index.php";
    // });

    // Retailer ratings chart
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
        //Populates the chart with rating total reviews
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
                    console.log("No 'stat-number' div found inside the 3rd item.");
                }
            } else {
                console.log("There are < 2 elements with 'my-class'.");
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
                console.log("There are < 3 elements with 'my-class'.");
            }
        }).catch(error => {
            console.error('Error initializing chart with ratings:', error);
        });

        loadOverview();

    }
});

//Gets the number of occurences of each rating
function getRatings(){
    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to load users.');
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
            return [0, 0, 0, 0, 0];
        }
    })
    .catch(error => {
        console.error('Error loading reviews:', error);
        alert('An error occurred while loading reviews.');
        return [0, 0, 0, 0, 0];
    });
}

function populateRatingsArr(data){
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
    let avg = total/n;
    let output = {
        ratingArr : ratings,
        average : avg
    }
    return output;
}

//Loads products and stats
function loadOverview(){

    const apiKey = localStorage.getItem('apiKey');
    if (!apiKey) {
        alert('Please log in to load users.');
        return; //Promise.reject('No API key');
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
            return populateOverview(data.data);
        } else {
            alert('Failed to load products: ' + (data.data || 'Unknown error'));
            return [0, 0, 0, 0, 0];
        }
    })
    .catch(error => {
        console.error('Error loading products:', error);
        alert('An error occurred while loading products.');
        return [0, 0, 0, 0, 0];
    });

}

function populateOverview(data){

    let numProducts = data.length

    const elements = document.getElementsByClassName('stat-box');
    if (elements.length >= 1) {
        const thirdItem = elements[0];
        const statNumberDiv = thirdItem.querySelector('.stat-number');
        if (statNumberDiv) {
            statNumberDiv.textContent = numProducts;
        } else {
            console.log("No 'stat-number' div found inside the 3rd item.");
        }
    } else {
        console.log("There are < 1 element with 'my-class'.");
    }

    const prodList = document.getElementsByClassName('products-list');
    prodList[0].innerHTML = "";

    data.forEach(element => {
        prodList[0].appendChild(createProductItem(element.ProductName, 5));
    });

    /*
    <div class="product-item">
                            <span class="product-name">Premium Headphones</span>
                            <div class="product-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-value">4.8</span>
                            </div>
                            <button class="view-btn" onclick="window.location.href='view.php'">View</button>
                        </div>
                         */

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
    ratingSpan.textContent = rating.toFixed(1);

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

// Product management functions
function editProduct(productId) {
    const editForm = document.getElementById('editProductForm');
    const products = {
        'R001': {
            name: 'Premium Headphones',
            category: 'Electronics',
            description: 'High-quality over-ear headphones with noise cancellation',
            price: '1299',
            // department: 'Audio',
            image: 'https://example.com/headphones.jpg'
        },
        'R002': {
            name: 'Wireless Earbuds',
            category: 'Electronics',
            description: 'True wireless earbuds with 24hr battery life',
            price: '899',
            // department: 'Audio',
            image: 'https://example.com/earbuds.jpg'
        },
        'R003': {
            name: 'Bluetooth Speaker',
            category: 'Electronics',
            description: 'Portable waterproof speaker with 20hr playtime',
            price: '1599',
            // department: 'Audio',
            image: 'https://example.com/speaker.jpg'
        }
    };
    
    const product = products[productId];
    if (product) {
        document.getElementById('editProductName').value = product.name;
        document.getElementById('editProductCategory').value = product.category;
        document.getElementById('editProductDescription').value = product.description;
        document.getElementById('editProductPrice').value = product.price;
        document.getElementById('editProductDepartment').value = product.department;
        document.getElementById('editProductImage').value = product.image;
        editForm.style.display = 'block';
        editForm.scrollIntoView({ behavior: 'smooth' });
    }
}

function saveProduct() {
    // Get form values
    const name = document.getElementById('editProductName').value;
    const category = document.getElementById('editProductCategory').value;
    const description = document.getElementById('editProductDescription').value;
    const price = document.getElementById('editProductPrice').value;
    const department = document.getElementById('editProductDepartment').value;
    const image = document.getElementById('editProductImage').value;
    
    // Validate form
    if (!name || !category || !description || !price || !department) {
        alert('Please fill in all required fields');
        return;
    }

    //write code to save product here
    
    alert('Product saved successfully!');
    cancelEdit();
}

function cancelEdit() {
    const editForm = document.getElementById('editProductForm');
    editForm.style.display = 'none';
    
    // Clear form
    document.getElementById('editProductName').value = '';
    document.getElementById('editProductCategory').value = '';
    document.getElementById('editProductDescription').value = '';
    document.getElementById('editProductPrice').value = '';
    document.getElementById('editProductDepartment').value = '';
    document.getElementById('editProductImage').value = '';
}

function addProduct() {
    // Get form values
    const name = document.getElementById('productName').value;
    const category = document.getElementById('productCategory').value;
    const description = document.getElementById('productDescription').value;
    const price = document.getElementById('productPrice').value;
    const department = document.getElementById('productDepartment').value;
    const image = document.getElementById('productImageUrl').value;
    
    // Validate form
    if (!name || !category || !description || !price || !department) {
        alert('Please fill in all required fields');
        return;
    }
    
    alert('Product added successfully!');
    document.getElementById('addProductForm').reset();
}