<?php 
require_once('../database/dbconfig.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('admin_common_libraries.php'); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
  <?php 

  require_once('admin_navbar.php');

  $new_orders_count=$admin->count_new_orders();
  $this_month_orders_count=$admin->count_this_month_orders();

  ?>




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <!-- <small>Control panel</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->

      <?php 
/*      echo "<pre>";
      echo json_encode($admin->orders_this_week());
      echo "</pre>";*/
      ?>




      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $new_orders_count; ?></h3>

              <p>New orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $this_month_orders_count; ?></h3>

              <p>This month orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
          </div>
        </div>
        
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><i class="fa fa-rupee" style="font-size:28px;"></i> <?php echo $admin->count_payments_in_today(); ?> </h3>
              <p>Payments in today</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><i class="fa fa-rupee" style="font-size:28px;"></i> <?php echo $admin->count_payments_out_today(); ?> </h3>
              <p>Payments out today</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><i class="fa fa-rupee" style="font-size:28px;"></i> <?php echo $admin->count_profit_today(); ?> </h3>
              <p>Profit today</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><i class="fa fa-rupee" style="font-size:28px;"></i> <?php echo $admin->count_payments_in_this_month(); ?> </h3>
              <p>Payments in this month</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><i class="fa fa-rupee" style="font-size:28px;"></i> <?php echo $admin->count_payments_out_this_month(); ?> </h3>
              <p>Payments out this month</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><i class="fa fa-rupee" style="font-size:28px;"></i> <?php echo $admin->count_profit_this_month(); ?> </h3>
              <p>Profit this month</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->      

      

        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('admin_control_sidebar.php');?>
<?php require_once('admin_footer.php');?>


<script>
  $(function () {
    "use strict";

    




  var weekdays = ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"];

  Morris.Line({
    element: 'weekly-line-chart',
    data: [{
        "period": "2018-04-26",
        "total": 4
      },
      {
        "period": "2018-24-27",
        "total": 2
      },
      {
        "period": "2018-02-28",
        "total": 5
      },
      {
        "period": "2018-03-01",
        "total": 9
      },
      {
        "period": "2018-03-02",
        "total": 15
      },
      {
        "period": "2018-03-03",
        "total": 12
      }, {
        "period": "2018-03-04",
        "total": 12
      }
    ],
    lineColors: ['#f5901a', '#fc8710', '#FF6541', '#A4ADD3', '#766B56'],
    xkey: 'period',
    ykeys: ['total'],
    labels: ['Total'],
    xLabels: 'day',
    xLabelFormat: function(d) {
      return weekdays[d.getDay()];
    },
    resize: true
  });

















    //DONUT CHART
    var donut = new Morris.Donut({
      element: 'sales-chart',
      resize: true,
      colors: ["#3c8dbc", "#f56954", "#00a65a"],
      data: [
        {label: "Download Sales", value: 12},
        {label: "In-Store Sales", value: 30},
        {label: "Mail-Order Sales", value: 20}
      ],
      hideHover: 'auto'
    });
    //BAR CHART
    var bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: [
        {y: '2006', a: 100, b: 90},
        {y: '2007', a: 75, b: 65},
        {y: '2008', a: 50, b: 40},
        {y: '2009', a: 75, b: 65},
        {y: '2010', a: 50, b: 40},
        {y: '2011', a: 75, b: 65},
        {y: '2012', a: 100, b: 90}
      ],
      barColors: ['#00a65a', '#f56954'],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['CPU', 'DISK'],
      hideHover: 'auto'
    });
  });
</script>
