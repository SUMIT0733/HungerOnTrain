<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Manage Payments</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('admin_common_libraries.php'); ?>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">

  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js"></script>

  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-moment/0.10.1/angular-moment.min.js"></script>


  <style type="text/css">
    #rest_pending_payments_table tr td
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

  </style>


</head>

<body class="hold-transition skin-blue sidebar-mini">


<div class="wrapper" ng-app="MyApp" ng-controller="MyController" id="wrapper">
  <?php 

  require_once('admin_navbar.php');
  $rest_pending_payments_list=$admin->fetch_rest_pending_payments();
  $rest_paid_payments_list=$admin->fetch_rest_paid_payments();
  $payments_paid_by_customer_list=$admin->fetch_payments_paid_by_customer();
  $customer_refunded_payments_list=$admin->fetch_customer_refunded_payments();
    
  ?>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Payments
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Payments</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
      <div class="row">
        <div class="col-md-12">

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#payments_pane" data-toggle="tab" id="payments_tab">Payments</a></li>
              <li><a href="#order_details_pane" data-toggle="tab" id="order_details_tab">Order details</a></li>
            </ul>

            <div class="tab-content">
              
              <div class="active tab-pane" id="payments_pane">

                <ul class="nav nav-pills pull-right" >
                  <li class="active" style="border:1px solid #337ab7;"><a data-toggle="pill" href="#pending_payments_tab" style="padding-top: 4px;padding-bottom: 9px;">Pending</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#rest_paid_payments_tab" style="padding-top: 4px;padding-bottom: 9px;">Paid to restaurants</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#prepared_orders_tab" style="padding-top: 4px;padding-bottom: 9px;">Paid by customers</a></li>
                  <li style="border:1px solid #337ab7;"><a data-toggle="pill" href="#cust_refunded_payments_tab" style="padding-top: 4px;padding-bottom: 9px;">Refunded to customers</a></li>
                </ul>
                <br/><br/><br/>
                <div class="tab-content">

                  <div id="pending_payments_tab" class="tab-pane fade in active">

                    <button class="btn btn-success pull-left" id="mark_all_as_paid" ng-disabled="rest_pending_payments.length<1">Mark all as paid</button>
                    <br/><br/><br/>
                    <div class="table-responsive">

                      <table id="rest_pending_payments_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Account No</th>
                              <th>IFSC code</th>
                              <th>Order amount</th>
                              <th>Order Time</th>
                              <th>View Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in rest_pending_payments">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.account_no}}</td>
                                <td style="max-width:100px;">{{order.IFSC_code}}</td>
                                <td style="max-width:100px;">{{order.order_amount}}</td>
                                <td style="max-width:140px;">{{order.create_date}}</td>
                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:140px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th><input type="text" class="form-control" style="max-width:120px;" placeholder="Search account no" /></th>
                              <th><input type="text" class="form-control" style="max-width:120px;" placeholder="Search IFSC code" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search amount" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                  
                  </div>


                  <div id="rest_paid_payments_tab" class="tab-pane fade">

                    <div class="table-responsive">

                      <table id="rest_paid_payments_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Account No</th>
                              <th>IFSC code</th>
                              <th>Order amount</th>
                              <th>Order Time</th>
                              <th>Payment Time</th>
                              <th>View Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in rest_paid_payments">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.account_no}}</td>
                                <td style="max-width:100px;">{{order.IFSC_code}}</td>
                                <td style="max-width:100px;">{{order.order_amount}}</td>
                                <td style="max-width:140px;">{{order.create_date}}</td>
                                <td style="max-width:140px;">{{order.rest_payment_date}}</td>

                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:140px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th><input type="text" class="form-control" style="max-width:120px;" placeholder="Search account no" /></th>
                              <th><input type="text" class="form-control" style="max-width:120px;" placeholder="Search IFSC code" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search amount" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search payment time" /></th>

                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>                    
                    
                  </div>


                  <div id="prepared_orders_tab" class="tab-pane fade">

                    <div class="table-responsive">

                      <table id="payments_paid_by_customer_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Customer Name</th>
                              <th>Customer Email</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Order amount</th>
                              <th>Order Time</th>
                              <th>Payment ID</th>
                              <th>View Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in payments_paid_by_customer">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.customer_name}}</td>
                                <td>{{order.customer_email}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.order_amount}}</td>
                                <td style="max-width:140px;">{{order.create_date}}</td>
                                <td>{{order.payment_id}}</td>

                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:140px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th><input type="text" class="form-control" style="max-width:120px;" placeholder="Search account no" /></th>
                              <th><input type="text" class="form-control" style="max-width:120px;" placeholder="Search IFSC code" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search amount" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search payment time" /></th>

                              <th style="display: none"></th>
                              <!-- We must need to put 'th' for every columns for datatable in-column searching. We are facing problem because in last two columns all are buttons, so column search will not work for any column.  So to not display last two column search fields, we have make it disappeared bcz button search is not required. -->
                            </tr>
                        </tfoot>
                      </table>
                    </div>                        

                  </div>

                  
                  <div id="cust_refunded_payments_tab" class="tab-pane fade">

                    <div class="table-responsive">

                      <table id="cust_refunded_payments_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th>Order ID</th>
                              <th>Customer Name</th>
                              <th>Customer Email</th>
                              <th>Restaurant</th>
                              <th>City</th>
                              <th>Order amount</th>
                              <th>Order Time</th>
                              <th>Payment ID</th>
                              <th>View Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="order in customer_refunded_payments">
                                <td style="max-width:100px;">{{order.order_id}}</td>
                                <td style="max-width:100px;">{{order.customer_name}}</td>
                                <td>{{order.customer_email}}</td>
                                <td style="max-width:100px;">{{order.restaurant_name}}</td>
                                <td style="max-width:100px;">{{order.city_name}}</td>
                                <td style="max-width:100px;">{{order.order_amount}}</td>
                                <td style="max-width:140px;">{{order.create_date}}</td>
                                <td>{{order.payment_id}}</td>

                                <td><button class="btn btn-primary" style="margin:5px;" ng-click="fetch_order_details(order.auto_order_id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" placeholder="Search order id" style="max-width:105px;"/></th>
                              <th ><input type="text" class="form-control" style="max-width:140px;" placeholder="Search restaurant" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search city" /></th>
                              <th><input type="text" class="form-control" style="max-width:120px;" placeholder="Search account no" /></th>
                              <th><input type="text" class="form-control" style="max-width:120px;" placeholder="Search IFSC code" /></th>
                              <th><input type="text" class="form-control" style="max-width:110px;" placeholder="Search amount" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search order time" /></th>
                              <th><input type="text" class="form-control" style="max-width:140px;" placeholder="Search payment time" /></th>

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

