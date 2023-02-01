<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Restaurant Orders</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('admin_common_libraries.php'); ?>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js"></script>


  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-moment/0.10.1/angular-moment.min.js"></script>













<!--   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-7rM2gy3d-0p07nv__onmDi9L4hok1pU"></script>
 -->





















  <style type="text/css">
    #pending_orders_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #accepted_orders_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #prepared_orders_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #picked_orders_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #delivered_orders_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #rejected_orders_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
  </style>


</head>

<body class="hold-transition skin-blue sidebar-mini">


<div class="wrapper" ng-app="MyApp" ng-controller="MyController" id="wrapper">
  <?php 

  require_once('admin_navbar.php');
  $current_pending_orders_list=$admin->fetch_current_pending_orders();
  $current_accepted_orders_list=$admin->fetch_current_accepted_orders();
  $current_prepared_orders_list=$admin->fetch_current_prepared_orders();
  $current_picked_orders_list=$admin->fetch_current_picked_orders();
  $current_delivered_orders_list=$admin->fetch_current_delivered_orders();
  $current_rejected_orders_list=$admin->fetch_current_rejected_orders();     
  ?>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
              <li><a href="#track_order_pane" data-toggle="tab" id="track_order_tab">Track Order</a></li>
            </ul>

            <div class="tab-content">
              
              <div class="active tab-pane" id="orders_pane">
                <ul class="nav nav-pills pull-right" >
                  <li class="active" style="border:1px solid #337ab7;"><a data-toggle="pill" href="#pending_orders_tab" style="padding-top: 4px;padding-bottom: 9px;">Pending</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#accepted_orders_tab" style="padding-top: 4px;padding-bottom: 9px;">Confirmed</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#prepared_orders_tab" style="padding-top: 4px;padding-bottom: 9px;">Prepared</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#picked_orders_tab" style="padding-top: 4px;padding-bottom: 9px;">Picked up</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#delivered_orders_tab" style="padding-top: 4px;padding-bottom: 9px;">Delivered</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#rejected_orders_tab" style="padding-top: 4px;padding-bottom: 9px;">Rejected</a></li>
                </ul>
                <br/><br/><br/>
                <div class="tab-content">

                  <div id="pending_orders_tab" class="tab-pane fade in active">

                    <div class="table-responsive">

                      <table id="pending_orders_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Delivery Address</th>
                              <th>Delivery Time</th>
                              <th>Order Time</th>
                              <th>View Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in current_all_pending_orders">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.delivery_address}}</td>
                                <td style="max-width:100px;">{{order.order_time}}</td>
                                <td>{{order.create_date}}</td>
                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search delivery address" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search delivery time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                  
                  </div>


                  <div id="accepted_orders_tab" class="tab-pane fade">
                    
                    <div class="table-responsive">

                      <table id="accepted_orders_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Delivery Address</th>
                              <th>Delivery Time</th>
                              <th>Order Time</th>
                              <th>View Order</th>
                              <th>Track Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in current_all_accepted_orders">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.delivery_address}}</td>
                                <td style="max-width:100px;">{{order.order_time}}</td>
                                <td>{{order.create_date}}</td>
                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                                <td><button class="btn btn-warning" style="margin:5px;" ng-click="track_order(order.auto_order_id)"><span class="glyphicon glyphicon-map-marker"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search delivery address" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search delivery time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>

                              <th style="display: none"></th>
                              <th style="display: none;"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>

                  </div>

                  <div id="prepared_orders_tab" class="tab-pane fade">
                    
                    <div class="table-responsive">

                      <table id="prepared_orders_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Delivery Address</th>
                              <th>Delivery Time</th>
                              <th>Order Time</th>
                              <th>View Order</th>
                              <th>Track Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in current_all_prepared_orders">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.delivery_address}}</td>
                                <td style="max-width:100px;">{{order.order_time}}</td>
                                <td>{{order.create_date}}</td>
                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                                <td><button class="btn btn-warning" style="margin:5px;" ng-click="track_order(order.auto_order_id)"><span class="glyphicon glyphicon-map-marker"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search delivery address" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search delivery time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th style="display: none"></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>


                  <div id="picked_orders_tab" class="tab-pane fade">
                    
                    <div class="table-responsive">

                      <table id="picked_orders_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Delivery Address</th>
                              <th>Delivery Time</th>
                              <th>Order Time</th>
                              <th>View Order</th>
                              <th>Track Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in current_all_picked_orders">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.delivery_address}}</td>
                                <td style="max-width:100px;">{{order.order_time}}</td>
                                <td>{{order.create_date}}</td>
                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                                <td><button class="btn btn-warning" style="margin:5px;" ng-click="track_order(order.auto_order_id)"><span class="glyphicon glyphicon-map-marker"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search delivery address" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search delivery time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th style="display: none"></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>

                  </div>



                  <div id="delivered_orders_tab" class="tab-pane fade">
                    
                    <div class="table-responsive">

                      <table id="delivered_orders_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Delivery Address</th>
                              <th>Delivery Time</th>
                              <th>Order Time</th>
                              <th>View Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in current_all_delivered_orders">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.delivery_address}}</td>
                                <td style="max-width:100px;">{{order.order_time}}</td>
                                <td>{{order.create_date}}</td>
                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search delivery address" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search delivery time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>

                  </div>


                  <div id="rejected_orders_tab" class="tab-pane fade">
                    
                    <div class="table-responsive">

                      <table id="rejected_orders_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Delivery Address</th>
                              <th>Delivery Time</th>
                              <th>Order Time</th>
                              <th>View Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in current_all_rejected_orders">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.delivery_address}}</td>
                                <td style="max-width:100px;">{{order.order_time}}</td>
                                <td>{{order.create_date}}</td>
                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>                            
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:110px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th style="max-width:200px;"><input type="text" class="form-control" placeholder="Search delivery address" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search delivery time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>

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


              <div class="tab-pane" id="track_order_pane">
                  <div class="row">
                    <div class="col-md-12">
                        <div id="cont" style="position: relative;width:100%;height:300px !important;box-shadow: 5px 6px 10px #888888;">
                        <div id="map-canvas" style="height: 100%;width: 100%;overflow: hidden;position: absolute;top:0;left:0;right: 0;bottom: 0;"></div>
                      </div>

                    </div>

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

