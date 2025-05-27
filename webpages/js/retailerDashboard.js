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
        new Chart(retailerCtx, {
            type: 'bar',
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    label: 'Number of Reviews',
                    data: [15, 12, 45, 234, 567],
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
    }
});

// load products into dashboard
function loadProducts(){

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
            department: 'Audio',
            image: 'https://example.com/headphones.jpg'
        },
        'R002': {
            name: 'Wireless Earbuds',
            category: 'Electronics',
            description: 'True wireless earbuds with 24hr battery life',
            price: '899',
            department: 'Audio',
            image: 'https://example.com/earbuds.jpg'
        },
        'R003': {
            name: 'Bluetooth Speaker',
            category: 'Electronics',
            description: 'Portable waterproof speaker with 20hr playtime',
            price: '1599',
            department: 'Audio',
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