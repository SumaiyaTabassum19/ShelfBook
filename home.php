<?php
// ... (Your existing PHP code remains the same)
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {
    $pro_name = $_POST['product_name'];
    $pro_price = $_POST['product_price'];
    $pro_quantity = $_POST['product_quantity'];
    $pro_image = $_POST['product_image'];

    $check = mysqli_query($conn, "SELECT * FROM `cart` where name='$pro_name' and user_id='$user_id'") or die('query failed');

    if (mysqli_num_rows($check) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id,name,price,quantity,image) VALUES ('$user_id','$pro_name','$pro_price','$pro_quantity','$pro_image')") or die('query2 failed');
        $message[] = 'Product added to cart!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page | BookShelf</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="home.css"> 

</head>

<body>

    <?php
    include 'user_header.php';
    ?>

    <section class="hero_banner_v13">
        <div class="banner_content_v13">
            <h1>Welcome to BookShelf: The World of Stories</h1>
            <p>Explore, Discover, and Buy Your Next Favorite Book.</p>
            <a href="#featured_v13" class="cta_primary_v13">Discover Our Books <i class="fas fa-arrow-down"></i></a>
        </div>
    </section>

    <section class="products_section_v13" id="featured_v13">
        <h2 class="section_title_v13">Featured Books</h2>
        <div class="product_grid_v13">
            <?php
            // Limit to 6 books
            $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');

            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
                    <form action="" method="post" class="product_card_v13">
                        <img src="./uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>" class="product_image_v13">
                        
                        <div class="product_info_v13"> 
                            <h3><?php echo $fetch_products['name']; ?></h3>
                            <p class="price_tag_v13">৳.<?php echo $fetch_products['price']; ?>/-</p>
                            
                            <input type="number" name="product_quantity" min="1" value="1" class="qty_input_v13">
                        </div>

                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name'] ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                        <input type="submit" value="Add to Cart" name="add_to_cart" class="add_to_cart_btn_v13"> 
                    </form>

            <?php
                }
            } else {
                echo '<p class="empty_v13">No Featured Products Added Yet !</p>';
            }
            ?>
        </div>
        <div class="full_shop_link">
            <a href="shop.php" class="cta_secondary_v13">View All Products <i class="fas fa-chevron-right"></i></a>
        </div>
    </section>

    <section class="promo_section_v13">
        <div class="promo_content_v13">
            <div class="promo_descript_v13">
                <h2>Discover Our Story</h2>
                <p>At BookShelf, we are passionate about connecting readers with captivating stories, inspiring ideas, and a world of knowledge. Our bookstore is more than just a place to buy books; it's a haven for book enthusiasts, where the love for literature thrives. We believe every book holds a new adventure.</p>
                <button class="cta_secondary_v13" onclick="window.location.href='about.php';">Read More About Us</button> 
            </div>
            <img src="about.jpg" alt="A person reading a book on a couch" class="promo_image_v13">
        </div>
    </section>

    <section class="contact_cta_v13">
        <div class="cta_inner_box_v13">
            <h2>Have Any Queries?</h2>
            <p>Our dedicated team is here to assist you every step of the way. Reach out to us for book inquiries, order support, or just to say hello.</p>
            <a href="contact.php" class="cta_primary_v13">Contact Us Today</a>
        </div>
    </section>
    
    <?php
    include 'footer.php';
    ?>
    <script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

    <script src="script.js"></script>

</body>

</html>