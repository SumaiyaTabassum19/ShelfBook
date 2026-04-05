<?php
include 'config.php';
session_start();

$user_id=$_SESSION['user_id'];

if(!isset($user_id)){
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | BookShelf</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="style.css">
    <!-- <link rel="stylesheet" href="about.css"> -->

</head>
<body>
    
<?php
include 'user_header.php';
?>

<section class="asym_intro_section">
    <div class="intro_text_block">
        <h1 class="page_title">The Journey of BookShelf</h1>
        <p class="tagline">More than just books, we curate experiences for every reader.</p>
        <p class="main_story">
            Founded on the principle that reading should be an accessible adventure, Bookiee has grown from a small neighborhood shop into a trusted online literary hub. Our mission is simple: to connect readers with stories that inspire, challenge, and entertain, all while fostering a genuine community of book enthusiasts.
        </p>
        <p class="main_story">
            We are dedicated to quality service, ethical sourcing, and promoting diverse voices across all genres. Join us in celebrating the power of the written word.
        </p>
        <a href="#stats" class="action_link">See Our Impact <i class="fas fa-arrow-down"></i></a>
    </div>
    <div class="intro_image_container">
        <img src="about1.jpg" alt="A well-organized modern library or bookshelf" class="intro_main_image">
    </div>
</section>

<section class="stats_section" id="stats">
    <h2 class="stats_heading">Our Impact in Numbers</h2>
    <div class="stats_grid">
        <div class="stat_box">
            <i class="fas fa-users"></i>
            <span class="stat_number">50k+</span>
            <p class="stat_label">Happy Readers</p>
        </div>
        <div class="stat_box">
            <i class="fas fa-book"></i>
            <span class="stat_number">12k+</span>
            <p class="stat_label">Books in Stock</p>
        </div>
        <div class="stat_box">
            <i class="fas fa-globe"></i>
            <span class="stat_number">50+</span>
            <p class="stat_label">Countries Served</p>
        </div>
        <div class="stat_box">
            <i class="fas fa-star"></i>
            <span class="stat_number">4.9/5</span>
            <p class="stat_label">Average Rating</p>
        </div>
    </div>
</section>

<section class="quote_section">
    <div class="quote_content">
        <i class="fas fa-quote-left quote_icon"></i>
        <p class="quote_text">BookShelf is my go-to place for unique finds. Their personalized recommendations have never steered me wrong. Truly a bookstore built by readers, for readers.</p>
        <p class="quote_author">- Abrar, Loyal Customer Since 2025</p>
    </div>
</section>

<section class="contact_cta_v3">
    <div class="cta_inner_box">
        <h2>Ready to Start Reading?</h2>
        <p>Explore our curated collections or reach out to our team for a personal recommendation.</p>
        <button class="primary_btn_v3" onclick="window.location.href='contact.php'">Get In Touch</button>
    </div>
</section>

<?php
include 'footer.php';
?>
<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

<script src="script.js"></script>

</body>
</html>