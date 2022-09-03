<?php 
    $page_title="Homepage";
    include('includes/header.html');
    echo '<h2 class="header2">Your one stop solution for all courses and their feedbacks</h2>
    <h3 class="header3">Signup/Login to view more reviews, reviewer details, and to make your own review!</h3>';
    include('includes/filteredreviews.php');
    include('includes/footer.html');
?>