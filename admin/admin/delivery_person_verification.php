<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Delivery Person Verification</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('admin_common_libraries.php'); ?>
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js"></script>
  <style type="text/css">
    #pending_del_person_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #verified_del_person_table tr td
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
  
  $pending_delivery_persons=$admin->get_pending_delivery_persons();
  $verified_delivery_persons=$admin->get_verified_delivery_persons();
 
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Delivery Persons Verification
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Delivery Persons Verification</li>
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
                    
                    <table id="pending_del_person_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Name</th>
                              <th style="max-width:100px;">City</th>
                              <th style="max-width:100px;">State</th>
                              <th>Email</th>
                              <th>View Profile</th>
                              <th>Verify</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="delivery_person in pending_delivery_persons">
                                <td>{{delivery_person.name}}</td>
                                <td style="max-width:100px;">{{delivery_person.city_name}}</td>
                                <td style="max-width:100px;">{{delivery_person.state_name}}</td>
                                <td>{{delivery_person.email}}</td>
                                <td><button class="btn btn-primary" ng-click="view_pending_del_person_profile(delivery_person.delivery_person_id);" data-toggle="modal" data-target="#myModal">View Profile</button></td>
                                <td><button class="btn btn-success" ng-click="verify_delivery_person(delivery_person.delivery_person_id)">Verify</button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search Restaurant Name" style="max-width:200px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search City" /></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search State" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search Email" /></th>
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
                    
                    <table id="verified_del_person_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Name</th>
                              <th style="max-width:100px;">City</th>
                              <th style="max-width:100px;">State</th>
                              <th>Email</th>
                              <th>View Profile</th>
                              <th>Unverify</th>
                            </tr>
                        <tbody>
                        </thead>
                            <tr ng-repeat="delivery_person in verified_delivery_persons">
                                <td>{{delivery_person.name}}</td>
                                <td style="max-width:100px;">{{delivery_person.city_name}}</td>
                                <td style="max-width:100px;">{{delivery_person.state_name}}</td>
                                <td>{{delivery_person.email}}</td>
                                <td><button class="btn btn-primary" ng-click="view_verified_del_person_profile(delivery_person.delivery_person_id);" data-toggle="modal" data-target="#myModal">View Profile</button></td>
                                <td><button class="btn btn-success" ng-click="unverify_delivery_person($index,delivery_person.delivery_person_id)">Unverify</button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search Restaurant Name" style="max-width:200px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search City" /></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search State" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search Email" /></th>
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
            <h4 class="modal-title">Delivery Person Details</h4>
          </div>
          <div class="modal-body" style="background-color: #0a1f27;">
            <form class="form-horizontal">
                  
