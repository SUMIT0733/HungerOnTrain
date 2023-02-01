<?php 
session_start();
require_once('database/dbconfig.php');
$state_q="select state_id,state_name from city_master group by state_id order by state_name asc";
$state_q_ex=$db->query($state_q);

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
	

    <script type="text/javascript">
        $(document).ready(function () {
            $('.button-checkbox').each(function () {

                // Settings
                var $widget = $(this),
                    $button = $widget.find('button'),
                    $checkbox = $widget.find('input:checkbox'),
                    color = $button.data('color'),
                    settings = {
                        on: {
                            icon: 'glyphicon glyphicon-check'
                        },
                        off: {
                            icon: 'glyphicon glyphicon-unchecked'
                        }
                    };

                // Event Handlers
                $button.on('click', function () {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    $checkbox.triggerHandler('change');
                    updateDisplay();
                });
                $checkbox.on('change', function () {
                    updateDisplay();
                });

                // Actions
                function updateDisplay() {
                    var isChecked = $checkbox.is(':checked');

                    // Set the button's state
                    $button.data('state', (isChecked) ? "on" : "off");

                    // Set the button's icon
                    $button.find('.state-icon')
                        .removeClass()
                        .addClass('state-icon ' + settings[$button.data('state')].icon);

                    // Update the button's color
                    if (isChecked) {
                        $button
                            .removeClass('btn-default')
                            .addClass('btn-' + color + ' active');
                    }
                    else {
                        $button
                            .removeClass('btn-' + color + ' active')
                            .addClass('btn-default');
                    }
                }

                // Initialization
                function init() {
                    updateDisplay();

                    // Inject the icon if applicable
                    if ($button.find('.state-icon').length == 0) {
                        $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
                    }
                }
                init();
            });
        });
    </script>
  </head>
<body>

<div style="margin-bottom: 90px;">
<?php require_once('navbar.php'); ?>
</div>
<div class="container" style="display: inline;float: left;width: 100%;position: relative;background-color:#1d273c;">

	<div class="row" style="border:0px solid grey;">
	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			

			<div id="signup">
				<h3><b><span style="color:white;">Sign Up</span></b></h3>
				<hr class="colorgraph">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
	                        <input type="text" name="restaurant_name" id="restaurant_name" class="form-control input-lg" placeholder="Restaurant Name" tabindex="1" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<select class="form-control input-lg" id="state" name="state" tabindex="2" required>
								<option value=""><b>Select State</b></option>
							<?php foreach($state_q_ex as $row){ ?>
								<option value="<?php echo $row['state_id']; ?>"><?php echo $row['state_name']; ?></option>
							<?php } ?>
							</select>			
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<select class="form-control input-lg" id="city" name="city" tabindex="3" required>
								<option value=""><b>Select City</b></option>
							</select>
						</div>
					</div>
				</div>
	
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="button" class="btn btn-success input-lg" style="padding-right:10px;">+91</button>
						</span>
						<input type="text" name="contact_no" id="contact_no" class="form-control input-lg" placeholder="Contact Number" pattern="[0-9]{10}" tabindex="4" required>
					</div>
				</div>

				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" tabindex="5" required>
				</div>
				<div class="row">
					<div class="col-xs-11 col-sm-5 col-md-5">
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="6" required>  
						</div>
					</div>
					<div class="col-xs-1 col-sm-1 col-md-1" style="padding-left: 0px;">
					<a id="password_policy" href="#" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="<b><p style='border-bottom:1px solid white;'>Password Policy:</p></b><ul><li style='border-bottom:1px solid white;'>Minimum 6 Characters.</li><li style='border-bottom:1px solid white;'>Maximum 15 Characters.</li><li style='border-bottom:1px solid white;'>Minimum one Letter.</li><li style='border-bottom:1px solid white;'>Minimum One Numeric Character.</li><li style='border-bottom:1px solid white;'>Minimum One Special Character(@$!%*#?&).</li></ul>" class="red-tooltip glyphicon glyphicon-question-sign" style="color:white;"></a>
					</div>

					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<input type="password" name="cpassword" id="cpassword" class="form-control input-lg" placeholder="Confirm Password" tabindex="7" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-4 col-sm-3 col-md-3">
						<span class="button-checkbox">
							<button type="button" class="btn" data-color="info" tabindex="8"> I Agree</button>
	                        <input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
						</span>
					</div>
					<div class="col-xs-8 col-sm-9 col-md-9">
						 <span style="color:white;">By clicking </span><strong class="label label-primary">Register</strong><span style="color:white;">, you agree to the </span><a href="#" data-toggle="modal" data-target="#t_and_c_m"><span style="color:white;">Terms and Conditions</span></a>.
					</div>
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
						<button id="submit" class="btn btn-primary btn-block btn-lg" tabindex="9">Register</button>
					</div> 
					<div class="col-xs-12 col-md-1" style="vertical-align: center;"><span style="color:white;">Or</span></div>
					<div class="col-xs-12 col-md-5"><a href="#" class="g-signin2" data-onsuccess="onSignIn"></a></div>
				</div>
			</div>
			<!-- End div="signup" -->
			<br/>

		</div>
	</div>

</div>

<script type="text/javascript">
	$('.errordiv').hide();
	$('#password_policy').tooltip();
	$('#submit').on('click',function(){
		$('.errordiv').hide();
		var restaurant_name=$('#restaurant_name').val();
		var state=$('#state').val();
		var city=$('#city').val();
		var contact_no=$('#contact_no').val();
		var email=$('#email').val();
		var pass=$('#password').val();
		var cpass=$('#cpassword').val();

		if(restaurant_name==''||state==''||city==''||contact_no==''||email==''||pass==''||cpass=='')
		{
			$('.errordiv').fadeIn();
			$('#error').html('Please fill all fields');
			return false;
		}

		var contactpattern=/^[0-9]{10}$/;
		if(!$.trim(contact_no).match(contactpattern))
		{
			$('.errordiv').fadeIn();
			$('#error').html('Please enter valid contact number');
			return false;
		}

		var emailpattern=/^\w+([\._]?\w+)*@\w+([\._]?\w+)*(\.\w{2,3})+$/;
		if(!$.trim(email).match(emailpattern))
		{
			$('.errordiv').fadeIn();
			$('#error').html('Please enter valid email');
			return false;
		}

		var passpattern=/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,15}$/;
		if(!pass.match(passpattern))
		{
			$('.errordiv').fadeIn();
			$('#error').html('Password does not match with pattern');
			return false;
		}

		if(pass != cpass)
		{
			$('.errordiv').fadeIn();
			$('#error').html('Password and Confirm Password does not match');
			return false;
		}

		if($('#t_and_c').prop("checked")==false)
		{
			$('.errordiv').fadeIn();
			$('#error').html('Please accept Terms & Conditions');
			return false;	
		}

		$.ajax({
			url:'restaurant_register_data.php',
			method:'POST',
			data:{'restaurant_name':restaurant_name,'state':state,'city':city,'contact_no':contact_no,'email':email,'pass':pass,'cpass':cpass},
			success:function(result){
				$('.errordiv').fadeIn();
				if(result=='success')
				{
					$('#error').html('Redirecting...');
          			setTimeout(function(){window.location='restaurant/restaurant_profile.php';},500);
				}
				else
				{
					$('#error').html(result);
				}
			},
		});

	});

	$('#state').on('change',function(){
		var state_id=$('#state').val();
		$.ajax({
			url:'state_city.php',
			method:'POST',
			data:{'state_id':state_id},
			success:function(result){
				$('#city').html(result);
			},
		});
	});
</script>

<?php require_once('footer.php'); ?>