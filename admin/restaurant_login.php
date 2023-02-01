<?php 
session_start();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>HOT</title>
    <?php require_once('common_libraries.php'); ?>
    <?php require_once('google_libraries.php');?>
    <style type="text/css">
    /* Credit to bootsnipp.com for the css for the color graph */
    .colorgraph {
      height: 5px;
      border-top: 0;
      background: #c4e17f;
      border-radius: 5px;
      background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
      background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
      background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
      background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
    }
    </style>
  

  </head>
<body>

<div style="margin-bottom: 90px;">
<?php require_once('navbar.php'); ?>
</div>

<div class="container" style="display: inline;float: left;width: 100%;position: relative;background-color:white;margin-top:30px;margin-bottom: 40px;">

  <div class="row" style="border:0px solid grey;">
      <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4" style="background-color:#1d273c;">
      
      <div id="signin">
        <h3><b><span style="color:white;">Sign In</span></b></h3>
        <hr class="colorgraph">

  
        <div class="form-group">
          <input type="text" name="email" id="email" class="form-control input-lg" placeholder="Email Address" tabindex="1" required>
        </div>

        <div class="form-group">
          <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="2" required>  
        </div>
        
        <div class="form-group">
          <div class="errordiv">
            <br/>
            <div class="alert alert-danger">
              <span id="error">Error</span>
            </div>
          </div>
        </div>

        <hr class="colorgraph">
        <div class="row">
          <div class="col-xs-12 col-md-5">
            <button id="submit" class="btn btn-primary btn-block btn-lg" tabindex="3">Login</button>
          </div> 
          <div class="col-xs-12 col-md-1" style="vertical-align: center;"><span style="color:white;">Or</span></div>
          <div class="col-xs-12 col-md-5"><a href="#" class="g-signin2" data-onsuccess="onSignIn"></a></div>
        </div>
        
      </div>
      <!-- End div="signin" -->
      <br/>

    </div>
  </div>

</div>

<script type="text/javascript">
  $('.errordiv').hide();
  $('#password_policy').tooltip();
  $('#submit').on('click',function(){
    $('.errordiv').hide();
    var email=$('#email').val();
    var pass=$('#password').val();

    if(email==''|| pass=='')
    {
      $('.errordiv').fadeIn();
      $('#error').html('Please fill all fields');
      return false;
    }

    var emailpattern=/^\w+([\._]?\w+)*@\w+([\._]?\w+)*(\.\w{2,3})+$/;
    if(!$.trim(email).match(emailpattern))
    {
      $('.errordiv').fadeIn();
      $('#error').html('Please enter valid email');
      return false;
    }

    $.ajax({
      url:'restaurant_login_data.php',
      method:'POST',
      data:{'email':email,'pass':pass},
      success:function(result){
        $('.errordiv').fadeIn();

        if(result=='success')
        {
          $('#error').text('Redirecting...');
          setTimeout(function(){window.location='restaurant/restaurant_profile.php';},500);
          
        }
        else
        {
          $('#error').html(result);
        }
      },
    });

  });
</script>

<?php require_once('footer.php'); ?>