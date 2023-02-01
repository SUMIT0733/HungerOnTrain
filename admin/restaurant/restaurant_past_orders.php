<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Restaurant Orders</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('rest_common_libraries.php'); ?>


  <!-- These libraries are used for showing order time in form of "time ago"-->
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-moment/0.10.1/angular-moment.min.js"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
  <?php 

  require_once('rest_navbar.php');
  $current_delivered_orders_list=$rest->fetch_current_delivered_orders();
  $current_rejected_orders_list=$rest->fetch_current_rejected_orders();

  ?>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_wrapper" ng-app="MyApp" ng-controller="MyController as main">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Restaurant Orders
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Restaurant Orders</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#orders_pane" data-toggle="tab" id="orders_tab">Orders</a></li>
              <li><a href="#order_details_pane" data-toggle="tab" id="order_details_tab">Order details</a></li>
            </ul>

            <div class="tab-content">
              

              <div class="active tab-pane" id="orders_pane">

                <ul class="nav nav-pills pull-right" >
                  <li class="active" style="border:1px solid #337ab7;"><a data-toggle="pill" href="#delivered_orders" style="padding-top: 4px;padding-bottom: 9px;">Delivered</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#rejected_orders" style="padding-top: 4px;padding-bottom: 9px;">Rejected</a></li>                </ul>
                <br/><br/>
                <div class="tab-content">
                  <div id="delivered_orders" class="tab-pane fade in active">
                    <br/>
                    <div class="well well-lg" style="border-radius:0px;box-shadow: 5px 6px 10px #888888;background-color: white;" ng-repeat="order in current_all_delivered_orders">

                      <div class="row" style="margin:-15px;font-size: 15px;"><div class="col-md-3"># Order ID: <b>{{order.order_id}}</b></div><div class="col-md-4 col-md-offset-5"><span class="glyphicon glyphicon-calendar"></span> Delivery Date/Time: <b>{{order.order_time}}</b></div>
                      </div>
                      <hr style="border-top:1px dashed #888888;">
                      <div class="row" style="font-size: 15px;">
                          <div class="col-md-6">
                            <div class="col-md-5">
                              <span class="glyphicon glyphicon-user"></span><b> {{order.end_customer_name}}</b><br/>
                              <span class="glyphicon glyphicon-earphone"></span><b> {{order.end_customer_contact}}</b><br/>
                            </div>
                            <div class="col-md-7">
                              <span class="glyphicon glyphicon-time" style="font-size:14px;"></span><b> <span am-time-ago="order.create_date"></span></b><br/>
                              <span class="glyphicon glyphicon-credit-card"></span><b> Rs. {{order.order_amount}} -</b> Paid Online<br/>
                              <span class="glyphicon glyphicon-map-marker"></span><b> {{order.delivery_address}}</b><br/>
                            </div>
                          </div>

                          <div class="col-md-3">
                            <button class="btn btn-warning" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)">View order details</button>
                          </div>
                      </div>
                      <!-- end row -->
                    </div>
                    <!-- end well -->
                  </div>
                  <div id="rejected_orders" class="tab-pane fade">
                    <br/>
                    <div class="well well-lg" style="border-radius:0px;box-shadow: 5px 6px 10px #888888;background-color: white;" ng-repeat="order in current_all_rejected_orders">

                      <div class="row" style="margin:-15px;font-size: 15px;"><div class="col-md-3"># Order ID: <b>{{order.order_id}}</b></div><div class="col-md-4 col-md-offset-5"><span class="glyphicon glyphicon-calendar"></span> Delivery Date/Time: <b>{{order.order_time}}</b></div>
                      </div>
                      <hr style="border-top:1px dashed #888888;">
                      <div class="row" style="font-size: 15px;">
                          <div class="col-md-6">
                            <div class="col-md-5">
                              <span class="glyphicon glyphicon-user"></span><b> {{order.end_customer_name}}</b><br/>
                              <span class="glyphicon glyphicon-earphone"></span><b> {{order.end_customer_contact}}</b><br/>
                            </div>
                            <div class="col-md-7">
                              <span class="glyphicon glyphicon-time" style="font-size:14px;"></span><b> <span am-time-ago="order.create_date"></span></b><br/>
                              <span class="glyphicon glyphicon-credit-card"></span><b> Rs. {{order.order_amount}} -</b> Paid Online<br/>
                              <span class="glyphicon glyphicon-map-marker"></span><b> {{order.delivery_address}}</b><br/>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <button class="btn btn-warning" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)">View order details</button>
                          </div>
                      </div>
                      <!-- end row -->
                    </div>
                    <!-- end well -->
                  </div>
                  
                </div>

                
              </div>
              <!-- /.tab-pane -->
