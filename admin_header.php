<?php

include 'config.php';
//session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

// --- PHP DATA FETCHING FOR DASHBOARD CARDS (Reusing V14 Logic) ---

// 1. Total Pending Orders
$select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
$total_pending = 0;
while ($fetch_pending = mysqli_fetch_assoc($select_pending)) {
    $total_pending += $fetch_pending['total_price'];
}

// 2. Total Completed Orders
$select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
$total_completed = 0;
while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
    $total_completed += $fetch_completed['total_price'];
}

// 3. Order Count
$select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
$num_orders = mysqli_num_rows($select_orders);

// 4. Products Count
$select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
$num_products = mysqli_num_rows($select_products);

// 5. Users Count
$select_users = mysqli_query($conn, "SELECT * FROM `register` WHERE user_type = 'user'") or die('query failed');
$num_users = mysqli_num_rows($select_users);

// 6. Admin Count
$select_admins = mysqli_query($conn, "SELECT * FROM `register` WHERE user_type = 'admin'") or die('query failed');
$num_admins = mysqli_num_rows($select_admins);

// 7. Messages Count
$select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
$num_messages = mysqli_num_rows($select_messages);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin_body_v15">

<div class="admin_wrapper_v15">
    
    <aside class="sidebar_v15">
        <div class="sidebar_brand">
            <i class="fas fa-book-reader"></i> BookShelf Admin
        </div>
        <div class="user_profile">
            <i class="fas fa-user-circle"></i>
            <p><?php echo $_SESSION['admin_name']; ?> <span>(Admin)</span></p>
        </div>
        
        <nav class="sidebar_nav">
            <a href="admin_page.php" class="nav_item active"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="admin_products.php" class="nav_item"><i class="fas fa-box-open"></i> Products</a>
            <a href="admin_orders.php" class="nav_item"><i class="fas fa-clipboard-list"></i> Orders</a>
            <a href="admin_users.php" class="nav_item"><i class="fas fa-users"></i> Users</a>
            <a href="admin_messages.php" class="nav_item message_count"><i class="fas fa-envelope"></i> Messages  <span>(<?php echo $num_messages; ?>)</span></a>
        </nav>
        
        <div class="sidebar_footer">
            <a href="logout.php" class="logout_btn_v15"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main_content_v15">
        <h1 class="dashboard_title_v15">Dashboard Overview</h1>
        
        <section class="data_summary_v15">
            
            <div class="data_card_v15 primary_border">
                <i class="fas fa-hourglass-half card_icon"></i>
                <div class="card_text">
                    <p class="card_value">৳ <?php echo number_format($total_pending, 2); ?>/-</p>
                    <p class="card_label">Pending Payments</p>
                </div>
            </div>

            <div class="data_card_v15 accent_border">
                <i class="fas fa-money-check-alt card_icon"></i>
                <div class="card_text">
                    <p class="card_value">৳ <?php echo number_format($total_completed, 2); ?>/-</p>
                    <p class="card_label">Total Revenue</p>
                </div>
            </div>

            <div class="data_card_v15 neutral_border">
                <i class="fas fa-boxes card_icon"></i>
                <div class="card_text">
                    <p class="card_value"><?php echo $num_products; ?></p>
                    <p class="card_label">Total Products</p>
                </div>
            </div>

            <div class="data_card_v15 dark_border">
                <i class="fas fa-receipt card_icon"></i>
                <div class="card_text">
                    <p class="card_value"><?php echo $num_orders; ?></p>
                    <p class="card_label">Total Orders</p>
                </div>
            </div>
            
        </section>

        <section class="segmented_content_v15">
            
            <div class="segment_widget user_widget">
                <h2>User Statistics</h2>
                <div class="user_stats">
                    <div class="stat_box user_stat">
                        <p class="stat_value"><?php echo $num_users; ?></p>
                        <p class="stat_label">Registered Users</p>
                    </div>
                    <div class="stat_box admin_stat">
                        <p class="stat_value"><?php echo $num_admins; ?></p>
                        <p class="stat_label">Admin Accounts</p>
                    </div>
                </div>
                <a href="admin_users.php" class="widget_link">Manage User Accounts <i class="fas fa-chevron-right"></i></a>
            </div>

            <div class="segment_widget quick_links_widget">
                <h2>Quick Actions</h2>
                <div class="quick_link_grid">
                    <a href="admin_products.php?add_product=true" class="quick_link_btn"><i class="fas fa-plus"></i> Add Products</a>
                    <a href="admin_orders.php?status=pending" class="quick_link_btn"><i class="fas fa-truck-loading"></i> Process Orders</a>
                    <a href="admin_messages.php" class="quick_link_btn"><i class="fas fa-comments"></i> Respond to Queries</a>
                </div>
            </div>
            
        </section>
        
    </main>
</div>

<script src="admin_js.js"></script>
</body>
</html>