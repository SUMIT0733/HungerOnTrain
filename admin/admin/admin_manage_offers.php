<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Manage Offers</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('admin_common_libraries.php'); ?>
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js"></script>
  <style type="text/css">
    #deactivated_offers_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }
    #activated_offers_table tr td
    {
      border-top:0px;border-bottom:1px solid #ddd;
    }

    .modal_labels
    {
       color:white;
    }

    
    .toggle.android { border-radius: 0px;}
    .toggle.android .toggle-handle { border-radius: 0px; }
  
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

  $activated_offers=$admin->get_activated_offers();
  $deactivated_offers=$admin->get_deactivated_offers();
 
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage Offers
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Offers</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">

          <div class="nav-tabs-custom">
            
            <ul class="nav nav-tabs" id="nav-tabs">
              
              <li><a href="#activated" id="activated_offers_tab" data-toggle="tab">Activated</a></li>
              <li><a href="#deactivated" id="deactivated_offers_tab"  data-toggle="tab">Deactivated</a></li>
              <li><a href="#add" id="Add_offer_tab" data-toggle="tab">Add</a></li>
            </ul>
            
            <div class="tab-content">

                <div class="tab-pane" id="activated">


                  <div class="table-responsive">
                    
                    <table id="activated_offers_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th style="max-width:50px;">ID</th>
                              <th>Offer Name</th>
                              <th>Offer Code</th>
                              <th>Start Date</th>
                              <th>Expiry Date</th>
                              <th>View</th>
                              <th>Deactivate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="offer in activated_offers">
                                <td style="max-width:50px;">{{offer.offer_id}}</td>
                                <td>{{offer.offer_name}}</td>
                                <td>{{offer.offer_code}}</td>
                                <td>{{offer.start_date}}</td>
                                <td>{{offer.expiry_date}}</td>
                                <td><button class="btn btn-warning" ng-click="view_activated_offer_details(offer.offer_id)" data-toggle="modal" data-target="#myModal">View</button>
                                </td>
                                <td><button class="btn btn-success" ng-click="deactivate_offer(offer.offer_id)">Deactivate</button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" style="max-width:90px;" placeholder="Search ID" /></th>
                              <th><input type="text" class="form-control" style="max-width:190px;" placeholder="Search Offer Name" /></th>
                              <th><input type="text" class="form-control" style="max-width:150px;" placeholder="Search Offer Code" /></th>
                              <th><input type="text" class="form-control" style="max-width:150px;" placeholder="Search Start Date" /></th>
                              <th><input type="text" class="form-control" style="max-width:153px;" placeholder="Search Expiry Date" /></th>
                              <th style="display: none"></th>
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
                    
                    <table id="deactivated_offers_table" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                              <th style="max-width:50px;">ID</th>
                              <th>Offer Name</th>
                              <th>Offer Code</th>
                              <th>Start Date</th>
                              <th>Expiry Date</th>
                              <th>View</th>
                              <th>Deactivate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="offer in deactivated_offers">
                                <td style="max-width:50px;">{{offer.offer_id}}</td>
                                <td>{{offer.offer_name}}</td>
                                <td>{{offer.offer_code}}</td>
                                <td>{{offer.start_date}}</td>
                                <td>{{offer.expiry_date}}</td>
                                <td><button class="btn btn-warning" ng-click="view_deactivated_offer_details(offer.offer_id)" data-toggle="modal" data-target="#myModal">View</button>
                                </td>
                                <td><button class="btn btn-success" ng-click="activate_offer(offer.offer_id)">Activate</button></td>
                            </tr>                         
                        </tbody>
                        <tfoot>
                            <tr>
                              <th><input type="text" class="form-control" style="max-width:90px;" placeholder="Search ID" /></th>
                              <th><input type="text" class="form-control" style="max-width:190px;" placeholder="Search Offer Name" /></th>
                              <th><input type="text" class="form-control" style="max-width:150px;" placeholder="Search Offer Code" /></th>
                              <th><input type="text" class="form-control" style="max-width:150px;" placeholder="Search Start Date" /></th>
                              <th><input type="text" class="form-control" style="max-width:153px;" placeholder="Search Expiry Date" /></th>
                              <th style="display: none"></th>
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
                          <label for="offer_name" class="col-sm-3 control-label">Offer Name</label>

                          <div class="col-sm-3">
                            <input type="text" id="offer_name" name="offer_name" ng-model="offer_name" class="form-control" placeholder="Enter offer name" required>
                          </div>

                          <label for="offer_code" class="col-sm-2 control-label">Offer Code</label>

                          <div class="col-sm-3">
                            <input type="textbox" class="form-control" id="offer_code" name="offer_code" ng-model="offer_code" placeholder="Enter offer code">
                          </div>

                        </div>

                        <div class="form-group">
                          <label for="start_date" class="col-sm-3 control-label">Starting Date</label>

                          <div class="col-sm-3">
                            <input type="date" id="start_date" name="start_date" ng-model="start_date" class="form-control" placeholder="Offer starting date" min="<?php echo date('Y-m-d');?>" required >
                          </div>

                          <label for="expiry_date" class="col-sm-2 control-label">Expiry Date</label>

                          <div class="col-sm-3">
                            <input type="date" id="expiry_date" name="expiry_date" ng-model="expiry_date" class="form-control" placeholder="Offer expiry date" min="<?php echo date('Y-m-d', time() + 86400); ?>" required>
                          </div>

                        </div> 

                        <div class="form-group">
                          <label for="perc_or_flat" class="col-sm-3 control-label">Discount type</label>

                          <div class="col-sm-2">
                            <input type="checkbox" id="perc_or_flat" name="perc_or_flat" data-toggle="toggle" data-width="105" data-on="Flat(in Rs.)" data-off="Percentage" data-onstyle="success" data-offstyle="primary" data-style="android" checked>
                          </div>

                         
                          <label for="discount_units" class="col-sm-1 control-label">Discount Units</label>

                          <div class="col-sm-3">
                            <input type="number" id="discount_units" class="form-control" name="discount_units" ng-model="discount_units" placeholder="Enter discount units" min="1">
                          </div>

                          <label for="discount_upto_rs" id="discount_upto_rs_label" class="col-sm-1 control-label">Discount upto(RS)</label>

                          <div class="col-sm-2" id="discount_upto_rs_div">
                            <input type="number" id="discount_upto_rs" class="form-control" name="discount_upto_rs" ng-model="discount_upto_rs" placeholder="discount upto in RS." min="1">
                          </div>
                        

                        </div>                   

                        <div class="form-group">
                          <label for="offer_description" class="col-sm-3 control-label">Offer Description</label>

                          <div class="col-sm-8">
                            <textarea id="offer_description" name="offer_description" ng-model="offer_description" class="form-control" placeholder="Enter offer description" required></textarea>
                          </div>

                        </div>


                        <div class="form-group">
                          
                          <label for="usage_per_user" class="col-sm-3 control-label">Usage per User</label>

                          <div class="col-sm-2">
                            <input type="number" min="1" id="usage_per_user" name="usage_per_user" ng-model="usage_per_user" class="form-control" placeholder="Usage per User" required>
                          </div>

                          <label for="is_min_order" class="col-sm-2 control-label">Minimum order amount</label>

                          <div class="col-sm-1">
                            <input type="checkbox" id="is_min_order" name="is_min_order" data-toggle="toggle" data-width="50" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" data-style="android">
                          </div>

                          <label for="min_order_amount" id="min_order_amount_label" class="col-sm-2 control-label">Minimum order amount (RS)</label>

                          <div class="col-sm-2" id="min_order_amount_div">
                            <input type="number" id="min_order_amount" class="form-control" name="min_order_amount" ng-model="min_order_amount" placeholder="Min amount" min="1">
                          </div>

                        </div>

                        <div class="form-group">
                          
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

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Offer Details</h4>
          </div>
          <div class="modal-body" style="background-color: #0a1f27;">
            <form class="form-horizontal">
                  
                  <div class="form-group">
                    
                    <div class="col-sm-5 col-md-offset-1">
                      
                      <label for="view_offer_name" class="control-label modal_labels">Offer Name</label>

                      <input type="text" class="form-control" id="view_offer_name" name="view_offer_name" value="{{offer_details_object.offer_name}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>

                    <div class="col-sm-5">
                      <label for="view_offer_code" class="control-label modal_labels">Offer Code</label>
                      <input type="text" class="form-control" id="view_offer_code" name="view_offer_code" value="{{offer_details_object.offer_code}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>

                  </div>

                  <div class="form-group">
                    
                    <div class="col-sm-5 col-md-offset-1">
                      
                      <label for="view_start_date" class="control-label modal_labels">Starting Date</label>

                      <input type="text" class="form-control" id="view_start_date" name="view_start_date" value="{{offer_details_object.start_date}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>

                    <div class="col-sm-5">
                      <label for="view_expiry_date" class="control-label modal_labels">Expiry Date</label>
                      <input type="text" class="form-control" id="view_expiry_date" name="view_expiry_date" value="{{offer_details_object.expiry_date}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>

                  </div>

                  <div class="form-group">
                    
                    <div class="col-sm-3 col-md-offset-1">
                      
                      <label for="view_discount_type" class="control-label modal_labels">Discount Type</label>

                      <span class="form-control" id="view_discount_type" name="view_discount_type">
                        <span ng-switch="offer_details_object.percentage_or_flat">
                          <span ng-switch-when="0">Percentage</span>
                          <span ng-switch-when="1">Flat(in Rs.)</span>      
                        </span>
                      </span>                      
                    </div>

                    <div class="col-sm-3">
                      <label for="view_discount_units" class="control-label modal_labels">Units</label>
                      <input type="text" class="form-control" id="view_discount_units" name="view_discount_units" value="{{offer_details_object.unit}}" style="background-color: white;font-weight: bold;" readonly>
                    </div> 
                    
                    <div class="col-sm-4">
                      <label for="view_discount_upto" class="control-label modal_labels">Discount upto(in Rs.)</label>
                      <input type="text" class="form-control" id="view_discount_upto" name="view_discount_upto" value="{{offer_details_object.discount_upto_rs}}" style="background-color: white;font-weight: bold;" readonly>
                    </div>  

                  </div>

                  <div class="form-group">
                    
                    <div class="col-sm-5 col-md-offset-1">
                      
                    <label for="view_usage_per_user" class="control-label modal_labels">Discount Type</label>

                    <input type="text" class="form-control" id="view_usage_per_user" name="view_usage_per_user" value="{{offer_details_object.usage_per_user}}" style="background-color: white;font-weight: bold;" readonly>

                    </div>
                   
                    <div class="col-sm-5">
                      <label for="view_discount_upto" class="control-label modal_labels">Min. order amount(in Rs.)</label>

                      <span class="form-control" id="view_discount_type" name="view_discount_type">
                        <span ng-switch="offer_details_object.percentage_or_flat">
                          <span ng-switch-when="0">Percentage</span>
                          <span ng-switch-when="1">Flat(in Rs.)</span>      
                        </span>
                      </span>

                    </div>  

                  </div>

                  <div class="form-group">
                    
                    <div class="col-sm-10 col-md-offset-1">
                      
                    <label for="view_offer_description" class="control-label modal_labels">Offer Description</label>

                    <textarea type="text" class="form-control" id="view_offer_description" name="view_offer_description" style="background-color: white;font-weight: bold;" readonly>{{offer_details_object.description}}</textarea> 

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
      $('.errordiv').hide();

      $('#perc_or_flat').bootstrapToggle();
      $('#status').bootstrapToggle();

      $('#discount_upto_rs_label').hide();
      $('#discount_upto_rs_div').hide();

      $('#min_order_amount_label').hide();
      $('#min_order_amount_div').hide();
      var check1=true;
      var check2=false;

      $('#perc_or_flat').on('change',function(){
        check1=$('#perc_or_flat').prop('checked');
        if(check1==false)
        {
          $('#discount_upto_rs_label').show();
          $('#discount_upto_rs_div').show(); 
        }
        else{
          $('#discount_upto_rs_label').hide();
          $('#discount_upto_rs_div').hide();
        }
      });

      $('#is_min_order').on('change',function(){
        check2=$('#is_min_order').prop('checked');
        if(check2==true)
        {
          $('#min_order_amount_label').show();
          $('#min_order_amount_div').show(); 
        }
        else{
          $('#min_order_amount_label').hide();
          $('#min_order_amount_div').hide();
        }
      });

      $('#submit').click(function(){
          var offer_name=$('#offer_name').val();
          var offer_code=$('#offer_code').val();
          var perc_or_flat=$('#perc_or_flat').prop('checked');
          var discount_units=$('#discount_units').val();
          var start_date=$('#start_date').val();
          var expiry_date=$('#expiry_date').val();
          var usage_per_user=$('#usage_per_user').val();
          var is_min_order=$('#is_min_order').prop('checked');
          var offer_description=$('#offer_description').val();
          var status=$('#status').prop('checked');

          if(perc_or_flat==false)
          {
            var discount_upto_rs=$('#discount_upto_rs').val();
          }
          else{
            var discount_upto_rs=$('#discount_units').val();
          }

          if(is_min_order==true)
          {
            var min_order_amount=$('#min_order_amount').val();
          }
          else{
            var min_order_amount=0;
          }


          if(offer_name==null || offer_name=='')
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter offer name');
            return false;
          }
          if(offer_code==null || offer_code=='')
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter offer code');
            return false;
          }

          if(start_date==null || start_date=='')
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please select start date of offer');
            return false;
          }
          if(expiry_date==null || expiry_date=='')
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter expiry date of offer');
            return false;
          }

          if(new Date(expiry_date) <= new Date(start_date))
          {
            $('.errordiv').fadeIn();
            $('#error').html('Expiry date should be more than starting date');
            return false;
          }

          if(perc_or_flat==null)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please select discount type');
            return false;
          }
          if(discount_units==null || discount_units=='' || discount_units<1)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter discount units(more than 0)');
            return false;
          }
          
          if(perc_or_flat==false && discount_units>100)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter discount percentage(less than 100)');
            return false;
          }

          if(perc_or_flat==false && discount_upto_rs==null || discount_upto_rs=='' || discount_upto_rs<1)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter discount upto(in RS)');
            return false;
          }

          if(offer_description==null || offer_description=='')
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter offer description');
            return false;
          }

          if(usage_per_user==null || usage_per_user=='' || usage_per_user<1)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter offer usage per user(more than 0)');
            return false;
          }
         
          if(is_min_order==null)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please select min order amount ');
            return false;
          }

          if(is_min_order==true && (min_order_amount==null || min_order_amount=='' || min_order_amount<1))
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please enter min order amount(in RS)');
            return false;
          }

          if(status==null)
          {
            $('.errordiv').fadeIn();
            $('#error').html('Please select status');
            return false;
          }

          $('.errordiv').hide();

          /*alert(perc_or_flat+':'+discount_units+':'+discount_upto_rs);
          return false;*/

          $.ajax({
            url:'admin_manage_offers_data.php',
            method:'POST',
            data:{'action':'add_new_offer','offer_name':offer_name,'offer_code':offer_code,'perc_or_flat':perc_or_flat,'discount_units':discount_units,'discount_upto_rs':discount_upto_rs,'min_order_amount':min_order_amount,'start_date':start_date,'expiry_date':expiry_date,'usage_per_user':usage_per_user,'offer_description':offer_description,'status':status},
            success: function(result)
            {
              if(result=='success')
              {
                //swal(title,description,type) from sweetalert.min.js
                if(status==true){
                  swal("","New Offer added to activated list", "success")
                  setTimeout(function(){window.location.href='admin_manage_offers.php?show_offers=activated_offers';},1000);
                }
                else
                {
                  swal("","New Offer added to deactivated list", "success")
                  setTimeout(function(){window.location.href='admin_manage_offers.php?show_offers=deactivated_offers';},1000);
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
    const show_offers = urlParams.get('show_offers');
    
    if(show_offers=='deactivated_offers')
    {
      $('#deactivated_offers_tab').trigger('click');
    }
    else
    {
      $('#activated_offers_tab').trigger('click');
    }

    //It will remove all query strings from url
    history.pushState('', document.title, window.location.pathname);

 
    // DataTable
    var deactivated_offers_table = $('#deactivated_offers_table').DataTable({
      columnDefs: [
          {targets: [5,6], searchable: false},
          {targets: [5,6], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    deactivated_offers_table.columns().every( function () {
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
    var activated_offers_table = $('#activated_offers_table').DataTable({
      columnDefs: [
          {targets: [5,6], searchable: false},
          {targets: [5,6], orderable: false}  // set orderable for selected columns
      ]
    });
 
    // Apply the search
    activated_offers_table.columns().every( function () {
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
    
  $scope.activated_offers = <?php echo json_encode($activated_offers); ?>;
  $scope.deactivated_offers = <?php echo json_encode($deactivated_offers); ?>;

  $scope.view_activated_offer_details= function(offer_id){
    $scope.offer_details_object = $filter('filter')($scope.activated_offers, function (d) {return d.offer_id === offer_id;})[0];
  }

  $scope.view_deactivated_offer_details= function(offer_id){
    $scope.offer_details_object = $filter('filter')($scope.deactivated_offers, function (d) {return d.offer_id === offer_id;})[0];
  }


  $scope.deactivate_offer=function(offer_id)
  {
      $http({
          method : "POST",
            url : "admin_manage_offers_data.php",
            data:'deactivate_offer_id='+JSON.stringify(offer_id),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},

        }).then(function mySuccess(response) {
          if(response.data=='success')
          {
              //swal(title,description,type) from sweetalert.min.js
              swal("","Offer deactivated", "success")
            
              setTimeout(function(){window.location.href='admin_manage_offers.php?show_offers=deactivated_offers';},1000);
          }          
      }
    );
  }

  $scope.activate_offer=function(offer_id)
  {
      $http({
          method : "POST",
            url : "admin_manage_offers_data.php",
            data:'activate_offer_id='+JSON.stringify(offer_id),
            headers:{'Content-Type': 'application/x-www-form-urlencoded'},

      }).then(function mySuccess(response) {
          if(response.data=='success')
          {
              //swal(title,description,type) from sweetalert.min.js
              swal("","Offer activated", "success")
            
              setTimeout(function(){window.location.href='admin_manage_offers.php?show_offers=activated_offers';},1000);
          }          
      });
  }


  }]);
</script>
