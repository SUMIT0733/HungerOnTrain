<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Restaurants Verification</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('admin_common_libraries.php'); ?>
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js"></script>
  <style type="text/css">
    #pending_rest_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #verified_rest_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }

    .modal_labels
    {
       color:white;
    }
  </style>

</head>

<body class="hold-transition skin-blue sidebar-mini" ng-app="MyApp">

<div class="wrapper" ng-controller="MyController">
  <?php 

  require_once('admin_navbar.php');
  
  $pending_restaurants=$admin->get_pending_restaurants();
  $verified_restaurants=$admin->get_verified_restaurants();
  //$verified=$admin->get_verified_restaurants();
 
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Restaurants Verification
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Restaurants Verification</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            
            <ul class="nav nav-tabs" id="nav-tabs">
              <li><a href="#pending" id="pending_tab" data-toggle="tab">Pending</a></li>
              <li><a href="#verified" id="verified_tab"  data-toggle="tab">Verified</a></li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane" id="pending">
                  <br/>

                  <div class="table-responsive">
                    
                    <table id="pending_rest_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Restaurant Name</th>
                              <th style="max-width:100px;">City</th>
                              <th style="max-width:100px;">State</th>
                              <th>Email</th>
                              <th>View Profile</th>
                              <th>View Menu</th>
                              <th>Verify</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="restaurant in pending_restaurants">
                                <td>{{restaurant.restaurant_name}}</td>
                                <td style="max-width:100px;">{{restaurant.city_name}}</td>
                                <td style="max-width:100px;">{{restaurant.state_name}}</td>
                                <td>{{restaurant.email}}</td>
                                <td><button class="btn btn-primary" ng-click="view_pending_rest_profile(restaurant.restaurant_id);" data-toggle="modal" data-target="#myModal">View Profile</button></td>
                                <td><button class="btn btn-warning" ng-click="view_rest_menu(restaurant.restaurant_id);">View Menu</button></td>
                                <td><button class="btn btn-success" ng-click="verify_restaurant(restaurant.restaurant_id)">Verify</button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search Restaurant Name" style="max-width:200px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search City" /></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search State" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search Email" /></th>
                              <th style="display: none;"></th>
                              <th style="display: none;"></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                    </table>
                  </div>

                </div>
                <!-- /.tab-pane -->
                

                <div class="tab-pane" id="verified">
                  <br/>

                  <div class="table-responsive">
                    
                    <table id="verified_rest_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Restaurant Name</th>
                              <th style="max-width:100px;">City</th>
                              <th style="max-width:100px;">State</th>
                              <th>Email</th>
                              <th>View Profile</th>
                              <th>View Menu</th>
                              <th>Unverify</th>
                            </tr>
                        <tbody>
                        </thead>
                            <tr ng-repeat="restaurant in verified_restaurants">
                                <td>{{restaurant.restaurant_name}}</td>
                                <td style="max-width:100px;">{{restaurant.city_name}}</td>
                                <td style="max-width:100px;">{{restaurant.state_name}}</td>
                                <td>{{restaurant.email}}</td>
                                <td><button class="btn btn-primary" ng-click="view_verified_rest_profile(restaurant.restaurant_id);" data-toggle="modal" data-target="#myModal">View Profile</button></td>
                                <td><button class="btn btn-warning" ng-click="view_rest_menu(restaurant.restaurant_id);">View Menu</button></td>
                                <td><button class="btn btn-success" ng-click="unverify_restaurant($index,restaurant.restaurant_id);">Unverify</button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search Restaurant Name" style="max-width:200px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search City" /></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search State" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search Email" /></th>
                              <th style="display: none;"></th>
                              <th style="display: none;"></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                    </table>
                  </div>

                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- End tab-content -->

          </div>
          <!--End nav-tabs-custom -->
        </div>
      </div>
      <!--End row -->

    </section>
    <!-- End content -->
    
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Restaurant Details</h4>
          </div>
          <div class="modal-body" style="background-color: #0a1f27;">
            <form class="form-horizontal">
                  <div class="form-group">
                    <label for="restaurant_name" class="col-sm-3 control-label modal_labels">Restaurant Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" value="{{rest_object.restaurant_name}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-3 control-label modal_labels">Email</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="email" name="email" value="{{rest_object.email}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="contact_no" class="col-sm-3 control-label modal_labels">Contact</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{rest_object.contact_no}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="restaurant_address" class="col-sm-3 control-label modal_labels">Address</label>

                    <div class="col-sm-9">
                      
                      <textarea class="form-control" id="restaurant_address" name="restaurant_address" style="background-color: white;font-weight: bold;" readonly>{{rest_object.restaurant_address}}</textarea>

                    </div>
                  </div>

                  <div class="form-group">

                    <label for="state" class="col-sm-3 control-label modal_labels">State</label>

                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="state" name="state" value="{{rest_object.state_name}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>

                    <label for="city" class="col-sm-1 control-label modal_labels">City</label>

                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="city" name="city" value="{{rest_object.city_name}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>

                  </div>

                  
                  <div class="form-group">
                    <label for="pincode" class="col-sm-3 control-label modal_labels">Pincode</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="pincode" name="pincode"  value="{{rest_object.restaurant_pincode}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="account_no" class="col-sm-3 control-label modal_labels">Account no</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="account_no" name="account_no"  value="{{rest_object.account_no}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="IFSC_code" class="col-sm-3 control-label modal_labels">IFSC code</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="IFSC_code" name="IFSC_code"  value="{{rest_object.IFSC_code}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="website" class="col-sm-3 control-label modal_labels">Website</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="website" name="website" value="{{rest_object.website}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="register_at" class="col-sm-3 control-label modal_labels">Registered At</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="register_at" name="register_at" value="{{rest_object.created_at}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>
 
                 
                </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <!-- end Modal -->

    <!-- Modal -->
    <div id="menuModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Restaurant Menu</h4>
          </div>
          <div class="modal-body">
            <!-- <div ng-if="isGotData"> -->
            <my-restaurant menu1="menu_data"></my-restaurant>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <!-- end Modal -->

  </div>
  <!-- End content-wrapper -->


<?php require_once('admin_control_sidebar.php');?>
<?php require_once('admin_footer.php');?>

<!-- Sweet alerts -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script type="text/javascript">
  $(document).ready(function() {

    //It will fetch all query strings from url
    const urlParams = new URLSearchParams(window.location.search);
    const show_tab = urlParams.get('show_restaurants');
    
    if(show_tab=='verified_restaurants')
    {
      $('#verified_tab').trigger('click');
    }
    else
    {
      $('#pending_tab').trigger('click');
    }

    //It will remove all query strings from url
    history.pushState('', document.title, window.location.pathname);


/*
    $('#pending_rest_table tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control" style="max-width:200px;" placeholder="Search '+title+'" />' );
    } );
*/ 
    // DataTable
    var pending_rest_table = $('#pending_rest_table').DataTable({
      columnDefs: [
          //for table global search, if we don't make these columns with view-profile/verify buttons non-searchable then, button texts will also be searched.
          { targets: [4,5,6], searchable: false },
          {targets: [4,5,6], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    pending_rest_table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );


    /*$('#verified_rest_table tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" class="form-control" style="max-width:200px;" placeholder="Search '+title+'" />' );
    } );*/
 
    // DataTable
    var verified_rest_table = $('#verified_rest_table').DataTable({
      columnDefs: [
          //for table global search, if we don't make these columns with view-profile/verify buttons non-searchable then, button texts will also be searched.
          { targets: [4,5,6], searchable: false },
          {targets: [4,5,6], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    verified_rest_table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );



  });
</script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
  var app = angular.module("MyApp", []);

  app.controller("MyController", ['$scope','$filter', '$http', function($scope,$filter,$http)
  {
    
    $scope.pending_restaurants = <?php echo json_encode($pending_restaurants); ?>;
    $scope.verified_restaurants = <?php echo json_encode($verified_restaurants); ?>;

    $scope.view_pending_rest_profile= function(restaurant_id){
      $scope.rest_object = $filter('filter')($scope.pending_restaurants, function (d) {return d.restaurant_id === restaurant_id;})[0];
    }

    $scope.view_verified_rest_profile= function(restaurant_id){
      $scope.rest_object = $filter('filter')($scope.verified_restaurants, function (d) {return d.restaurant_id === restaurant_id;})[0];
    }

    $scope.verify_restaurant=function(restaurant_id)
    {
      $http({
            method : "POST",
              url : "restaurant_verification_data.php",
              data:'verify_restaurant_id='+JSON.stringify(restaurant_id),
              headers:{'Content-Type': 'application/x-www-form-urlencoded'},

          }).then(function mySuccess(response) {
            if(response.data=='success')
            {
                //swal(title,description,type) from sweetalert.min.js
                swal("","Restaurant verified successfully!", "success")
              
                setTimeout(function(){window.location.href='restaurant_verification.php?show_restaurants=verified_restaurants';},1000);
            }          
        }
      );
    }

    $scope.unverify_restaurant=function(index,restaurant_id)
    {
      $http({
            method : "POST",
              url : "restaurant_verification_data.php",
              data:'unverify_restaurant_id='+JSON.stringify(restaurant_id),
              headers:{'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function mySuccess(response) {
            if(response.data=='success')
            {
                //swal(title,description,type) from sweetalert.min.js
                swal("","Restaurant unverified successfully!", "success");
                //after 1000ms of delay, redirect to given location
                setTimeout(function(){window.location.href='restaurant_verification.php?show_restaurants=pending_restaurants';},1000);
                  
            }
        }
      );
    }

    $scope.view_rest_menu=function(restaurant_id)
    {
      $http({
            method : "POST",
              url : "restaurant_view_menu_data.php",
              data:'view_menu_restaurant_id='+restaurant_id,
              headers:{'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function mySuccess(response) {
                $scope.menu_data=response.data;
                $('#menuModal').modal('show');
            }
      );

    }


  }]).directive('myRestaurant', function() {
    return {
      scope: {
        Menu: '=menu1'
      },
      templateUrl: 'restaurant_view_menu.php'
    };
  });

</script>
