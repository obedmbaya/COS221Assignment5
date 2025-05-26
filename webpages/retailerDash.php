<?php
    $additional_css = '<link rel="stylesheet" href="css/retailerDash.css">'; 
    $page_title = "CompareIt | Admin Dashboard";
    include_once 'header.php';
?>

<!-- Main Content -->
<div class="admin-content-wrapper">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <h1>Retailer Dashboard</h1>
            <p>Manage your products and account details</p>
        </div>
        
        <div class="admin-details">
            <div class="detail-card">
                <h3>Business Details</h3>
                <div class="detail-item">
                    <span class="detail-label">Company:</span>
                    <span class="detail-value">Acme Distributors</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">New York, USA</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact:</span>
                    <span class="detail-value">Company Manager</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">companyName@email.com</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">0827891970</span>
                </div>
            </div>
        </div>
        
        <div class="dashboard-actions">
            <a href="retailerProducts.php" class="btn btn-primary">Manage Products</a>            <a href="#" class="logout-link">Logout</a>
        </div>
    </div>
</div>

</body>
</html>