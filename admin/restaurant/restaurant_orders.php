<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Restaurant Orders</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('rest_common_libraries.php'); ?>

  <style type="text/css">
    table {
      border: 1px solid black;
    }
  </style>

</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
  <?php 

  require_once('rest_navbar.php');
  $current_pending_orders_list=$rest->fetch_current_pending_orders();
  $current_accepted_orders_list=$rest->fetch_current_accepted_orders();
  $current_prepared_orders_list=$rest->fetch_current_prepared_orders();
  $current_picked_orders_list=$rest->fetch_current_picked_orders();
        
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
                  <li class="active" style="border:1px solid #337ab7;"><a data-toggle="pill" href="#pending_orders" style="padding-top: 4px;padding-bottom: 9px;">Pending</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#accepted_orders" style="padding-top: 4px;padding-bottom: 9px;">Confirmed</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#prepared_orders" style="padding-top: 4px;padding-bottom: 9px;">Prepared</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#picked_orders" style="padding-top: 4px;padding-bottom: 9px;">Picked up</a></li>
                </ul>
                <br/><br/>
                <div class="tab-content">
                  <div id="pending_orders" class="tab-pane fade in active">
                    <br/>
                    <div class="well well-lg" style="border-radius:0px;box-shadow: 5px 6px 10px #888888;background-color: white;" ng-repeat="order in current_all_pending_orders">

                      <div class="row" style="margin:-15px;font-size: 15px;">
                      
                        <div class="col-md-4"># Order ID: <b>{{order.order_id}}</b></div>
                        
                        <div class="col-md-4"><span class="glyphicon glyphicon-user"></span> Delivery Person: 
                          <b>
                            <span ng-if="order.delivery_person_id == 0" style="color:red;">
                              Not assigned
                            </span>
                            <span ng-if="order.delivery_person_id != 0" style="color:green;">
                              Assigned
                            </span>
                          </b>
                        </div>

                        <div class="col-md-4"><span class="glyphicon glyphicon-calendar"></span> Delivery Date/Time: <b>{{order.order_time}}</b>
                        </div>
                        
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
                              <span class="glyphicon glyphicon-map-marker"></span><b> {{order.delivery_station}} STATION, {{order.delivery_address}}</b><br/>
                            </div>
                          </div>
                          <div class="col-md-1">
                            <button class="btn btn-success" style="margin:5px;" ng-click="accept_order($index,order.auto_order_id,order.order_id)">Accept</button>
                          </div>
                          <div class="col-md-1">
                            <button class="btn btn-danger" style="margin:5px;" ng-click="reject_order($index,order.auto_order_id)">Reject</button>
                          </div>
                          <div class="col-md-3">
                            <button class="btn btn-warning" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)">View order details</button>
                          </div>
                      </div>
                      <!-- end row -->
                    </div>
                    <!-- end well -->
                  </div>
                  <div id="accepted_orders" class="tab-pane fade">
                    <br/>
                    <div class="well well-lg" style="border-radius:0px;box-shadow: 5px 6px 10px #888888;background-color: white;" ng-repeat="order in current_all_accepted_orders">

                      <div class="row" style="margin:-15px;font-size: 15px;">
                      
                        <div class="col-md-4"># Order ID: <b>{{order.order_id}}</b></div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-user"></span> Delivery Person: 
                          <b>
                            <span ng-if="order.delivery_person_id == 0" style="color:red;">Not assigned</span>
                            <span ng-if="order.delivery_person_id != 0" style="color:green;">Assigned</span>
                          </b>
                        </div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-calendar"></span> Delivery Date/Time: <b>{{order.order_time}}</b>
                        </div>
                        
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
                          <div class="col-md-2">
                            <button class="btn btn-success" style="margin:5px;" ng-click="mark_as_prepared($index,order.auto_order_id)">Mark as prepared</button>
                          </div>
                          <div class="col-md-3">
                            <button class="btn btn-warning" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)">View order details</button>
                          </div>
                      </div>
                      <!-- end row -->
                    </div>
                    <!-- end well -->
                  </div>
                  <div id="prepared_orders" class="tab-pane fade">
                    <br/>
                    <div class="well well-lg" style="border-radius:0px;box-shadow: 5px 6px 10px #888888;background-color: white;" ng-repeat="order in current_all_prepared_orders">

                      <div class="row" style="margin:-15px;font-size: 15px;">
                      
                        <div class="col-md-4"># Order ID: <b>{{order.order_id}}</b></div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-user"></span> Delivery Person: 
                          <b>
                            <span ng-if="order.delivery_person_id == 0" style="color:red;">Not assigned</span>
                            <span ng-if="order.delivery_person_id != 0" style="color:green;">Assigned</span>
                          </b>
                        </div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-calendar"></span> Delivery Date/Time: <b>{{order.order_time}}</b>
                        </div>
                        
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
                          <div class="col-md-2">
                            <button class="btn btn-success" style="margin:5px;" ng-click="mark_as_picked($index,order.auto_order_id)">Mark as picked up</button>
                          </div>
                          <div class="col-md-3">
                            <button class="btn btn-warning" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)">View order details</button>
                          </div>
                      </div>
                      <!-- end row -->

                    </div>
                    <!-- end well -->
                  </div>

                  <div id="picked_orders" class="tab-pane fade">
                    <br/>
                    <div class="well well-lg" style="border-radius:0px;box-shadow: 5px 6px 10px #888888;background-color: white;" ng-repeat="order in current_all_picked_orders">

                      <div class="row" style="margin:-15px;font-size: 15px;">
                      
                        <div class="col-md-4"># Order ID: <b>{{order.order_id}}</b></div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-user"></span> Delivery Person: 
                          <b>
                            <span ng-if="order.delivery_person_id == 0" style="color:red;">Not assigned</span>
                            <span ng-if="order.delivery_person_id != 0" style="color:green;">Assigned</span>
                          </b>
                        </div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-calendar"></span> Delivery Date/Time: <b>{{order.order_time}}</b>
                        </div>
                        
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
                    <div class="row" style="margin:-15px;margin-top:-10px;font-size: 15px;box-shadow: 5px 6px 10px #888888;background-color: white;padding:5px;"><div class="col-md-4"># Order ID: <b>{{order_details.order_id}}</b></div><div class="col-md-4  col-md-offset-3"><span class="glyphicon glyphicon-calendar"></span> Delivery Date/Time: <b>{{order_details.order_time}}</b></div>
                    </div>
                    <!-- end row -->
                    <br/><br/>
                    
                    <div ng-if="order_details.delivery_person_id!=0" class="row" style="margin:-15px;font-size: 15px;box-shadow: 5px 6px 10px #888888;background-color: white;padding:5px;">
                        <span>Delivery Person Details</span>
                        <hr style="border-top:1px dashed #888888;margin-top:5px;margin-bottom:5px;">
                        
                        <div class="row">

                          <div class="col-md-4">
                            <span class="glyphicon glyphicon-user"></span><b> {{order_details.delivery_person_details[0].name}}</b>
                          </div>
                          <div class="col-md-4">
                            <span class="glyphicon glyphicon-earphone"></span><b> {{order_details.delivery_person_details[0].contact_no}}</b><br/>
                          </div>
                          <div class="col-md-4">
                            <span class="glyphicon glyphicon-phone"></span><b> Verification OTP: {{order_details.delivery_person_details[0].otp}}</b><br/>
                          </div>

                        </div>
                        <!-- end row -->

                    </div>
                    <!-- end row -->
                    <div ng-if="order_details.delivery_person_id!=0"><br/><br/></div>

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
  //angularMoment is used by angular-moment.min.js located in rest_common_libraries
  var app = angular.module('MyApp', ['angularMoment']);
  app.controller('MyController',  ['$scope','$filter', '$window', '$http', function($scope,$filter, $window, $http) {

    $scope.current_all_pending_orders=<?php echo json_encode($current_pending_orders_list); ?>;
    $scope.current_all_accepted_orders=<?php echo json_encode($current_accepted_orders_list); ?>;
    $scope.current_all_prepared_orders=<?php echo json_encode($current_prepared_orders_list); ?>;
    $scope.current_all_picked_orders=<?php echo json_encode($current_picked_orders_list); ?>;
    
    $scope.order_details={};

    $scope.get_new_orders_based_on_notif=function(new_orders_ids)
    {
      $http({
          method : "POST",
            url : "restaurant_orders_data.php",
            data: $.param({'action':'fetch_multiple_orders_basic_details','orders_ids_arr':new_orders_ids}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {
          
          angular.forEach(response.data, function(item){
            $scope.current_all_pending_orders.splice(0, 0, item);
          });
      
        }
      );
    }

    $scope.remove_delivered_orders_based_on_notif=function(del_orders_ids)
    {
          angular.forEach(del_orders_ids, function(del_orders_id){
            
            var index = $scope.current_all_picked_orders.findIndex(x=>x.order_id == del_orders_id);
            $scope.current_all_picked_orders.splice(index, 1);

          });
      
    }

    $scope.delivery_per_assigned_based_on_notif=function(delivery_per_assigned_order_ids)
    {
      //this function updates delivery person assigned status in orders based on notifications
      angular.forEach(delivery_per_assigned_order_ids, function(order_id){

        var index = $scope.current_all_pending_orders.findIndex(x=>x.order_id == order_id);
        if(index!=-1)
        {
          $scope.current_all_pending_orders[index].delivery_person_id=999999;
        }
        else{

          index = $scope.current_all_accepted_orders.findIndex(x=>x.order_id == order_id);
          if(index!=-1)
          {
            $scope.current_all_accepted_orders[index].delivery_person_id=999999;
          }
          else
          {
            index = $scope.current_all_prepared_orders.findIndex(x=>x.order_id == order_id);
            $scope.current_all_prepared_orders[index].delivery_person_id=999999;

          }
        }

        
      });

    }

    $scope.send_notification_to_passenger=function(auto_order_id,message)
    {
      $http({
          method : "POST",
            url : "send_passenger_notification.php",
            data: $.param({'action':'send_notification_to_passenger','auto_order_id':auto_order_id,'message':message}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {

        });
    }

    $scope.send_notification_to_del_person=function(auto_order_id,message)
    {
      $http({
          method : "POST",
            url : "send_del_person_notification.php",
            data: $.param({'action':'send_notification_to_del_person','auto_order_id':auto_order_id,'message':message}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {

        });
    }
    


    $scope.accept_order=function(index,auto_order_id,order_id)
    {


      $http({
          method : "POST",
            url : "restaurant_orders_data.php",
            data: $.param({'action':'accept_order','auto_order_id':auto_order_id}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {
          if(response.data=='success')
          {
                //swal(title,description,type) from sweetalert.min.js
                swal("","Order Accepted", "success");
                
                $scope.current_all_accepted_orders.splice(0, 0, $scope.current_all_pending_orders[index]);
      
                $scope.current_all_pending_orders.splice(index, 1);


                //SEND NOTIFICATION TO PASSENGER
                $scope.send_notification_to_passenger(auto_order_id,'You order has been accepted');

                //Change status of order as accepted inside notification cart
                
                var notif_scope = angular.element($("#notifications")).scope();

                //This method can be used to perform operations on scope variables outside angularjs scope
                notif_scope.$apply(function(){

                  var notif_index=notif_scope.current_all_notifications.findIndex( record => record.order_id == order_id);
                  notif_scope.current_all_notifications[notif_index].notification_type=1;
                });


                $http({
                  method : "POST",
                    url : "restaurant_orders_data.php",
                    data: $.param({'action':'assign_delivery_person','order_id':order_id}),
                    headers:{'Content-Type': 'application/x-www-form-urlencoded'},
                }).then(function mySuccess(response) {
                    if(response.data=='success')
                    {
                    //SEND NOTIFICATION TO PASSENGER
                    $scope.send_notification_to_passenger(auto_order_id,'Delivery person assigned');

                    //SEND NOTIFICATION TO delivery person
                    //actual message is sent from backend file
                    $scope.send_notification_to_del_person(auto_order_id,'New order assigned');
                    }                   
                });

          }
          else{
            alert("Something went wrong! Please try again later!");
          }
          
        }
      );
    }
    $scope.reject_order=function(index,auto_order_id)
    {
      $http({
          method : "POST",
            url : "restaurant_orders_data.php",
            data: $.param({'action':'reject_order','auto_order_id':auto_order_id}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {
          if(response.data=='success')
          {
             //swal(title,description,type) from sweetalert.min.js
                swal("","Order Rejected", "success")
      
                $scope.current_all_pending_orders.splice(index, 1); 

                $scope.send_notification_to_passenger(auto_order_id,'You order is rejected');

          }
          else{
            alert("Something went wrong! Please try again later!");
          }
          
        }
      );
    }

    $scope.fetch_order_details=function(auto_order_id)
    {

      $http({
          method : "POST",
            url : "restaurant_orders_data.php",
            data: $.param({'action':'fetch_order_details','auto_order_id':auto_order_id}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {
          $scope.order_details=response.data;
          //console.log($scope.order_details);
          $('#order_details_tab').show();
          $('#order_details_tab').trigger('click');
          window.scrollTo(0,0);

        }
      );
    }    

    $scope.mark_as_prepared=function(index,auto_order_id)
    {
      $http({
          method : "POST",
            url : "restaurant_orders_data.php",
            data: $.param({'action':'mark_as_prepared','auto_order_id':auto_order_id}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {
          if(response.data=='success')
          {
             //swal(title,description,type) from sweetalert.min.js
                swal("","Order marked as prepared", "success")
                $scope.current_all_prepared_orders.splice(0, 0, $scope.current_all_accepted_orders[index]);
      
                $scope.current_all_accepted_orders.splice(index, 1);
              
          }
          else{
            alert("Something went wrong! Please try again later!");
          }
          
        }
      );
    }

    $scope.mark_as_picked=function(index,auto_order_id)
    {
      $http({
          method : "POST",
            url : "restaurant_orders_data.php",
            data: $.param({'action':'mark_as_picked','auto_order_id':auto_order_id}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {
          if(response.data=='success')
          {
             //swal(title,description,type) from sweetalert.min.js
                swal("","Order marked as picked up", "success")
                $scope.current_all_picked_orders.splice(0, 0, $scope.current_all_prepared_orders[index]);
      
                $scope.current_all_prepared_orders.splice(index, 1);
                
                $scope.send_notification_to_passenger(auto_order_id,'You food is on the way');

              
          }
          else{
            alert("Something went wrong! Please try again later!");
          }
          
        }
      );
    }
     
  }]);



    

</script>