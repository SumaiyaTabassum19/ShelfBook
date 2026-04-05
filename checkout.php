<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = array();

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'Your cart is empty';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order already placed!';
        } else {
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
            $message[] = 'Order placed successfully!';
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            // Redirect to orders page or home after success
            header('location:orders.php'); 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
<body>
  
<?php include 'user_header.php'; ?>

<div class="checkout_container_v21">
    <h1 class="main_title_v21">Secure Checkout</h1>

    <div class="checkout_wrapper_v21">
        
        <div class="checkout_form_section_v21">
            <form action="" method="post" class="actual_form_v21">
                <div class="form_header_v21">
                    <i class="fas fa-truck"></i>
                    <h3>Shipping & Payment Details</h3>
                </div>

                <div class="input_grid_v21">
                    <div class="input_box_v21">
                        <span>Full Name</span>
                        <input type="text" name="name" required placeholder="Enter Your Name">
                    </div>
                    <div class="input_box_v21">
                        <span>Phone Number</span>
                        <input type="tel" name="number" required placeholder="Enter Your Number?">
                    </div>
                    <div class="input_box_v21">
                        <span>Email Address</span>
                        <input type="email" name="email" required placeholder="Enter Your Email">
                    </div>
                    <div class="input_box_v21">
                        <span>Payment Method</span>
                        <select name="method">
                            <option value="cash on delivery">Cash on Delivery</option>
                            <option value="gpay">Google Pay (GPay)</option>
                        </select>
                    </div>
                    <div class="input_box_v21 full_width_v21">
                        <span>Delivery Address</span>
                        <textarea name="address" required placeholder="Flat No., Street, City, Pincode" rows="4"></textarea>
                    </div>
                </div>

                <button type="submit" name="order_btn" class="place_order_btn_v21">
                    Complete Purchase <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <div class="order_summary_section_v21">
            <div class="summary_sticky_v21">
                <h3>Order Summary</h3>
                <div class="summary_item_list_v21">
                    <?php
                    $grand_total = 0;
                    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');
                    if (mysqli_num_rows($select_cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                            $grand_total += $total_price;
                    ?>
                    <div class="mini_product_card_v21">
                        <img src="./uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
                        <div class="mini_details_v21">
                            <h4><?php echo $fetch_cart['name']; ?></h4>
                            <p>Qty: <?php echo $fetch_cart['quantity']; ?> × ৳. <?php echo $fetch_cart['price']; ?></p>
                        </div>
                        <div class="mini_subtotal_v21">
                           ৳. <?php echo number_format($total_price, 2); ?>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                        echo '<p class="empty_v21">Your bag is empty!</p>';
                    }
                    ?>
                </div>

                <div class="total_breakdown_v21">
                    <div class="breakdown_line_v21">
                        <span>Subtotal</span>
                        <span>৳. <?php echo number_format($grand_total, 2); ?></span>
                    </div>
                    <div class="breakdown_line_v21">
                        <span>Shipping</span>
                        <span class="free_text_v21">FREE</span>
                    </div>
                    <hr>
                    <div class="grand_total_v21">
                        <span>Grand Total</span>
                        <span>৳. <?php echo number_format($grand_total, 2); ?></span>
                    </div>
                </div>

                <div class="trust_badges_v21">
                    <p><i class="fas fa-shield-alt"></i> 100% Authentic Books</p>
                    <p><i class="fas fa-undo"></i> 7-Day Easy Return</p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>