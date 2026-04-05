<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity='$cart_quantity' WHERE id='$cart_id'") or die('query failed');
    $message[] = 'Cart updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id='$delete_id'") or die('query failed');
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Bag</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
<body>
  
<?php include 'user_header.php'; ?>

<section class="cart_container_v20">
    <h1 class="page_title_v20">Review Your Bag</h1>

    <div class="cart_wrapper_v20">
        <div class="cart_items_v20">
            <div class="list_header_v20">
                <span>Product</span>
                <span>Quantity</span>
                <span>Subtotal</span>
            </div>

            <?php
            $grand_total = 0;
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']);
                    $grand_total += $sub_total;
            ?>
            <div class="cart_item_row_v20">
                <div class="product_info_v20">
                    <div class="img_container_v20">
                        <img src="./uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                    </div>
                    <div class="details_v20">
                        <h3><?php echo $fetch_cart['name']; ?></h3>
                        <p class="unit_price_v20">Price: Rs. <?php echo $fetch_cart['price']; ?></p>
                        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="remove_link_v20" onclick="return confirm('Remove this item?');">
                            <i class="fas fa-trash-alt"></i> Remove
                        </a>
                    </div>
                </div>

                <div class="quantity_control_v20">
                    <form action="" method="post">
                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                        <div class="qty_input_group_v20">
                            <input type="number" name="cart_quantity" min="1" value="<?php echo $fetch_cart['quantity']; ?>">
                            <button type="submit" name="update_cart" title="Update Quantity">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="subtotal_v20">
                    <span>Tk. <?php echo number_format($sub_total, 2); ?></span>
                </div>
            </div>
            <?php
                }
            } else {
                echo '
                <div class="empty_cart_v20">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Your shopping bag is currently empty.</p>
                    <a href="shop.php" class="shop_now_btn">Explore Books</a>
                </div>';
            }
            ?>

            <?php if($grand_total > 0): ?>
            <div class="list_footer_v20">
                <a href="cart.php?delete_all" class="clear_cart_btn" onclick="return confirm('Clear entire cart?');">Empty Bag</a>
                <a href="shop.php" class="continue_shop_link">Continue Shopping</a>
            </div>
            <?php endif; ?>
        </div>

        <div class="cart_summary_v20">
            <div class="summary_card_v20">
                <h3>Order Summary</h3>
                <div class="summary_line_v20">
                    <span>Subtotal</span>
                    <span>Tk. <?php echo number_format($grand_total, 2); ?></span>
                </div>
                <div class="summary_line_v20">
                    <span>Shipping</span>
                    <span class="free_shipping_v20">FREE</span>
                </div>
                <hr>
                <div class="summary_total_v20">
                    <span>Total</span>
                    <span>Rs. <?php echo number_format($grand_total, 2); ?></span>
                </div>
                <a href="checkout.php" class="checkout_btn_v20 <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">
                    Proceed to Checkout
                </a>
                <p class="secure_text_v20"><i class="fas fa-lock"></i> Secure Encrypted Payment</p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>