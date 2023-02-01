
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Restaurant Profile</title>
  <?php require_once('rest_common_libraries.php'); ?>


  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

  <style type="text/css">
    #cont{
    position: relative;
    width:100%;
    height:300px !important;
    }
  #map_canvas{
      overflow: hidden;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
  }
  </style>

  
</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
  <?php 

  require_once('rest_navbar.php');
        
  $cuisines_list=$rest->get_rest_cuisines_list();
  $states_list=$rest->get_states_list();
  $city_list=$rest->get_city_list($rest_details['restaurant_state']);
        
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Restaurant Profile
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Restaurant profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-4">

          <!-- Profile Image -->
            <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?php  
              if($rest_details['profile_url']!='')
              {
                echo $rest_details['profile_url'];
              }
              else{echo 'images/default.jpg';}?>" style="margin: 0 auto;
      width: 150px;padding: 3px;border: 2px solid #ccc; border-radius:3%;" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo $rest_details['restaurant_name'];?></h3>

              <p class="text-muted text-center"><?php if($rest_details['restaurant_city']!=''){ echo $rest_details['city_name'].", ".$rest_details['state_name'];} ?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <strong><i class="fa fa-pencil margin-r-5"></i>Restaurant Name</strong><p class="text-muted"><?php echo $rest_details['restaurant_name'];  ?></p>
                </li>
                
                <li class="list-group-item">
                  <strong><i class="fa fa-envelope margin-r-5"></i>Email</strong>
                  <p class="text-muted"><?php echo $rest_details['email'];  ?></p>

                </li>
                
                <li class="list-group-item">
                  <strong><i class="fa fa-phone margin-r-5"></i>Contact</strong>
                  <p class="text-muted"><?php if($rest_details['contact_no']!=''){echo $rest_details['contact_no'];}else{echo "Not available";}  ?></p>
                  
                </li>

                <li class="list-group-item">
                  <strong><i class="fa fa-map-marker margin-r-5"></i>Address</strong>
                  <p class="text-muted"><?php if($rest_details['restaurant_address']!=''){echo $rest_details['restaurant_address'];}else{echo "Not available";}  ?></p>
                  
                </li>
                <li class="list-group-item">
                  <strong><i class="fa  fa-map-signs margin-r-5"></i>City/State</strong>
                  <p class="text-muted"><?php if($rest_details['restaurant_city']!=''){ echo $rest_details['city_name'].", ".$rest_details['state_name'];} else{
                    echo "Not available";
                  }  ?></p>
                  
                </li>
                <li class="list-group-item">
                  <strong><i class="fa fa-pencil margin-r-5"></i>Bank Account Number</strong>
                  <p class="text-muted"><?php if($rest_details['account_no']!=''){ echo $rest_details['account_no'];} else{
                    echo "Not available";
                  }  ?></p>
                  
                </li>
                <li class="list-group-item">
                  <strong><i class="fa fa-pencil margin-r-5"></i>Bank Account IFSC code</strong>
                  <p class="text-muted"><?php if($rest_details['IFSC_code']!=''){ echo $rest_details['IFSC_code'];} else{
                    echo "Not available";
                  }  ?></p>
                  
                </li>
                <li class="list-group-item">
                  <strong><i class="fa fa-map-pin margin-r-5"></i>Pincode</strong>
                  <p class="text-muted"><?php if($rest_details['restaurant_address']!=''){echo $rest_details['restaurant_pincode']; }else{
                    echo "Not available";
                  } ?></p> 
                </li>

                <li class="list-group-item">
                  <strong><i class="fa fa-globe margin-r-5"></i>Website</strong>
                  <p class="text-muted"><?php if($rest_details['website']!='NA'){echo $rest_details['website']; }else{
                    echo "Not available";
                  } ?></p> 
                </li>
                
                <li class="list-group-item">
                  <strong><i class="fa  fa-list-ul margin-r-5"></i> Cuisines</strong>
                  <p>
                    <?php
                    if(count($cuisines_list)==0)
                    {
                      echo "<span class='text-muted'>Not available</span>";
                    }
                    else
                    {

                      $color_classes=array("label-danger","label-success","label-info","label-warning","label-primary");
                      $i=0;

                      foreach($cuisines_list as $cuisine) 
                      { 
                        if($i==4){$i=0;}?>
                        <span class="label <?php echo $color_classes[$i];?>"><?php echo $cuisine;?></span>
                      <?php $i++; 
                      }
                    } ?>
                  </p>
                </li>
              </ul>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
         <!--  <div class="box box-primary">
    
          </div> -->
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
<!--               <li><a href="#activity" data-toggle="tab">Activity</a></li>
              <li><a href="#timeline" data-toggle="tab">Timeline</a></li> -->
              <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
            <div class="tab-content">

              <div class="active tab-pane" id="settings">

                <?php if($rest_details['restaurant_name']=='' || $rest_details['email']=='' || $rest_details['contact_no']=='' || $rest_details['restaurant_address']=='' || $rest_details['restaurant_pincode']=='' || $rest_details['restaurant_city']=='' || $rest_details['restaurant_state']=='' ) {  ?>
                  <div class="alert alert-warning alert-dismissible fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     Please complete profile to get your restaurant account verified.
                  </div>
                <?php } else if($rest_details['verified']==0) {?>

                  <div class="alert alert-info alert-dismissible fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     Admin has not verified this account yet. Please wait.
                  </div>
                <?php } ?>

                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="restaurant_name" class="col-sm-3 control-label">Restaurant Name<span style="color:red;">*</span></label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" placeholder="Restaurant Name" value="<?php echo $rest_details['restaurant_name'];?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Email<span style="color:red;">*</span></label>

                    <div class="col-sm-9">
                      <div class="form-control" id="email" name="email" disabled><?php echo $rest_details['email'];?></div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="contact_no" class="col-sm-3 control-label">Contact<span style="color:red;">*</span></label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Contact number" value="<?php echo $rest_details['contact_no'];?>">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="restaurant_address" class="col-sm-3 control-label">Address<span style="color:red;">*</span></label>

                    <div class="col-sm-9">
                      
                      <textarea class="form-control" id="restaurant_address" name="restaurant_address" placeholder="Restaurant Address"><?php echo $rest_details['restaurant_address'];?></textarea>

                    </div>
                  </div>

                  <div class="form-group">

                    <label for="state" class="col-sm-3 control-label">State<span style="color:red;">*</span></label>

                    <div class="col-sm-4">
                      <select class="form-control" id="state" name="state">
                        <option value="">Select State</option>
                        <?php foreach($states_list as $state){ ?>
                          <option value="<?php echo $state['state_id']; ?>" <?php if($state['state_id']==$rest_details['restaurant_state']){echo "selected";} ?>> <?php echo $state['state_name']; ?></option>
                          <?php } ?>
                      </select>
                    </div>

                    <label for="city" class="col-sm-1 control-label">City<span style="color:red;">*</span></label>

                    <div class="col-sm-4">
                      <select class="form-control" id="city" name="city">
                        <?php foreach($city_list as $city){ ?>
                          <option value="<?php echo $city['city_id']; ?>" <?php if($city['city_id']==$rest_details['restaurant_city']){echo "selected";} ?>> <?php echo $city['city_name']; ?></option>
                          <?php } ?>
                      </select>
                    </div>

                  </div>

                  <div class="form-group">
                    <label for="pincode" class="col-sm-3 control-label">Pincode<span style="color:red;">*</span></label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" value="<?php echo $rest_details['restaurant_pincode'];?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="account_no" class="col-sm-3 control-label">Bank account number<span style="color:red;">*</span></label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="account_no" name="account_no" placeholder="Bank Account number" value="<?php echo $rest_details['account_no'];?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="IFSC_code" class="col-sm-3 control-label">Bank account IFSC code<span style="color:red;">*</span></label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="IFSC_code" name="IFSC_code" placeholder="IFSC code" value="<?php echo $rest_details['IFSC_code'];?>">
                    </div>
                  </div>
                  
                  <div class="form-group">

                    <label for="website" class="col-sm-3 control-label">Website</label>
                    
                    <div class="col-sm-9">
                      <div class="input-group">
                        <input type="text" class="form-control" id="website" name="website" placeholder="Website ( If not available, press 'Not Available' button )" value="<?php echo $rest_details['website'];?>">
                        <span class="input-group-btn">
                          <button type="button" id="website_notavailbale" class="btn btn-success" style="padding-left:10px;">Not Available</button>
                        </span>
                      </div>
                    </div>

                  </div>

                  <div class="form-group">
                    <label for="pincode" class="col-sm-3 control-label">Change Profile Picture</label>

                    <div class="col-sm-9">
                      <input type="file" class="form-control" id="profile_pic" name="profile_pic">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="map_location" class="col-sm-3 control-label">Add Map Location</label>

                    <div class="col-sm-4">
                      <button type="button" id="map_location" name="map_location" class="btn btn-info" data-toggle="modal" data-target="#myModal">Open Google Map</button>
                    </div>
                    <div class="col-sm-8">
                      
                  <input type="hidden" id="lat" name="lat" value="<?php echo $rest_details['lat']; ?>" />
                  <input type="hidden" id="long" name="long" value="<?php echo $rest_details['lng']; ?>" />

                    </div> 
                  </div>

                  <div class="form-group errordiv">
                    <div class="col-sm-9 col-sm-offset-3">
                      <div class="alert alert-danger">
                        <span id="error"></span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group successdiv">
                    <div class="col-sm-9 col-sm-offset-3">
                      <div class="alert alert-success">
                        <span id="success"></span>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-3">
                      <button type="button" id="submit" class="btn btn-primary btn-block">Update</button>
                    </div> 
                  </div>
                  
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Put marker on restaurant location</h4>
            </div>
            <div class="modal-body">
                  <div id="cont">
                    <div id="map_canvas" style="width: 100%; height: 100%;"></div>
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('rest_control_sidebar.php');?>
<?php require_once('rest_footer.php');?>