<!-- Sweet alerts -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){

    $('#order_details_tab').hide();
    
    $('#payments_tab').click(function(){
      $('#order_details_tab').hide();

    });

    // DataTable
    var rest_pending_payments_table = $('#rest_pending_payments_table').DataTable({
      dom: 'Bfrtip',

      columnDefs: [
          {targets: [7], searchable: false},
          {targets: [7], orderable: false}  // set orderable for selected columns
      ],
      buttons: [
        {
            extend: 'excelHtml5',
            className: "mybuttonToHide",
            exportOptions: {
                columns: [ 3, 4 , 5 ],
            },
            title: ''
        },
      ]

    });

    //To hide the button in the webpage
    rest_pending_payments_table.buttons('.mybuttonToHide').nodes().css("display", "none");






    // Apply the search
    rest_pending_payments_table.columns().every( function () {
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
    var rest_paid_payments_table = $('#rest_paid_payments_table').DataTable({
      columnDefs: [
          {targets: [8], searchable: false},
          {targets: [8], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    rest_paid_payments_table.columns().every( function () {
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
    var payments_paid_by_customer_table = $('#payments_paid_by_customer_table').DataTable({
      columnDefs: [
          {targets: [8], searchable: false},
          {targets: [8], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    payments_paid_by_customer_table.columns().every( function () {
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
    var cust_refunded_payments_table = $('#cust_refunded_payments_table').DataTable({
      columnDefs: [
          {targets: [8], searchable: false},
          {targets: [8], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    cust_refunded_payments_table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );





    $('#mark_all_as_paid').click(function(){
        $.ajax({
          url:'admin_manage_payment_data.php',
          method:'POST',
          data:{'action':'mark_all_payments_as_paid'},
          success:function(result){
            if(result=='success')
            {
                //To download the excel file
                //we have hide the excel export button.
                $('.buttons-excel').trigger('click');

                swal("","All pending payments marked as paid", "success")
                setTimeout(function(){window.location.href='admin_manage_payment.php';},500);
              
            }
            else
            {
              $('#error').html(result);
            }
          },
        });

    });


  });
</script>



<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js
"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js
"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>

<script type="text/javascript">
  //angularMoment is used by angular-moment.min.js
  var app = angular.module("MyApp", ['angularMoment']);

  app.controller("MyController", ['$scope','$filter', '$http', function($scope,$filter,$http) {
    

    $scope.rest_pending_payments=<?php echo json_encode($rest_pending_payments_list); ?>;

    $scope.rest_paid_payments=<?php echo json_encode($rest_paid_payments_list); ?>;

    $scope.payments_paid_by_customer=<?php echo json_encode($payments_paid_by_customer_list); ?>;

    $scope.customer_refunded_payments=<?php echo json_encode($customer_refunded_payments_list); ?>;

    $scope.order_details={};

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
 

     
  }]);



    

</script>