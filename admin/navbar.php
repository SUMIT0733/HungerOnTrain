

<script type="text/javascript">
  var profile=null;
  var id_token=null;
  var authUser=null;

  $(document).ready(function onload() {  
          gapi.load('auth2', function() {         
            gapi.auth2.init(); 
          });     
  });

  function onSignIn(googleUser) {
    profile = googleUser.getBasicProfile();
    id_token = googleUser.getAuthResponse().id_token;
    //authUser=googleUser;
/*    document.cookie = "userprofile="+googleUser;
*/
    //alert('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
    $.ajax({
      type:'POST',
      url:'google_signin_data.php',
      data:{email:profile.getEmail(),name:profile.getName()},
      success:function(result){
        if(result=='success')
        {
          window.location.href='restaurant/restaurant_profile.php';
        }
      },
    });
  }

/*  function getCookie(cname) {
      var name = cname + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(';');
      for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }
*/
    function signOut()
    {
      <?php session_destroy(); ?>  
      window.location.href="restaurant_login.php";
    }

</script>
  <!--START SCROLL TOP BUTTON -->
    <a class="scrollToTop" href="#">
      <i class="fa fa-angle-up"></i>
    </a>
  <!-- END SCROLL TOP BUTTON -->

  <!-- Start header section -->
  <header id="mu-header">  
    <nav class="navbar navbar-default mu-main-navbar" role="navigation">  
      <div class="container">
        <div class="navbar-header">
          <!-- FOR MOBILE VIEW COLLAPSED BUTTON -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <!-- LOGO -->       

           <!--  Text based logo  -->
            <a class="navbar-brand" href="index.php">HOT</a> 

              <!--  Image based logo  -->
          <!-- <a class="navbar-brand" href="index.html"><img src="assets/img/logo.png" alt="Logo img"></a>  -->
         

        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul id="top-menu" class="nav navbar-nav navbar-right mu-main-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="#about-us">About us</a></li>                       
            <li><a href="#contact-us">Contact us</a></li>
            
            <?php if(isset($_SESSION['restaurant_email']) && isset($_SESSION['restaurant_name'])){?>
              <li><a href="#" onclick="signOut();">Logout</a></li>
              <li><a href="#"> Welcome, <?php echo $_SESSION['restaurant_name'];?></a></li>
            <?php } 
            else { ?>
              <li><a href="admin_login.php">Admin Login</a></li>
              <li><a href="register_restaurant.php">Register Restaurant</a></li>
              <li><a href="restaurant_login.php">Restaurant Login</a></li>
            <?php } ?>
          </ul>                            
        </div><!--/.nav-collapse -->       
      </div>          
    </nav> 
  </header>
  <!-- End header section -->

