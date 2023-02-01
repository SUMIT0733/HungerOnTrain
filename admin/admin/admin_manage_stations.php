<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Manage Stations</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('admin_common_libraries.php'); ?>
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js"></script>
  <style type="text/css">
    #deactivated_stations_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #activated_stations_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }

    .modal_labels
    {
       color:white;
    }
  </style>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.18.10/slimselect.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.18.10/slimselect.min.css" rel="stylesheet"></link>

  <!-- For Bootstrap toggle switch -->
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper" ng-app="MyApp" ng-controller="MyController">
  <?php 

  require_once('admin_navbar.php');

  $activated_cities=$admin->get_activated_cities();
  $activated_stations=$admin->get_activated_stations();
  $deactivated_stations=$admin->get_deactivated_stations();
 
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Stations
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Stations</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">

          <div class="nav-tabs-custom">
            
            <ul class="nav nav-tabs" id="nav-tabs">
              
              <li><a href="#activated" id="activated_stations_tab" data-toggle="tab">Activated</a></li>
              <li><a href="#deactivated" id="deactivated_stations_tab"  data-toggle="tab">Deactivated</a></li>
              <li><a href="#add" id="Add_station_tab" data-toggle="tab">Add</a></li>
            </ul>
            
            <div class="tab-content">

                <div class="tab-pane" id="activated">
                  <br/>
                  <div class="table-responsive">
                    
                    <table id="activated_stations_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th style="max-width:50px;">ID</th>
                              <th>Station Name</th>
                              <th>City Name</th>
                              <th>Deactivate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="station in activated_stations">
                                <td style="max-width:50px;">{{station.station_id}}</td>
                                <td>{{station.station_name}}</td>
                                <td>{{station.city_name}}</td>
                                <td><button class="btn btn-success" ng-click="deactivate_station(station.station_id)">Deactivate</button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" style="max-width:90px;" placeholder="Search ID" /></th>
                              <th><input type="text" class="form-control" style="max-width:200px;" placeholder="Search Station Name" /></th>
                              <th><input type="text" class="form-control" style="max-width:200px;" placeholder="Search City Name" /></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                    </table>
                  </div>

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="deactivated">
                  <br/>

                  <div class="table-responsive">
                    
                    <table id="deactivated_stations_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th style="max-width:50px;">ID</th>
                              <th>Station Name</th>
                              <th>City Name</th>
                              <th>Activate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="station in deactivated_stations">
                                <td style="max-width:50px;">{{station.station_id}}</td>
                                <td>{{station.station_name}}</td>
                                <td>{{station.city_name}}</td>
                                <td><button class="btn btn-success" ng-click="activate_station(station.station_id)">Activate</button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>

                              <th><input type="text" class="form-control" style="max-width:90px;" placeholder="Search ID" /></th>
                              <th><input type="text" class="form-control" style="max-width:200px;" placeholder="Search Station Name" /></th>
                              <th><input type="text" class="form-control" style="max-width:200px;" placeholder="Search City Name" /></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                    </table>
                  </div>

                </div>
                <!-- /.tab-pane -->


                <div class="tab-pane" id="add">
                    <br/>
                    <div class="row">

                      <form class="form-horizontal col-md-10">

                        <div class="form-group">
                          
                          <label for="city" class="col-sm-3 control-label">City</label>

                          <div class="col-sm-4">
                            <select id="city" name="city" ng-model="city" required>
                              <option ng-repeat="city in activated_cities" ng-value="city['city_id']">{{city['city_name']}}</option>
                            </select>
                          </div>

                        </div>

                        <div class="form-group">
                          
                          <label for="city" class="col-sm-3 control-label">Search Station</label>

                          <div class="col-sm-3">
                            <input type="textbox" class="form-control" name="search_station" ng-model="search_station" ng-change="fetch_stations(search_station)" ng-disabled="response_in_process" placeholder="Search station">
                            </select>
                          </div>

                          <label for="station" class="col-sm-2 control-label">Select Station</label>

                          <div class="col-sm-4">
                            <select id="station" name="station" ng-model="station" required>
                              <option ng-repeat="station in all_stations" ng-value="station['name']">{{station['name']}}</option>
                            </select>
                          </div>

                        </div>


                        <div class="form-group">
                          <style>
                            .toggle.android { border-radius: 0px;}
                            .toggle.android .toggle-handle { border-radius: 0px; }
                          </style>
                          <label for="status" class="col-sm-3 control-label">Status</label>

                          <div class="col-sm-4">
                            <input type="checkbox" id="status" name="status" data-toggle="toggle" data-width="105" data-on="Activated" data-off="Deactivated" data-onstyle="success" data-offstyle="danger" data-style="android" checked>
                          </div>
                        </div>

                        <div class="form-group"  style="margin-bottom: 0px;">
                          <div class="errordiv col-sm-6 col-sm-offset-3">
                            <div class="alert alert-danger">
                              <span id="error"></span>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <br/>
                          <div class="col-sm-2 col-sm-offset-6">
                            <button type="button" id="submit" class="btn btn-primary btn-block">Add</button>
                          </div>
                        </div>
                        
                      </form>
                    </div>
                    <!-- End row -->

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
    
  </div>
  <!-- End content-wrapper -->


