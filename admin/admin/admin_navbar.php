<?php

require_once('admin_class.php');
$admin=new Admin($db);

?>
<header class="main-header">
    <!-- Logo -->
    <a href="restaurant_dashboard.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>HOT</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Hunger</b>On<b>Train</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">5</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 5 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> Notification 1
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-warning text-yellow"></i> Notification 2
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-red"></i> Notification 3
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-shopping-cart text-green"></i> Notification 4
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="fa fa-user text-red"></i> Notification 5
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="images/profile.png" style="background-color: white;" class="user-image" alt="User Image">
              <span class="hidden-xs">Admin</span>
              &nbsp;<small><span class="glyphicon glyphicon-chevron-down" style="font-size:10px;"></span></small>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="images/profile.png" class="img-circle" alt="User Image" style="width:100px;height:100px;background-color: white;">
                <p>
                  <?php echo $_SESSION['admin_email']; ?>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="restaurant_verification.php">Restaurants</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Orders</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Payment</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                    <form action="admin_logout.php" method="post">
                      <input type="hidden" value="admin_logout" name="logout_type" id="logout_type">
                      <input type="submit" id="logout" name="logout" value="logout" class="btn btn-default btn-flat">
                    </form>
                  
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image" style="margin-left:-7px;">
          <img src="images/profile.png" class="img-circle" alt="User Image" style="width:80px;height:45px;background-color: white;">
        </div>
        <div class="pull-left info" style="margin-left:-7px;">
          <p>Admin</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <li>
          <a href="admin_dashboard.php">
            <i class="fa fa-th"></i> <span>Admin Dashboard</span>
          </a>
        </li>
        
        <li>
          <a href="restaurant_verification.php">
            <i class="fa fa-th"></i> <span>Verify Restaurants</span>
          </a>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i>
            <span>Manage Cities & Stations</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="admin_manage_cities.php"><i class="fa fa-circle-o"></i>Manage Cities</a></li>
            <li><a href="admin_manage_stations.php"><i class="fa fa-circle-o"></i>Manage Stations</a></li>
          </ul>
        </li>

        <li>
          <a href="delivery_person_verification.php">
            <i class="fa fa-th"></i> <span>Verify Delivery Persons</span>
          </a>
        </li>
        
        <li>
          <a href="admin_view_orders.php">
            <i class="fa fa-th"></i> <span>Restaurants Orders</span>
          </a>
        </li>
        
        <li>
          <a href="admin_manage_payment.php">
            <i class="fa fa-th"></i> <span>Manage Payments</span>
          </a>
        </li>
        <li>
          <a href="admin_manage_offers.php">
            <i class="fa fa-th"></i> <span>Manage Offers</span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>