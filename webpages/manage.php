<?php
    $additional_css = '<link rel="stylesheet" href="css/styles.css">';
    // $additional_js = '<script src="Link to js file goes here"></script>'; <!-- "js/header.js" for example  -->
    $page_title = "CompareIt | Management";
    include_once 'header.php';
?>
    <div class="manage-container">
        <!-- Select retailer section -->
        <div class="box">
            <h2>Select Retailer</h2>
            <form id="manageProductsForm">
                <label for="retailerDropdown">Choose a retailer to manage:</label>
                <select id="retailerDropdown">
                    <option value="">Select Retailer</option>
                    <option value="1">Takealot</option>
                    <option value="2">Incredible Connection</option>
                    <option value="3">HiFiCorp</option>
                    <option value="4">Game</option>
                    <option value="5">Evetech</option>
                </select>
            </form>
        </div>
        
        <!-- Current products section -->
        <div class="box">
            <h2>Current Products</h2>
            <div class="card-header">
                <h3>Takealot</h3>
            </div>
            
            <div class="product-list">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Current Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Samsung 4K Smart TV</td>
                            <td>Televisions</td>
                            <td>
                                <input type="text" class="price-input" value="R 12,999.00">
                            </td>
                            <td class="actions">
                                <button type="button" class="success">Update Price</button>
                                <button type="button" class="remove">Remove</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Sony Bluetooth Speaker</td>
                            <td>Audio</td>
                            <td>
                                <input type="text" class="price-input" value="R 1,499.00">
                            </td>
                            <td class="actions">
                                <button type="button" class="success">Update Price</button>
                                <button type="button" class="remove">Remove</button>
                            </td>
                        </tr>
                        <tr>
                            <td>iPhone 14 Pro</td>
                            <td>Smartphones</td>
                            <td>
                                <input type="text" class="price-input" value="R 21,499.00">
                            </td>
                            <td class="actions">
                                <button type="button" class="success">Update Price</button>
                                <button type="button" class="remove">Remove</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Dell XPS 15 Laptop</td>
                            <td>Computers</td>
                            <td>
                                <input type="text" class="price-input" value="R 24,999.00">
                            </td>
                            <td class="actions">
                                <button type="button" class="success">Update Price</button>
                                <button type="button" class="remove">Remove</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Logitech Gaming Mouse</td>
                            <td>Accessories</td>
                            <td>
                                <input type="text" class="price-input" value="R 899.00">
                            </td>
                            <td class="actions">
                                <button type="button" class="success">Update Price</button>
                                <button type="button" class="remove">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- New products section -->
        <div class="box">
            <h2>Add New Product</h2>
            <form id="addProductForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="productDropdown">Select Product:</label>
                        <select id="productDropdown">
                            <option value="">Select Product</option>
                            <option value="1">Canon EOS DSLR Camera</option>
                            <option value="2">JBL Wireless Headphones</option>
                            <option value="3">Samsung Galaxy Tab S8</option>
                            <option value="4">Asus Gaming Monitor</option>
                            <option value="5">Dyson Air Purifier</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productPrice">Set Price (in Rands):</label>
                        <input type="text" id="productPrice" placeholder="Enter price in Rands">
                    </div>
                </div>
                <button type="button" class="success">Add Product to Retailer</button>
            </form>
        </div>
    </div>   
</body>

</html>