<script type="text/javascript">

  $('.errordiv').hide();
  $('.successdiv').hide();
  

  var isWebsiteDisabled=$('#website').prop('disabled');

  <?php if($rest_details['website']=='NA'){ ?>
    isWebsiteDisabled=true;
    $('#website').val('');
    $('#website').attr('placeholder','Not Available');
    $('#website').prop('disabled','disabled');
    $('#website_notavailbale').html('Available');
  <?php } ?>


  $('#website_notavailbale').click(function(){
    isWebsiteDisabled=$('#website').prop('disabled');
    if(isWebsiteDisabled==false)
    {
      isWebsiteDisabled=true;
      $('#website').val('');
      $('#website').attr('placeholder','Not Available');
      $('#website').prop('disabled','disabled');
      $('#website_notavailbale').html('Available');
    }
    else
    {
      isWebsiteDisabled=false;
      $('#website').val('');
      $('#website').attr('placeholder',"Website ( If not available, press 'Not Available' button )");
      $('#website').prop('disabled',false);
      $('#website_notavailbale').html('Not Available');
    }
  });


    var rest_lat="<?php echo $rest_details['lat']; ?>";
    var rest_long="<?php echo $rest_details['lng']; ?>";

    var initial_marker_lat;
    var initial_marker_long;

    if(rest_lat==0 && rest_long==0)
    {
      //set initial marker to mumbai
      initial_marker_lat=19.076090;
      initial_marker_long=72.877426;
    }
    else{
      //set initial marker to current already added restaurant location
      initial_marker_lat=rest_lat;
      initial_marker_long=rest_long;
    }




  $('#submit').on('click',function(){
    $('.errordiv').hide();
    var restaurant_name=$('#restaurant_name').val();
    var state=$('#state').val();
    var city=$('#city').val();
    var pincode=$('#pincode').val();
    var restaurant_address=$('#restaurant_address').val();
    var contact_no=$('#contact_no').val();
    var account_no=$('#account_no').val();
    var IFSC_code=$('#IFSC_code').val();

    var website=$('#website').val();

    var profile_pic = $("#profile_pic");
    //var lblError = $("#lblError");
    var allowedFiles = [".jpg", ".jpeg", ".png"];
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");


    var lat=$('#lat').val();
    var long=$('#long').val();

    
    if(restaurant_name==''||state==''||city==''||pincode==''||restaurant_address==''||contact_no=='' || account_no=='' || IFSC_code=='' || (isWebsiteDisabled==false && website==''))
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

    var pincodepattern=/^[0-9]{6}$/;
    if(!$.trim(pincode).match(pincodepattern))
    {
      $('.errordiv').fadeIn();
      $('#error').html('Please enter valid pincode');
      return false;
    }

    var accountnopattern=/^[0-9]+$/;
    if(!$.trim(account_no).match(accountnopattern))
    {
      $('.errordiv').fadeIn();
      $('#error').html('Please enter valid account number');
      return false;
    }


/*    if (!regex.test(profile_pic.val().toLowerCase())) {
        $('.errordiv').fadeIn();
        $('#error').html("Please upload image having extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
        return false;
    }*/

    var file_data = $("#profile_pic").prop("files")[0];   // Getting the properties of file from file field
    var form_data = new FormData();     // Creating object of FormData class

    var filePath = $('#profile_pic').val();
    if(filePath!='')
    {
      var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
      if(!allowedExtensions.exec(filePath)){
        $('.errordiv').fadeIn();
        $('#error').html('Please upload image having extensions .jpeg/.jpg/.png only.');
        return false;
      }
      else
      {
        form_data.append("file", file_data);    // Appending parameter named file with properties of file_field to form_data 
      }
    }
    

    if(lat==0 && long==0)
    {
      $('.errordiv').fadeIn();
      $('#error').html('Please select restaurant location in google maps.');
        return false;              
    }

    if((rest_lat!=0 && rest_long!=0) && (lat!=rest_lat || long!=rest_long))
    {
      $('.errordiv').fadeIn();
      $('#error').html('You cannot change restaurant location now.');
        return false;
    }

    form_data.append("restaurant_name", restaurant_name);                 
    // Adding extra parameters to form_data
    form_data.append("state", state);
    form_data.append("city", city);
    form_data.append("pincode", pincode);
    form_data.append("restaurant_address", restaurant_address);
    form_data.append("contact_no", contact_no);
    form_data.append("account_no", account_no);
    form_data.append("IFSC_code", IFSC_code);
    form_data.append("website", website);
    form_data.append("lat", lat);
    form_data.append("long", long);

    $.ajax({
          url: "restaurant_update_profile_data.php",
          dataType: 'script',
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,                         // Setting the data attribute of ajax with file_data
          type: 'post',
          success:function(result)
          {
            $('.successdiv').fadeIn();
            if(result=='success')
            {
              $('#success').html("Updated Successfuly");
              //window.location.href=window.location.href;
              setTimeout(function(){window.location.href='restaurant_profile.php';},500);

            }
          },
    });
  });

  $('#state').on('change',function(){
    var state_id=$('#state').val();
    $.ajax({
      url:'../state_city.php',
      method:'POST',
      data:{'state_id':state_id},
      success:function(result){
        $('#city').html(result);
      },
    });
  });

</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-7rM2gy3d-0p07nv__onmDi9L4hok1pU&callback=initialize"
  async defer></script>

<script type="text/javascript">
        
    var map;

    function initialize() {
        var myLatlng = new google.maps.LatLng(initial_marker_lat,initial_marker_long);

        var myOptions = {
            zoom: 8,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

        var marker = new google.maps.Marker({
            draggable: true,
            position: myLatlng,
            map: map,
            title: "Your location"
        });

        google.maps.event.addListener(marker, 'dragend', function (event) {
            document.getElementById("lat").value = event.latLng.lat();
            document.getElementById("long").value = event.latLng.lng();
        });
    }
    google.maps.event.addDomListener(window, "load", initialize());
  
</script>
