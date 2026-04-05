<?php
include 'config.php';
session_start();

$user_id=$_SESSION['user_id'];

if(!isset($user_id)){
  header('location:login.php');
}

// Check for and display messages
if(isset($message)){
    foreach($message as $msg){
        echo '<div class="message_alert"><span>'.$msg.'</span> <i class="fa-solid fa-xmark" onclick="this.parentElement.remove();"></i></div>';
    }
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
    $pro_name = mysqli_real_escape_string($conn, $pro_name);
    $pro_image = mysqli_real_escape_string($conn, $pro_image);
    
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
  <title>BookShelf Shop</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="style.css">
</head>
<body>
  
<?php
include 'user_header.php';
?>

<section class="catalog_hero_v9">
    <div class="header_box">
        <h1>The Book Catalog</h1>
        <p>A curated collection of the finest stories and knowledge.</p>
    </div>
</section>

<section class="product_catalog_listing_v9">
    <h2 class="catalog_heading">Browse Our Library</h2>
    <div class="product_catalog_grid">
      <?php
      $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');

      if (mysqli_num_rows($select_products) > 0) {
        while ($fetch_products = mysqli_fetch_assoc($select_products)) {

      ?>
          <form action="" method="post" class="catalog_item">
            <div class="item_inner_box">
                <div class="book_cover_area">
                    <img src="./uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>" class="catalog_book_image">
                    
                    <div class="hover_overlay">
                        <div class="overlay_content">
                            <input type="number" name="product_quantity" min="1" value="1" class="qty_input_overlay">
                            <input type="submit" value="Add to Cart" name="add_to_cart" class="add_btn_overlay">
                            <a href="#" class="view_btn_overlay"><i class="fas fa-eye"></i> View</a>
                        </div>
                    </div>
                </div>
                
                <div class="item_details">
                    <h3 class="item_title"><?php echo $fetch_products['name']; ?></h3>
                    <p class="item_price">৳.<?php echo $fetch_products['price']; ?>/-</p>
                </div>
            </div>
            
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name'] ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

          </form>

      <?php
        }
      } else {
        echo '<p class="empty_catalog_message">No Products Added Yet !</p>';
      }
      ?>
    </div>
  </section>

<?php
include 'footer.php';
?>
<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

<script src="script.js"></script>

</body>
</html>