<?php require_once('admin_control_sidebar.php');?>
<?php require_once('admin_footer.php');?>

<!-- Sweet alerts -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script type="text/javascript">
  $(document).ready(function() {
      $('.errordiv').hide();
      initialize_SlimSelect('city');
      initialize_SlimSelect('station');

      $('#status').bootstrapToggle();

      $('#submit').click(function(){
          var station_name=$('#station').val();
          var city_id=$('#city').val();
          var status=$('#status').prop('checked');
          //angularjs ng-model automatically adds '? undefined:undefined ?' option as first option to prevent automatic selection. city_id=='' or city_id==null returns false, because always first option that is selected is '? undefined:undefined ?'. but here with slimSelect.js, sometimes if no results found inside selectbox, then it sets null as selectbox's value. 
          if(city_id=='? undefined:undefined ?' || city_id==null)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please select city');
            return false;
          }

          if(station_name=='? undefined:undefined ?' || station_name==null)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please select station');
            return false;
          }

          if(status==null)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please select status');
            return false;
          }

          $('.errordiv').hide();
          
          $.ajax({
            url:'admin_manage_stations_data.php',
            method:'POST',
            data:{'action':'add_station','city_id':city_id,'station_name':station_name,'status':status},
            success: function(result)
            {
              if(result=='success')
              {
                //swal(title,description,type) from sweetalert.min.js
                if(status==true){
                  swal("","Station added to activated list", "success")
                  setTimeout(function(){window.location.href='admin_manage_stations.php?show_stations=activated_stations';},1000);
                }
                else
                {
                  swal("","Station added to deactivated list", "success")
                  setTimeout(function(){window.location.href='admin_manage_stations.php?show_stations=deactivated_stations';},1000);
                }
              }
              else{
                $('.errordiv').fadeIn();
                $('#error').html(result);
              }
            }
          });
 
      });

      function initialize_SlimSelect(id){
        new SlimSelect({
          select:  document.querySelector('#'+id+''),
          searchFilter: (option, search) => {
            return option.text.substr(0, search.length).toLowerCase() == search.toLowerCase()
          }
        })
      }


    //It will fetch all query strings from url
    const urlParams = new URLSearchParams(window.location.search);
    const show_stations = urlParams.get('show_stations');
    
    if(show_stations=='deactivated_stations')
    {
      $('#deactivated_stations_tab').trigger('click');
    }
    else
    {
      $('#activated_stations_tab').trigger('click');
    }

    //It will remove all query strings from url
    history.pushState('', document.title, window.location.pathname);

 
    // DataTable
    var deactivated_stations_table = $('#deactivated_stations_table').DataTable({
      columnDefs: [
          {targets: [3], searchable: false},
          {targets: [3], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    deactivated_stations_table.columns().every( function () {
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
    var activated_stations_table = $('#activated_stations_table').DataTable({
      columnDefs: [
          {targets: [3], searchable: false},
          {targets: [3], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    activated_stations_table.columns().every( function () {
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

<!-- For Bootstrap toggle switch -->
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


<script type="text/javascript">
  var app = angular.module("MyApp", []);

  app.controller("MyController", ['$scope','$filter', '$http', function($scope,$filter,$http) {
    
  $scope.activated_stations = <?php echo json_encode($activated_stations); ?>;
  $scope.deactivated_stations = <?php echo json_encode($deactivated_stations); ?>;
  $scope.activated_cities = <?php echo json_encode($activated_cities); ?>;
  $scope.all_stations={};
  $scope.response_stations={};
  $scope.response_in_process=false;

  $scope.deactivate_station=function(station_id)
  {
      $http({
          method : "POST",
            url : "admin_manage_stations_data.php",
            data:'deactivate_station_id='+JSON.stringify(station_id),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},

        }).then(function mySuccess(response) {
          if(response.data=='success')
          {
              //swal(title,description,type) from sweetalert.min.js
              swal("","Station added to deactivated list", "success")
            
              setTimeout(function(){window.location.href='admin_manage_stations.php?show_stations=deactivated_stations';},1000);
          }          
      }
    );
  }

  $scope.activate_station=function(station_id)
  {
      $http({
          method : "POST",
            url : "admin_manage_stations_data.php",
            data:'activate_station_id='+JSON.stringify(station_id),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},

      }).then(function mySuccess(response) {
          if(response.data=='success')
          {
              //swal(title,description,type) from sweetalert.min.js
              swal("","Station added to activated list", "success")
            
              setTimeout(function(){window.location.href='admin_manage_stations.php?show_stations=activated_stations';},1000);
          }          
      });
  }

  $scope.fetch_stations=function(station_hint){
    $scope.response_in_process=true;
    var api_url="https://api.railwayapi.com/v2/suggest-station/name/"+station_hint+"/apikey/evndob9pfc/";
    $http({
          method : "GET",
            url : api_url,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},

      }).then(function mySuccess(response) {
          $scope.response_stations=response.data;
          $scope.all_stations=$scope.response_stations.stations;
          $scope.response_in_process=false;
      });
  }

  }]);
</script>