<?php require_once('admin_control_sidebar.php');?>
<?php require_once('admin_footer.php');?>

<script type="text/javascript">
  $(document).ready(function(){

    $('#order_details_tab').hide();
    $('#track_order_tab').hide();
    
    $('#orders_tab').click(function(){
      $('#order_details_tab').hide();
      $('#track_order_tab').hide();

    });

    //angular.bootstrap(document.getElementById("wrapper"), ['MyApp']);


    /*$('#routebtn').on('click',function(){
        calcRoute(track_order_obj);
    });*/






    // DataTable
    var pending_orders_table = $('#pending_orders_table').DataTable({
      columnDefs: [
          {targets: [6], searchable: false},
          {targets: [6], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    pending_orders_table.columns().every( function () {
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
    var accepted_orders_table = $('#accepted_orders_table').DataTable({
      columnDefs: [
          {targets: [6,7], searchable: false},
          {targets: [6,7], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    accepted_orders_table.columns().every( function () {
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
    var prepared_orders_table = $('#prepared_orders_table').DataTable({
      columnDefs: [
          {targets: [6,7], searchable: false},
          {targets: [6,7], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    prepared_orders_table.columns().every( function () {
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
    var picked_orders_table = $('#picked_orders_table').DataTable({
      columnDefs: [
          {targets: [6,7], searchable: false},
          {targets: [6,7], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    picked_orders_table.columns().every( function () {
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
    var delivered_orders_table = $('#delivered_orders_table').DataTable({
      columnDefs: [
          {targets: [6], searchable: false},
          {targets: [6], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    delivered_orders_table.columns().every( function () {
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
    var rejected_orders_table = $('#rejected_orders_table').DataTable({
      columnDefs: [
          {targets: [6], searchable: false},
          {targets: [6], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    rejected_orders_table.columns().every( function () {
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
  var directionsDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;

  function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var chicago = new google.maps.LatLng(37.334818, -121.884886);
    var mapOptions = {
      zoom: 7,
      center: chicago
    };
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    directionsDisplay.setMap(map);

  }

  function calcRoute(track_order_obj) {
    alert(track_order_obj.del_person_lat+'...'+track_order_obj.del_person_lng);
    
    var start = new google.maps.LatLng(track_order_obj.rest_lat, track_order_obj.rest_long);
    var end = new google.maps.LatLng(track_order_obj.station_lat,track_order_obj.station_long);


    var delivery_person = new google.maps.LatLng(22.5611154, 72.9148654);
    
    var delivery_person_marker = new google.maps.Marker({
            draggable: false,
            position: delivery_person,
            map: map,
            title: "Delivery person location"
    });

    var request = {
      origin: start,
      destination: end,
      travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
        directionsDisplay.setMap(map);
      } else {
        alert("Directions Request from " + start.toUrlValue(6) + " to " + end.toUrlValue(6) + " failed: " + status);
      }
    });
  }

  google.maps.event.addDomListener(window, 'load', initialize);

</script>



<script type="text/javascript">
  //angularMoment is used by angular-moment.min.js
  var app = angular.module("MyApp", ['angularMoment']);

  app.controller("MyController", ['$scope','$filter', '$http', function($scope,$filter,$http) {
    

    $scope.current_all_pending_orders=<?php echo json_encode($current_pending_orders_list); ?>;
    $scope.current_all_accepted_orders=<?php echo json_encode($current_accepted_orders_list); ?>;
    $scope.current_all_prepared_orders=<?php echo json_encode($current_prepared_orders_list); ?>;
    $scope.current_all_picked_orders=<?php echo json_encode($current_picked_orders_list); ?>;
    $scope.current_all_delivered_orders=<?php echo json_encode($current_delivered_orders_list); ?>;
    $scope.current_all_rejected_orders=<?php echo json_encode($current_rejected_orders_list); ?>;
    
    $scope.order_details={};
    $scope.track_order_details={};

    $scope.fetch_order_details=function(auto_order_id)
    {

      $http({
          method : "POST",
            url : "../restaurant/restaurant_orders_data.php",
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

    $scope.track_order=function(auto_order_id){

      $http({
          method : "POST",
            url : "admin_view_orders_data.php",
            data: $.param({'action':'fetch_rest_and_del_person_latlong','auto_order_id':auto_order_id}),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},
        }).then(function mySuccess(response) {
          
          $scope.track_order_details=response.data;
          calcRoute($scope.track_order_details);

          $('#track_order_tab').show();
          $('#track_order_tab').trigger('click');
          window.scrollTo(0,0);
          

          //console.log($scope.order_details);
          /*$('#order_details_tab').show();
          $('#order_details_tab').trigger('click');
          window.scrollTo(0,0);*/

        }
      );

    }   

     
  }]);



    

</script>