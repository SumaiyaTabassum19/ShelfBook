<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

    $email=mysqli_real_escape_string($conn,$_POST['email']);
    // Hash the input password using MD5 to compare with the stored hash
    $password=mysqli_real_escape_string($conn,md5($_POST['password']) );

    $select_users=mysqli_query($conn,"SELECT * FROM `register` WHERE email='$email' AND password='$password'") or die('query failed');

    if(mysqli_num_rows($select_users) > 0){
        $row=mysqli_fetch_assoc($select_users);

        if($row['user_type'] =='admin'){
            $_SESSION['admin_name']=$row['name'];
            $_SESSION['admin_email']=$row['email'];
            $_SESSION['admin_id']=$row['id'];
            header('location:admin_page.php');

        }elseif($row['user_type'] =='user'){
            $_SESSION['user_name']=$row['name'];
            $_SESSION['user_email']=$row['email'];
            $_SESSION['user_id']=$row['id'];
            header('location:home.php');
        }
    }else{
        $message[]='Incorrect email or password';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="login.css">
</head>
<body class="auth_body_v10">

<?php
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


<div class="auth_container_v10">
    <form action="" method="post" class="login_form_v10">
        <h2>BookShelf Login</h2>

        <div class="form_group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required class="form_input_v10">
        </div>

        <div class="form_group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required class="form_input_v10">
        </div>
        
        <div class="form_utility_links">
            <a href="#" class="footer_link_v10">Forgot Password?</a>
        </div>

        <input type="submit" value="Login" name="submit" class="submit_btn_v10">
        
        <div class="form_footer_links">
            <p>Don't have an account? <a href="register.php" class="footer_link_v10">Register Now</a></p>
        </div>
    </form>
</div>

<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>
</body>
</html>