<div class="tab-pane" id="order_details_pane">
                  <br/>

                  <div class="well well-lg" style="border-radius:0px;">
                  
                    <div class="row" style="margin:-15px;margin-top:-10px;font-size: 15px;box-shadow: 5px 6px 10px #888888;background-color: white;padding:5px;"><div class="col-md-4"># Order ID: <b>{{order_details.order_id}}</b></div><div class="col-md-4"># Payment ID: <b>{{order_details.payment_id}}</b></div><div class="col-md-4"><span class="glyphicon glyphicon-calendar"></span> Delivery Date/Time: <b>{{order_details.order_time}}</b></div>
                    </div>
                    <!-- end row -->
                    <br/><br/>

                    <div class="row" style="margin:-15px;font-size: 15px;box-shadow: 5px 6px 10px #888888;background-color: white;padding:5px;">
                          <span>Passenger Details</span>
                          <hr style="border-top:1px dashed #888888;margin-top:5px;margin-bottom:5px;">
                          
                          <div class="row">

                            <div class="col-md-5">
                              <span class="glyphicon glyphicon-user"></span><b> {{order_details.end_customer_name}}</b><br/>
                              <span class="glyphicon glyphicon-earphone"></span><b> {{order_details.end_customer_contact}}</b><br/>
                            </div>
                            <div class="col-md-7">
                              <span class="glyphicon glyphicon-time" style="font-size:14px;"></span><b> <span am-time-ago="order_details.create_date"></span></b><br/>
                              <span class="glyphicon glyphicon-credit-card"></span><b> Rs. {{order_details.order_amount}} -</b> Paid Online<br/>
                              <span class="glyphicon glyphicon-map-marker"></span><b> {{order_details.delivery_station}} STATION, {{order_details.delivery_address}}</b><br/>
                            </div>

                          </div>
                          <!-- end row -->

                      </div>
                      <!-- end row -->

                      <br/><br/>
                    <div class="row" style="margin:-15px;font-size: 15px;box-shadow: 5px 6px 10px #888888;background-color: white;padding:5px;">
                      
                      <span>Order Summary</span>
                      <hr style="border-top:1px dashed #888888;margin-top:5px;margin-bottom:5px;">
                      
                      <div class="table-responsive">          
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Food item</th>
                              <th>Units</th>
                              <th>Cuisine</th>
                              <th>Sub-total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr ng-repeat="order_item in order_details.order_summary">
                              <td>{{$index+1}}</td>
                              <td>{{order_item.food_item_name}}</td>
                              <td>{{order_item.unit}}</td>
                              <td>{{order_item.cuisine_name}}</td>
                              <td>Rs. {{order_item.subtotal}}</td>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td><b>Total:</b> Rs. {{order_details.order_amount}}</td>
                            </tr>
                          </tbody>
                        </table>
                        </div>

                    </div>
                    <!-- end row -->
                    <br/><br/>
                    <div class="row" style="margin:-15px;font-size: 15px;box-shadow: 5px 6px 10px #888888;background-color: white;padding:5px;margin-bottom:-5px;">
                      <span>Order instruction</span>
                      <hr style="border-top:1px dashed #888888;margin-top:5px;margin-bottom:5px;">

                      <div ng-if="order_details.order_instruction!=''">
                        <b>{{order_details.order_instruction}}</b>
                      </div>
                      
                    </div>
                    <!-- end row -->



                  </div>

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
  $(document).ready(function(){

    //to initialize second app in the document, we need to bootstrap it on document onload. first app in all files is 'notificationApp' located in rest_navbar
    angular.bootstrap(document.getElementById("content_wrapper"), ['MyApp']);


    $('#order_details_tab').hide();
    
    $('#orders_tab').click(function(){
      $('#order_details_tab').hide();
    });
  });
</script>
<script type="text/javascript">
  var app = angular.module('MyApp', ['angularMoment']);
  app.controller('MyController',  ['$scope','$filter', '$window', '$http', function($scope,$filter, $window, $http) {


    $scope.current_all_delivered_orders=<?php echo json_encode($current_delivered_orders_list); ?>;
    $scope.current_all_rejected_orders=<?php echo json_encode($current_rejected_orders_list); ?>;

    $scope.fetch_order_details=function(auto_order_id)
    {
      $http({
          method : "POST",
            url : "restaurant_orders_data.php",
            data: $.param({'action':'fetch_order_details','auto_order_id':auto_order_id}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {
          $scope.order_details=response.data;
          
          $('#order_details_tab').show();
          $('#order_details_tab').trigger('click');
          window.scrollTo(0,0);

        }
      );

    }    

 
      
  
  }]);
    

</script>