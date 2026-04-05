<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | BookShelf</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
<body>
  
<?php include 'user_header.php'; ?>

<section class="orders_section_v22">
    <div class="container_v22">
        <h1 class="page_title_v22"><i class="fas fa-history"></i> Your Order History</h1>
        <p class="subtitle_v22">Track and manage your past purchases</p>

        <div class="orders_list_v22">
            <?php
            $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id='$user_id' ORDER BY id DESC") or die('query failed');

            if (mysqli_num_rows($order_query) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
                    $status_class = ($fetch_orders['payment_status'] == 'pending') ? 'status_pending_v22' : 'status_completed_v22';
            ?>
            
            <div class="order_card_v22">
                <div class="order_header_v22">
                    <div class="header_info_v22">
                        <span class="label_v22">PLACED ON</span>
                        <span class="value_v22"><?php echo $fetch_orders['placed_on']; ?></span>
                    </div>
                    <div class="header_info_v22">
                        <span class="label_v22">TOTAL AMOUNT</span>
                        <span class="value_v22 highlight_v22">৳. <?php echo number_format($fetch_orders['total_price'], 2); ?></span>
                    </div>
                    <div class="status_pill_v22 <?php echo $status_class; ?>">
                        <?php echo strtoupper($fetch_orders['payment_status']); ?>
                    </div>
                </div>

                <div class="order_body_v22">
                    <div class="info_column_v22">
                        <h4><i class="fas fa-user-tag"></i> Delivery Details</h4>
                        <p><strong><i class="fas fa-user"></i></strong> <?php echo $fetch_orders['name']; ?></p>
                        <p><strong><i class="fas fa-phone"></i></strong> <?php echo $fetch_orders['number']; ?></p>
                        <p><strong><i class="fas fa-envelope"></i></strong> <?php echo $fetch_orders['email']; ?></p>
                        <p><strong><i class="fas fa-map-marker-alt"></i></strong> <?php echo $fetch_orders['address']; ?></p>
                    </div>

                    <div class="info_column_v22">
                        <h4><i class="fas fa-box-open"></i> Order Content</h4>
                        <div class="products_list_v22">
                            <?php echo $fetch_orders['total_products']; ?>
                        </div>
                        <div class="payment_method_v22">
                            <i class="fas fa-credit-card"></i> Paid via: <span><?php echo strtoupper($fetch_orders['method']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                }
            } else {
                echo '
                <div class="empty_orders_v22">
                    <img src="https://cdn-icons-png.flaticon.com/512/4555/4555971.png" alt="Empty">
                    <h3>No orders yet!</h3>
                    <p>Looks like you haven\'t started your reading journey yet.</p>
                    <a href="shop.php" class="shop_btn_v22">Browse Collection</a>
                </div>';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>