<!-- sidebarAdmin.php -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="retailerDash.php" <?php echo basename($_SERVER['PHP_SELF']) == 'retailerDash.php' ? 'class="active"' : ''; ?>>
                <i>👤</i>
                <span>Profile</span>
            </a>
        </li>
        <li>
            <a href="retailerProducts.php" <?php echo basename($_SERVER['PHP_SELF']) == 'retailerProducts.php' ? 'class="active"' : ''; ?>>
                <i>📦</i>
                <span>Products</span>
            </a>
        </li>
        <li>
            <a href="logout.php">
                <i>🚪</i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>