<!--                   <div class="form-group"> 
                    <div class="col-md-12">
                      <img class="profile-user-img img-responsive img-circle" src="{{delivery_person_object.profile_url}}" style="margin: 0 auto; width: 200px; padding: 3px; border: 2px solid #ccc; border-radius:3%;" alt="Profile picture">
                    </div>
                  </div>
                  <br/> -->
                  <!-- <hr style="border-color:white;">
 -->
                  <div class="form-group">
                    <label for="delivery_person_name" class="col-sm-3 control-label modal_labels">Name</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="delivery_person_name" name="delivery_person_name" value="{{delivery_person_object.name}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email" class="col-sm-3 control-label modal_labels">Email</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="email" name="email" value="{{delivery_person_object.email}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="contact_no" class="col-sm-3 control-label modal_labels">Contact</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{delivery_person_object.contact_no}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>
                  
            
                  <div class="form-group">

                    <label for="state" class="col-sm-3 control-label modal_labels">State</label>

                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="state" name="state" value="{{delivery_person_object.state_name}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>

                    <label for="city" class="col-sm-1 control-label modal_labels">City</label>

                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="city" name="city" value="{{delivery_person_object.city_name}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>

                  </div>

                  
                  <div class="form-group">
                    <label for="account_no" class="col-sm-3 control-label modal_labels">Account no</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="account_no" name="account_no"  value="{{delivery_person_object.account_no}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="IFSC_code" class="col-sm-3 control-label modal_labels">IFSC code</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="IFSC_code" name="IFSC_code"  value="{{delivery_person_object.IFSC_code}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="register_at" class="col-sm-3 control-label modal_labels">Registered At</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="register_at" name="register_at" value="{{delivery_person_object.created_at}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>
                  </div>
                  
                  <div class="form-group">

                    <label for="view_full_profile" class="col-sm-3 control-label modal_labels">View full profile</label>

                    <div class="col-sm-2">
                      <a class="btn btn-primary" id="view_full_profile" name="view_full_profile" href="{{delivery_person_object.profile_url}}" target="_blank">Profile</a>
                    </div>

                    <label for="view_document" class="col-sm-4 control-label modal_labels">View proof document</label>
                    <div class="col-sm-3">
                      <a class="btn btn-warning" id="view_document" name="view_document" href="{{delivery_person_object.proof_url}}" target="_blank">Document</a>
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
    const show_tab = urlParams.get('show_delivery_persons');
    
    if(show_tab=='verified_delivery_persons')
    {
      $('#verified_tab').trigger('click');
    }
    else
    {
      $('#pending_tab').trigger('click');
    }

    //It will remove all query strings from url
    history.pushState('', document.title, window.location.pathname);


    // DataTable
    var pending_del_person_table = $('#pending_del_person_table').DataTable({
      columnDefs: [
          //for table global search, if we don't make these columns with view-profile/verify buttons non-searchable then, button texts will also be searched.
          { targets: [4,5], searchable: false },
          {targets: [4,5], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    pending_del_person_table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

 
    // DataTable
    var verified_del_person_table = $('#verified_del_person_table').DataTable({
      columnDefs: [
          //for table global search, if we don't make these columns with view-profile/verify buttons non-searchable then, button texts will also be searched.
          { targets: [4,5], searchable: false },
          {targets: [4,5], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    verified_del_person_table.columns().every( function () {
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
    
    $scope.pending_delivery_persons = <?php echo json_encode($pending_delivery_persons); ?>;
    $scope.verified_delivery_persons = <?php echo json_encode($verified_delivery_persons); ?>;

    $scope.view_pending_del_person_profile= function(delivery_person_id){
      $scope.delivery_person_object = $filter('filter')($scope.pending_delivery_persons, function (d) {return d.delivery_person_id === delivery_person_id;})[0];
    }

    $scope.view_verified_del_person_profile= function(delivery_person_id){
      $scope.delivery_person_object = $filter('filter')($scope.verified_delivery_persons, function (d) {return d.delivery_person_id === delivery_person_id;})[0];
    }

    $scope.verify_delivery_person=function(delivery_person_id)
    {

      $http({
            method : "POST",
              url : "delivery_person_verification_data.php",
              data:'verify_delivery_person_id='+JSON.stringify(delivery_person_id),
              headers:{'Content-Type': 'application/x-www-form-urlencoded'},

          }).then(function mySuccess(response) {
            if(response.data=='success')
            {
                //swal(title,description,type) from sweetalert.min.js
                swal("","Delivery person verified successfully!", "success")
              
                setTimeout(function(){window.location.href='delivery_person_verification.php?show_delivery_persons=verified_delivery_persons';},1000);
            }          
        }
      );
    }

    $scope.unverify_delivery_person=function(index,delivery_person_id)
    {

      $http({
            method : "POST",
              url : "delivery_person_verification_data.php",
              data:'unverify_delivery_person_id='+JSON.stringify(delivery_person_id),
              headers:{'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function mySuccess(response) {
            if(response.data=='success')
            {
                //swal(title,description,type) from sweetalert.min.js
                swal("","Delivery person unverified successfully!", "success");
                //after 1000ms of delay, redirect to given location
                setTimeout(function(){window.location.href='delivery_person_verification.php?show_delivery_persons=pending_delivery_persons';},1000);
                  
            }
        }
      );
    }


  }]);

</script>
