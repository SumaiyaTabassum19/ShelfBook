<?php
include 'config.php';
session_start();

$user_id=$_SESSION['user_id'];

if(!isset($user_id)){
    header('location:login.php');
}
// Check for and display messages (as per previous response)
if(isset($message)){
   foreach($message as $msg){
      echo '<div class="message"><span>'.$msg.'</span> <i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
   }
}

if(isset($_POST['send'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = $_POST['number'];
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    
    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');
    
    if(mysqli_num_rows($select_message) > 0){
         $message[] = 'Message sent already!';
    }else{
         mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
         $message[] = 'Message sent successfully!';
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="style.css"> 

</head>
<body>
    
<?php
include 'user_header.php';
?>

<section class="contact_page_v5">
    <div class="contact_split_container">
        
        <div class="info_panel">
            <div class="info_content">
                <h2>Get in Touch</h2>
                <p>We are dedicated to assisting you with any inquiries regarding orders, products, or recommendations.</p>
                
                <div class="contact_details_list">
                    <div class="detail_item">
                        <i class="fas fa-envelope"></i>
                        <p><span>Email:</span> bookshelf25@gmail.com</p>
                    </div>
                    <div class="detail_item">
                        <i class="fas fa-phone"></i>
                        <p><span>Phone:</span> +880 123 456789</p>
                    </div>
                    <div class="detail_item">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><span>Address:</span> Chittagong, Bangladesh</p>
                    </div>
                </div>
            </div>
            
            <div class="social_links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        
        <div class="form_panel">
             <h2 class="form_title">Send Us a Quick Message</h2>
             <form action="" method="post" class="contact_form_v5">
                
                <input type="text" name="name" required placeholder="Your Full Name" class="form_input">
                <input type="email" name="email" required placeholder="Your Email Address" class="form_input">
                <input type="tel" name="number" required placeholder="Your Phone Number (Optional)" class="form_input">
                
                <textarea name="message" placeholder="Type your message here..." rows="8" required class="form_textarea"></textarea>
                
                <input type="submit" value="Submit Inquiry" name="send" class="submit_btn_v5">
            </form>
        </div>
        
    </div>
</section>

<?php
include 'footer.php';
?>
<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

<script src="script.js"></script>

</body>
</html>