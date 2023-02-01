<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>Hunger On Train</title>
    <?php require_once('common_libraries.php'); ?>
  </head>
<body>


<?php require_once('navbar.php'); ?>

 

  <!-- Start slider  -->
  <section id="mu-slider">
    <div class="mu-slider-area"> 

      <!-- Top slider -->
      <div class="mu-top-slider">

        <!-- Top slider single slide -->
        <div class="mu-top-slider-single">
          <img src="assets/img/bg.jpeg" alt="img">
          <!-- Top slider content -->
          <div class="mu-top-slider-content">
            <span class="mu-slider-small-title">Welcome</span>
            <h2 class="mu-slider-title">To HungerOnTrain</h2>
            <p>Order your favorite food and get delivered at your seat.</p>           
            <a href="#mu-reservation" class="mu-readmore-btn mu-reservation-btn">Download App</a>
          </div>
          <!-- / Top slider content -->
        </div>
        <!-- / Top slider single slide -->    

         <!-- Top slider single slide -->
        <div class="mu-top-slider-single">
          <img src="assets/img/bg.jpeg" alt="img">
          <!-- Top slider content -->
          <div class="mu-top-slider-content">
            
            <span class="mu-slider-small-title">Welcome</span>
            <h2 class="mu-slider-title">To HungerOnTrain</h2>
            <p>Register your restaurant here to get orders.</p>           
            <a href="register_restaurant.php" class="mu-readmore-btn">Register Restaurant</a>

          </div>
          <!-- / Top slider content -->
        </div>
        <!-- / Top slider single slide --> 
 

      </div>
    </div>
  </section>
  <!-- End slider  -->



<?php require_once('footer.php'); ?>
