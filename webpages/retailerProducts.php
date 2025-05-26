<?php
    $additional_css = '<link rel="stylesheet" href="css/adminProducts.css">'; 
    $page_title = "CompareIt | Admin Dashboard";
    include_once 'header.php';
?>
    <div class="container">
        <div class="header">
        <a href="retailerDash.php" class="back-btn">← Back to Dashboard</a>
            <h1>Your Products</h1>
            <a href="addProductForm.php"><button class="btn btn-primary" id="addProductBtn">+ Add Product</button></a>
            
        </div>
        
        
        <div class="product-list" id="productList">
            <!-- Products loaded here -->
        </div>
    </div>
    
    <div class="modal" id="addProductModal">
        <div class="modal-content">
            <h2>Add New Product</h2>
            <form id="productForm">
                <!-- Form fields here -->
                <div style="margin-top: 20px;">
                    <button type="button" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        //integrate this FIN :)
        
        const sampleProducts = [
            {
                id: 1,
                title: "Product 1",
                description: "You need this one",
                price: 69.69,
                category: "Electronics",
                department: "Gadgets",
                image: "https://via.placeholder.com/80"
            },
            {
                id: 2,
                title: "Product 2",
                description: "You might need this one",
                price: 420.420,
                category: "Electronics",
                department: "Gadgets",
                image: "https://via.placeholder.com/80"
            }
        ];
        
        function renderProducts() {
            const productList = document.getElementById('productList');
            productList.innerHTML = '';
            //DOM for you Mr Finn :)
            sampleProducts.forEach(product => {
                const productItem = document.createElement('div');
                productItem.className = 'product-item';
                productItem.innerHTML = `
                    <img src="${product.image}" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">${product.title}</h3>
                        <p class="product-description">${product.description}</p>
                        <div class="product-meta">
                            <span class="product-price">$${product.price.toFixed(2)}</span>
                            <span>Category: ${product.category}</span>
                            <span>Department: ${product.department}</span>
                        </div>
                    </div>
                    <div class="product-actions">
                        <button class="btn" onclick="editProduct(${product.id})">Edit</button>
                        <button class="btn" onclick="deleteProduct(${product.id})">Delete</button>
                    </div>
                `;
                productList.appendChild(productItem);
            });
        }
        
        
        
        // Initial render
        renderProducts();
    </script>
</body>
</html>