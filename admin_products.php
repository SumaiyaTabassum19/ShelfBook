<?php
include 'config.php';
session_start();

$admin_id=$_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:login.php');
};

// --- Add Product Logic ---
if(isset($_POST['add_products_btn'])){
    $name=mysqli_real_escape_string($conn, $_POST['name']);
    $price=$_POST['price'];
    $image=$_FILES['image']['name'];
    $image_size=$_FILES['image']['size'];
    $image_tmp_name=$_FILES['image']['tmp_name'];
    $image_folder="uploaded_img/".$image;

    $select_product_name=mysqli_query($conn, "SELECT name FROM `products` WHERE name='$name'") or die('query failed');

    if(mysqli_num_rows($select_product_name)>0){
        $message[]='The given product is already added';
    }else{
        $add_product_query=mysqli_query($conn,"INSERT INTO `products`(name,price,image) VALUES ('$name','$price','$image')") or die('query2 failed');
        if($add_product_query){
            if($image_size>2000000){
                $message[]='Image size is too large (max 2MB)';
            }else{
                move_uploaded_file($image_tmp_name,$image_folder);
                $message[]="Product added successfully!";
            }
        }else{
            $message[]="Product failed to be added!";
        }
    }
};

// --- Delete Product Logic ---
if(isset($_GET['delete'])){
    $delete_id=$_GET['delete'];

    $delete_img_query=mysqli_query($conn,"SELECT image from `products` WHERE id='$delete_id'") or die('query failed');
    // Fetch the image name correctly
    $fetch_del_img=mysqli_fetch_assoc($delete_img_query);
    if ($fetch_del_img && !empty($fetch_del_img['image'])) {
        unlink('./uploaded_img/'.$fetch_del_img['image']);
    }

    mysqli_query($conn, "DELETE FROM `products` WHERE id='$delete_id'") or die('query failed');
    header('location:admin_products.php');
}

// --- Update Product Logic ---
if(isset($_POST['update_product'])){
    $update_p_id=$_POST['update_p_id'];
    $update_name=mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_price=$_POST['update_price'];
    $old_image=$_POST['update_old_img'];

    mysqli_query($conn,"UPDATE `products` SET name='$update_name', price='$update_price' WHERE id='$update_p_id'") or die('query failed');

    $update_image=$_FILES['update_image']['name'];
    $update_image_tmp_name=$_FILES['update_image']['tmp_name'];
    $update_image_size=$_FILES['update_image']['size'];
    $update_folder='./uploaded_img/'.$update_image;

    if(!empty($update_image)){
        if($update_image_size>2000000){
            $message[]='Image size is too large';
        }else{
            mysqli_query($conn,"UPDATE `products` SET image='$update_image' WHERE id='$update_p_id'") or die('query failed');

            move_uploaded_file($update_image_tmp_name,$update_folder);
            unlink('./uploaded_img/'.$old_image);

            $message[]="Product updated successfully!";
        }
    }
    header('location:admin_products.php');
}

// Get message count for sidebar
$select_messages_count = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
$num_messages = mysqli_num_rows($select_messages_count);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css"> 
</head>
<body class="admin_body">

