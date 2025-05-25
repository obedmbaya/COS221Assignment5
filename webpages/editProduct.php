<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/editProduct.css">
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Edit Product</h1>
            <button class="btn btn-outline" onclick="window.location.href='retailerProducts.php'">← Back to Products</button>
        </div>
        
        <form id="productForm">
            <!-- Product Image -->
            <div class="form-group">
                <label class="form-label">Product Image</label>
                <input type="file" id="productImage" class="form-control" accept="image/*">
                <div class="current-image">Current image will be replaced if a new one is selected</div>
            </div>
            <!-- Product Name & Category -->
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" placeholder="Enter product name" value="Premium Widget" required>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select class="form-control" required>
                            <option value="">Select category</option>
                            <option selected>Electronics</option>
                            <option>Clothing</option>
                            <option>Home & Garden</option>
                            <option>Sports & Outdoors</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" placeholder="Enter detailed product description" required>High quality widget with advanced features. Perfect for professional use with long-lasting durability.</textarea>
            </div>
            
            <!-- Price & Stock -->
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label class="form-label">Price (ZAR)</label>
                        <input type="number" class="form-control" step="0.01" min="0" placeholder="0.00" value="49.99" required>
                    </div>
                </div>
                <!-- =========If we want to add quantity... but this is a price checker... but we need to allow the admin to tell when they do ==========
                 ===========not want their product to be shown when they are out of stock or have stopped selling the Product =============-->
                <!-- <div class="form-col">
                    <div class="form-group">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" min="0" placeholder="Available quantity" value="125" required>
                    </div>
                </div> -->
            </div>
            
            <!-- Department & SKU -->
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" class="form-control" placeholder="e.g. Men's Fashion, Kitchenware" value="Gadgets" required>
                    </div>
                </div>
                <!-- If you want to add an id for the tables... -->
                <!-- <div class="form-col">
                    <div class="form-group">
                        <label class="form-label">ID</label>
                        <input type="text" class="form-control" placeholder="Product identifier" value="WID-001-PRE">
                    </div>
                </div> -->
            </div>
            
            <!-- Form Footer with Buttons -->
            <div class="form-footer">
                <button type="button" class="btn btn-outline" onclick="window.location.href='retailerProducts.php'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
    </script>
</body>
</html>