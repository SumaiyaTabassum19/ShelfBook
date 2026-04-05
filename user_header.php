<?php
// PHP logic for message display
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
<!-- Note:Style.css -->

<header class="main_app_header">
    <div class="header_top_row">
        <div class="header_content_wrapper">
            
            <div class="brand_identity">
                <img src="book_logo_1.png" alt="" class="brand_logo">

                <a href="home.php" class="brand_name">Book<span>Shelf</span></a>
            </div>


            <nav class="primary_navbar">
                <a href="home.php" class="nav_link">Home</a>
                <a href="about.php" class="nav_link">About</a>
                <a href="shop.php" class="nav_link">Shop</a>
                <a href="orders.php" class="nav_link">Orders</a>
                <a href="contact.php" class="nav_link">Contact</a>
            </nav>

            <div class="user_action_group">
                
                <div class="auth_links">
                    <a href="login.php" class="auth_button login_btn">Login</a>
                    <a href="register.php" class="auth_button register_btn">Register</a>
                </div>

                <div class="icon_group">
                    <a class="fas fa-search icon_btn" href="search_page.php" aria-label="Search"></a>

                    <div class="fas fa-user icon_btn" id="user_btn" aria-label="User Account"></div>
                    
                    <?php
                    if (isset($conn) && isset($user_id)) {
                        $select_cart_number=mysqli_query($conn,"SELECT * FROM `cart` where user_id='$user_id'") or die('query failed');
                        $cart_row_number=mysqli_num_rows($select_cart_number);
                    } else {
                        $cart_row_number = 0;
                    }
                    ?>
                    
                    <a href="cart.php" class="icon_btn cart_icon_link" aria-label="Shopping Cart">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart_quantity_badge">(<?php echo $cart_row_number?>)</span>
                    </a>

                    <div class="fas fa-bars icon_btn" id="user_menu_btn"></div>
                </div>

            </div>
            
            <div class="header_acc_box user_dropdown_panel">
                <p>Username : <span class="user_data"><?php echo $_SESSION['user_name'];?></span></p>
                <p>Email : <span class="user_data"><?php echo $_SESSION['user_email'];?></span></p>
                <a href="logout.php" class="logout_btn">Logout</a>
            </div>

        </div>

    </div>

</header>