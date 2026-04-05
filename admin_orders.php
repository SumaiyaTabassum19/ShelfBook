<?php
include 'config.php';
session_start();

$admin_id=$_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:login.php');
}

if(isset($_POST['update_order'])){

    $order_update_id=$_POST['order_id'];
    $update_payment=$_POST['update_payment'];

    mysqli_query($conn,"UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');

    $message[]='Order Payment status has been updated';
}

if(isset($_GET['delete'])){
    $delete_id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM `orders` WHERE id='$delete_id'") or die('query failed');
    $message[]='1 order has been deleted';
    header("location:admin_orders.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link rel="stylesheet" href="admin.css"> 
</head>
<body class="admin_body">

<?php
// Displaying messages (like "Order status updated")
if(isset($message)){
    foreach($message as $msg){
        echo '
        <div class="message_alert">
            <span>'.$msg.'</span>
            <i class="fa-solid fa-xmark" onclick="this.parentElement.remove();"></i>
        </div>
    ';      
    } 
}
?>

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
            <a href="admin_page.php" class="nav_item"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="admin_products.php" class="nav_item"><i class="fas fa-box-open"></i> Products</a>
            <a href="admin_orders.php" class="nav_item active"><i class="fas fa-clipboard-list"></i> Orders</a>
            <a href="admin_users.php" class="nav_item"><i class="fas fa-users"></i> Users</a>
            <a href="admin_messages.php" class="nav_item message_count"><i class="fas fa-envelope"></i> Messages <span>(0)</span></a> 
        </nav>
        
        <div class="sidebar_footer">
            <a href="logout.php" class="logout_btn_v15"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main_content_v15">
        <h1 class="orders_title_v17"><i class="fas fa-shopping-cart"></i> Customer Orders</h1>
        
        <section class="orders_section_v17">
            <div class="orders_timeline_container_v17">
                <?php
                $select_orders=mysqli_query($conn,"SELECT * FROM `orders` ORDER BY placed_on DESC") or die('query failed');

                if(mysqli_num_rows($select_orders)>0){
                    while($fetch_orders=mysqli_fetch_assoc($select_orders)){
                        // Determine status class for styling
                        $status_class = ($fetch_orders['payment_status'] == 'pending') ? 'status_pending' : 'status_completed';
                ?>
                <div class="order_card_v17 <?php echo $status_class; ?>">
                    <div class="order_header">
                        <p class="order_date"><i class="fas fa-clock"></i> Placed On: <span><?php echo $fetch_orders['placed_on']?></span></p>
                        <p class="order_id_tag">Order ID: #<?php echo $fetch_orders['id']; ?></p>
                    </div>

                    <div class="order_details_grid">
                        <div class="detail_group">
                            <h4>Customer Info</h4>
                            <p><i class="fas fa-user"></i> Name: <span><?php echo $fetch_orders['name']?></span></p>
                            <p><i class="fas fa-phone"></i> Number: <span><?php echo $fetch_orders['number']?></span></p>
                            <p><i class="fas fa-at"></i> Email: <span><?php echo $fetch_orders['email']?></span></p>
                            <p><i class="fas fa-map-marker-alt"></i> Address: <span><?php echo $fetch_orders['address']?></span></p>
                        </div>
                        
                        <div class="detail_group products_group">
                            <h4>Order Summary</h4>
                            <p><i class="fas fa-box"></i> Items: <span><?php echo $fetch_orders['total_products']?></span></p>
                            <p class="price_total"><i class="fas fa-money-bill-wave"></i> Total: <span>Rs. <?php echo $fetch_orders['total_price']?>/-</span></p>
                            <p><i class="fas fa-credit-card"></i> Method: <span><?php echo $fetch_orders['method']?></span></p>
                            <p class="user_id_info"><i class="fas fa-id-badge"></i> User ID: <span><?php echo $fetch_orders['user_id']?></span></p>
                        </div>
                    </div>
                    
                    <form action="" method="post" class="order_actions">
                        <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                        
                        <div class="status_update_group">
                            <select name="update_payment" class="status_select">
                                <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                                <option value="pending">pending</option>
                                <option value="completed">completed</option>
                            </select>
                            <input type="submit" value="Update" name="update_order" class="update_btn_v17">
                        </div>

                        <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');" class="delete_btn_v17"><i class="fas fa-trash-alt"></i> Delete</a>
                    </form>
                </div>
                <?php
                    }
                }else{
                    echo '<p class="empty_v17">No orders placed yet!</p>';
                }
                ?>
            </div>
        </section>
    </main>
</div>

<script src="admin_js.js"></script>
<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

</body>
</html>