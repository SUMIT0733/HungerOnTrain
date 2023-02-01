<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Restaurant Profile</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('rest_common_libraries.php'); ?>
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
              <li><a href="#activity" data-toggle="tab">Activity</a></li>
              <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
              <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane" id="activity">
  
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                
              </div>
              <!-- /.tab-pane -->

              <div class="active tab-pane" id="settings">
                  <?php if($rest_details['restaurant_name']=='' || $rest_details['email']=='' || $rest_details['contact_no']=='' || $rest_details['restaurant_address']=='' || $rest_details['restaurant_pincode']=='' || $rest_details['restaurant_city']=='' || $rest_details['restaurant_state']=='' ) { ?>
                  <div class="alert alert-info alert-dismissible fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Info!</strong> Please complete profile to get your restaurant account verified.
                  </div>
                  <?php } ?>
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="restaurant_name" class="col-sm-3 control-label">Restaurant Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" placeholder="Restaurant Name" value="<?php echo $rest_details['restaurant_name'];?>">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Email</label>

                    <div class="col-sm-9">
                      <div class="form-control" id="email" name="email" disabled><?php echo $rest_details['email'];?></div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="contact_no" class="col-sm-3 control-label">Contact</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Contact number" value="<?php echo $rest_details['contact_no'];?>">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="restaurant_address" class="col-sm-3 control-label">Address</label>

                    <div class="col-sm-9">
                      
                      <textarea class="form-control" id="restaurant_address" name="restaurant_address" placeholder="Restaurant Address"><?php echo $rest_details['restaurant_address'];?></textarea>

                    </div>
                  </div>

                  <div class="form-group">

                    <label for="state" class="col-sm-3 control-label">State</label>

                    <div class="col-sm-4">
                      <select class="form-control" id="state" name="state">
                        <option value="">Select State</option>
                        <?php foreach($states_list as $state){ ?>
                          <option value="<?php echo $state['state_id']; ?>" <?php if($state['state_id']==$rest_details['restaurant_state']){echo "selected";} ?>> <?php echo $state['state_name']; ?></option>
                          <?php } ?>
                      </select>
                    </div>

                    <label for="city" class="col-sm-1 control-label">City</label>

                    <div class="col-sm-4">
                      <select class="form-control" id="city" name="city">
                        <?php foreach($city_list as $city){ ?>
                          <option value="<?php echo $city['city_id']; ?>" <?php if($city['city_id']==$rest_details['restaurant_city']){echo "selected";} ?>> <?php echo $city['city_name']; ?></option>
                          <?php } ?>
                      </select>
                    </div>

                  </div>

                  <div class="form-group">
                    <label for="pincode" class="col-sm-3 control-label">Pincode</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" value="<?php echo $rest_details['restaurant_pincode'];?>">
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

                  <div class="form-group errordiv">
                    <div class="col-sm-9 col-sm-offset-3">
                      <div class="alert alert-danger">
                        <span id="error">Error</span>
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

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('rest_control_sidebar.php');?>
<?php require_once('rest_footer.php');?>

<script type="text/javascript">

  var isWebsiteDisabled=$('#website').prop('disabled');

  <?php if($rest_details['website']=='NA'){ ?>
    isWebsiteDisabled=true;
    $('#website').val('');
    $('#website').attr('placeholder','Not Available');
    $('#website').prop('disabled','disabled');
    $('#website_notavailbale').html('Available');
  <?php } ?>

  $('.errordiv').hide();

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

  $('#submit').on('click',function(){
    $('.errordiv').hide();
    var restaurant_name=$('#restaurant_name').val();
    var state=$('#state').val();
    var city=$('#city').val();
    var pincode=$('#pincode').val();
    var restaurant_address=$('#restaurant_address').val();
    var contact_no=$('#contact_no').val();
    var website=$('#website').val();

    var profile_pic = $("#profile_pic");
    //var lblError = $("#lblError");
    var allowedFiles = [".jpg", ".jpeg", ".png"];
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    
    if(restaurant_name==''||state==''||city==''||pincode==''||restaurant_address==''||contact_no=='' || (isWebsiteDisabled==false && website==''))
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
    

    form_data.append("restaurant_name", restaurant_name);                 
    // Adding extra parameters to form_data
    form_data.append("state", state);
    form_data.append("city", city);
    form_data.append("pincode", pincode);
    form_data.append("restaurant_address", restaurant_address);
    form_data.append("contact_no", contact_no);
    form_data.append("website", website);

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
            $('.errordiv').fadeIn();
            if(result=='success')
            {
              $('#error').html("Updated Successfuly");
              window.location.href=window.location.href;
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