<?php
// Displaying messages 
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
            <i class="fas fa-book-reader"></i> Bookiee Admin
        </div>
        <div class="user_profile">
            <i class="fas fa-user-circle"></i>
            <p><?php echo $_SESSION['admin_name']; ?> <span>(Admin)</span></p>
        </div>
        
        <nav class="sidebar_nav">
            <a href="admin_page.php" class="nav_item"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="admin_products.php" class="nav_item active"><i class="fas fa-box-open"></i> Products</a>
            <a href="admin_orders.php" class="nav_item"><i class="fas fa-clipboard-list"></i> Orders</a>
            <a href="admin_users.php" class="nav_item"><i class="fas fa-users"></i> Users</a>
            <a href="admin_messages.php" class="nav_item message_count"><i class="fas fa-envelope"></i> Messages <span>(<?php echo $num_messages; ?>)</span></a>
        </nav>
        
        <div class="sidebar_footer">
            <a href="logout.php" class="logout_btn_v15"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main_content_v15">
        <h1 class="products_title_v18"><i class="fas fa-cubes"></i> Product Management</h1>
        
        <section class="add_product_module_v18">
            <form action="" method="post" enctype="multipart/form-data" class="product_form_v18">
                <h3><i class="fas fa-plus-circle"></i> Add New Product</h3>
                
                <div class="form_grid_v18">
                    <input type="text" name="name" class="form_input_v18" placeholder="Enter Product Name" required>
                    
                    <input type="number" min="0" name="price" class="form_input_v18" placeholder="Enter Price (৳)" required>
                    
                    <label class="file_upload_label">
                        <i class="fas fa-upload"></i> Upload Image (JPG/PNG)
                        <input type="file" name="image" class="file_input_v18" accept="image/jpg, image/jpeg, image/png" required>
                    </label>

                    <input type="submit" name="add_products_btn" class="submit_btn_v18" value="Add Product">
                </div>
            </form>
        </section>

        <section class="product_gallery_v18">
            <h2><i class="fas fa-th-large"></i> Existing Products</h2>
            
            <div class="product_grid_v18">
                <?php
                $select_products=mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');

                if(mysqli_num_rows($select_products)>0){
                    while($fetch_products=mysqli_fetch_assoc($select_products)){
                ?>
                <div class="product_card_v18">
                    <img src="./uploaded_img/<?php echo $fetch_products['image'];?>" alt="<?php echo $fetch_products['name'];?>" class="product_img">

                    <div class="product_info">
                        <div class="product_name_v18"><?php echo $fetch_products['name'];?></div>
                        
                        <div class="product_price_v18">
                       ৳.  <span><?php echo $fetch_products['price'];?></span> /-
                        </div>
                    </div>

                    <div class="product_actions_v18">
                        <a href="admin_products.php?update=<?php echo $fetch_products['id']?>" class="action_btn_v18 update_btn_v18"><i class="fas fa-edit"></i> Edit</a>

                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']?>" class="action_btn_v18 delete_btn_v18" onclick= "return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash-alt"></i> Delete</a>
                    </div>
                </div>
                <?php
                    }
                }else{
                    echo '<p class="empty_v18">No products have been added yet.</p>';
                }
                ?>
            </div>
        </section>
    </main>
</div>

<section class="edit_product_modal_v18">
    <?php
    if(isset($_GET['update'])){
        $update_id=$_GET['update'];
        $update_query=mysqli_query($conn,"SELECT * FROM `products` WHERE id='$update_id'") or die('query failed');
        if(mysqli_num_rows($update_query)>0){
            while($fetch_update=mysqli_fetch_assoc($update_query)){
    ?>
    <div class="modal_content_v18">
        <form action="" method="post" enctype="multipart/form-data">
            <h3><i class="fas fa-sync-alt"></i> Update Product</h3>

            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id'];?>">
            <input type="hidden" name="update_old_img" value="<?php echo $fetch_update['image'];?>">

            <img src="./uploaded_img/<?php echo $fetch_update['image'];?>" alt="" class="modal_current_img">

            <input type="text" name="update_name" value="<?php echo $fetch_update['name'];?>" class="form_input_v18" required placeholder="Enter Product Name">

            <input type="number" name="update_price" min="0" value="<?php echo $fetch_update['price'];?>" class="form_input_v18" required placeholder="Enter Product Price">

            <label class="file_upload_label small">
                <i class="fas fa-sync"></i> Change Image (Optional)
                <input type="file" name="update_image" class="file_input_v18" accept="image/jpg, image/jpeg, image/png">
            </label>

            <div class="modal_actions">
                <input type="submit" value="Update Product" name="update_product" class="submit_btn_v18 update_modal_btn">
                <button type="button" id="close_update" class="submit_btn_v18 cancel_modal_btn">Cancel</button>
            </div>
        </form>
    </div>
    <?php
            }
        }
    }
    // The conditional display logic is handled by CSS/JS below
    ?>
</section>

<script src="admin_js.js"></script>
<script>
    // JS to handle the modal display/hide when the 'update' GET parameter is present
    document.addEventListener('DOMContentLoaded', () => {
        const updateModal = document.querySelector('.edit_product_modal_v18');
        const closeUpdateBtn = document.getElementById('close_update');

        if (window.location.search.includes('update=')) {
            updateModal.style.display = 'flex';
        } else {
            updateModal.style.display = 'none';
        }

        // Handle the cancel button click
        if (closeUpdateBtn) {
            closeUpdateBtn.addEventListener('click', () => {
                // Remove the 'update' parameter from the URL without reloading
                window.history.pushState({}, document.title, window.location.pathname);
                updateModal.style.display = 'none';
            });
        }
    });

    // Re-linking admin_js.js for sidebar functionality
    // (Assuming admin_js.js is correctly linked and contains the V15 sidebar logic)
</script>
</body>
</html>