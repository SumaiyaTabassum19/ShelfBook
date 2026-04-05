<?php
include 'config.php';
session_start();

$admin_id=$_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:login.php');
}

if(isset($_GET['delete'])){
    $delete_id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM `message` WHERE id='$delete_id'") or die('query failed');
    // Using an array for message display consistent with previous scripts
    $message[]='1 message has been deleted'; 
    // Note: The header redirect will clear the $message variable unless stored in session
    header("location:admin_messages.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css"> 
</head>
<body class="admin_body">

<?php
// Displaying messages (like "1 message has been deleted")
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

<!-- ?php
include 'admin_header.php';
?> -->

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
            <a href="admin_orders.php" class="nav_item"><i class="fas fa-clipboard-list"></i> Orders</a>
            <a href="admin_users.php" class="nav_item"><i class="fas fa-users"></i> Users</a>
            <a href="admin_messages.php" class="nav_item active message_count"><i class="fas fa-envelope"></i> Messages <span>(<?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `message`")); ?>)</span></a>
        </nav>
        
        <div class="sidebar_footer">
            <a href="logout.php" class="logout_btn_v15"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main_content_v15">
        <h1 class="messages_title_v16"><i class="fas fa-envelope-open-text"></i> Customer Messages</h1>
        
        <section class="messages_section_v16">
            <div class="messages_container_v16">
                <?php
                $select_msgs=mysqli_query($conn,"SELECT * FROM `message` ORDER BY id DESC") or die('query failed');

                if(mysqli_num_rows($select_msgs)>0){
                    while($fetch_msgs=mysqli_fetch_assoc($select_msgs)){
                ?>
                <div class="message_card_v16">
                    <div class="message_details">
                        <p class="detail_item"><i class="fas fa-user-tag"></i> Name: <span><?php echo $fetch_msgs['name']; ?></span></p>
                        <p class="detail_item"><i class="fas fa-phone"></i> Number: <span><?php echo $fetch_msgs['number']; ?></span></p>
                        <p class="detail_item"><i class="fas fa-at"></i> Email: <span><?php echo $fetch_msgs['email']; ?></span></p>
                    </div>
                    
                    <div class="message_body">
                        <h3>Message:</h3>
                        <p><?php echo $fetch_msgs['message']; ?></p>
                    </div>

                    <a href="admin_messages.php?delete=<?php echo $fetch_msgs['id']; ?>" onclick="return confirm('Are you sure you want to delete this message?');" class="delete_btn_v16"><i class="fas fa-trash-alt"></i> Delete</a>
                </div>
                <?php
                    };
                }
                else{
                    echo '<p class="empty_v16">No new messages yet!</p>';
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