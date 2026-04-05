<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Product added to cart!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Our Collection</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <!-- <link rel="stylesheet" href="search_v19.css">  -->
  </head>
<body>

<?php include 'user_header.php'; ?>

<section class="search_hero_v19">
    <div class="hero_content_v19">
        <h1>Find Your Next Favorite Book</h1>
        <p>Search through our extensive library of titles and authors.</p>
        <form action="" method="post" class="search_form_v19">
            <div class="input_group_v19">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Enter book title, author, or keywords..." required>
                <button type="submit" name="submit" class="search_btn_v19">Search</button>
            </div>
        </form>
    </div>
</section>

<section class="results_section_v19">
    <div class="container_v19">
        <?php
        if (isset($_POST['submit'])) {
            $search_item = mysqli_real_escape_string($conn, $_POST['search']);
            echo '<h2 class="results_title_v19">Results for: "<span>' . $search_item . '</span>"</h2>';
            
            echo '<div class="product_grid_v19">';
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');
            
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
                    <form action="" method="post" class="book_card_v19">
                        <div class="image_box_v19">
                            <img src="./uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                            <div class="price_badge_v19">Rs. <?php echo $fetch_products['price']; ?></div>
                        </div>
                        
                        <div class="card_content_v19">
                            <h3 class="book_title_v19"><?php echo $fetch_products['name']; ?></h3>
                            
                            <div class="purchase_controls_v19">
                                <div class="qty_input_v19">
                                    <label>Qty:</label>
                                    <input type="number" name="product_quantity" min="1" value="1">
                                </div>
                                
                                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name'] ?>">
                                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                                
                                <button type="submit" name="add_to_cart" class="add_cart_btn_v19">
                                    <i class="fas fa-shopping-cart"></i> Add
                                </button>
                            </div>
                        </div>
                    </form>
        <?php
                }
            } else {
                echo '</div><div class="empty_state_v19">
                        <i class="fas fa-book-open"></i>
                        <p>We couldn\'t find any books matching that name.</p>
                        <a href="search_page.php" class="reset_btn_v19">Clear Search</a>
                      </div>';
            }
            echo '</div>'; // End Grid
        } else {
            echo '<div class="empty_state_v19">
                    <i class="fas fa-search-plus"></i>
                    <p>Enter a keyword above to start exploring!</p>
                  </div>';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>