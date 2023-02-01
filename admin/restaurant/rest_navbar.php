<?php

require_once('restaurant_class.php');
$rest=new Restaurant($db);
$rest_details=$rest->get_rest_details();

?>
<style type="text/css">
  .navbar-nav>.notifications-menu>.dropdown-menu>li .menu>li :hover{
    color: black;
  }
</style>
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
          <li class="dropdown notifications-menu" id="notifications" ng-app="NotificationApp" ng-controller="NotificationController">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">{{current_all_notifications.length}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have {{current_all_notifications.length}} notifications</li>
              <li>
                <ul class="menu">
                  <li ng-repeat="current_notification in current_all_notifications" ng-style="current_notification.notification_type == 0 && {'background-color': '#080707'} || current_notification.notification_type == 1 && {'background-color': '#edc211'} || current_notification.notification_type == 2 && {'background-color': 'rgb(210, 30, 30)'} || current_notification.notification_type == 3 && {'background-color': 'rgb(30, 65, 93)'} || current_notification.notification_type == 4 && {'background-color': 'rgb(34, 144, 81)'}">

                    <a href="#" class="no_background_change_on_hover">

                      <div class="row" style="color:white;">

                      <div class="col-md-2 col-sm-2" style="padding-right:0;">
                      

                      <span ng-switch="current_notification.notification_type">
                          <span ng-switch-when="0"><img src="images/foodorder3.png" style="height: 30px;background-color:white;margin-right: 0px;margin-top: 5px;" class="img-circle pull-left"></span>
                          <span ng-switch-when="1"><img src="images/order_accepted2.png" style="height: 30px;background-color:white;margin-right: 0px;margin-top: 5px;" class="img-circle pull-left"></span>
                          <span ng-switch-when="2"><img src="images/delivery_person2.png" style="height: 30px;background-color:white;margin-right: 0px;margin-top: 5px;" class="img-circle pull-left"></span>
                          <span ng-switch-when="3"><img src="images/order_picked2.png" style="height: 30px;background-color:white;margin-right: 0px;margin-top: 5px;" class="img-circle pull-left"></span>
                          <span ng-switch-when="4"><img src="images/delivered.png" style="height: 30px;background-color:white;margin-right: 0px;margin-top: 5px;" class="img-circle pull-left"></span>
                      </span>

                      </div>

                      <div class="col-md-10 col-sm-10" style="padding-left: 3px;">

                        <div class="row">

                          <div class="col-md-5 col-sm-5" style="padding-right:0;">#{{current_notification.order_id}}</div> 
                          <div class="col-md-7 col-sm-7">
                            <span class="glyphicon glyphicon-time" style="font-size:12px;"></span>
                            <b><span am-time-ago="current_notification.created_at"></span></b>
                          </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                          <div class="col-md-5 col-sm-5 col-xs-offset-2 col-md-offset-0 col-sm-offset-0">
                            <span ng-switch="current_notification.notification_type">
                              <span ng-switch-when="0">(New order)</span>
                              <span ng-switch-when="1">(Order accepted)</span>
                              <span ng-switch-when="2">(Delivery person assigned)</span>
                              <span ng-switch-when="3">(Order picked up)</span>
                              <span ng-switch-when="4">(Order delivered)</span>
                            </span>
                          </div>
                        </div>
                        <!-- end row -->


                      </div>
                      
                      </div>
                      <!-- end row -->
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#"></a></li>
            </ul>
          </li>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php  
              if($rest_details['profile_url']!='')
              {
                echo $rest_details['profile_url'];
              }
              else{echo 'images/default.jpg';}?>" style="" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo @$_SESSION['restaurant_name']; ?></span>
              &nbsp;<small><span class="glyphicon glyphicon-chevron-down" style="font-size:10px;"></span></small>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php  
              if($rest_details['profile_url']!='')
              {
                echo $rest_details['profile_url'];
              }
              else{echo 'images/default.jpg';}?>" class="img-circle" alt="User Image" style="width:100px;height:100px;">

                <p>
                  <?php echo $_SESSION['restaurant_name']; ?>
                  <small>Member since <?php echo date("M j, Y", strtotime($rest_details['created_at']));?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="restaurant_view_menu.php">Menu</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="restaurant_view_orders.php">Orders</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="restaurant_dashboard.php">Dashboard</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <!-- <a href="restaurant_profile.php" class="btn btn-default btn-flat">Profile</a> -->
                  <style>
                    .toggle.android { border-radius: 0px;}
                    .toggle.android .toggle-handle { border-radius: 0px; }
                  </style>

                  <input type="checkbox" id="status" name="status" data-toggle="toggle" data-width="105" data-on="Online" data-off="Offline" data-onstyle="success" data-offstyle="danger" data-style="android" <?php if($rest_details['status']==1){echo "checked";} ?>>
                      
                </div>
                <div class="pull-right">
                  <?php if($_SESSION['logintype']=='normal'){?>
                    <form action="restaurant_logout.php" method="post">
                    <input type="hidden" value="normal_logout" name="logout_type" id="logout_type">
                    <input type="submit" id="logout" name="logout" value="logout" class="btn btn-default btn-flat">
                  </form>
                  <?php } else if($_SESSION['logintype']=='google') {?>
                  <button type="button" id="google_logout" name="google_logout" onclick="signOut();" class="btn btn-default btn-flat">logout</button>
                  <?php } ?>
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
          <img src="<?php  
              if($rest_details['profile_url']!='')
              {
                echo $rest_details['profile_url'];
              }
              else{echo 'images/default.jpg';}?>" class="img-circle" alt="User Image" style="width:80px;height:45px;">
        </div>
        <div class="pull-left info" style="margin-left: -7px;">
          <p><?php echo $_SESSION['restaurant_name']; ?></p>
          <?php if($rest_details['verified']==1) { ?>
            <a href="#"><!-- <i class="fa fa-circle text-success"></i> --> <img src="images/correct_icon.png" /> &nbsp;Verified</a>
          <?php } else { ?>
            <a href="#"> <i class="fa fa-circle text-danger"></i> &nbsp;<span class="text-danger">Pending</span></a>
          <?php } ?>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <li>
          <a href="restaurant_dashboard.php">
            <i class="fa fa-th"></i> <span>Restaurant Dashboard</span>
          </a>
        </li>
        <li>
          <a href="restaurant_profile.php">
            <i class="fa fa-th"></i> <span>Restaurant Profile</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i>
            <span>Restaurant Menu</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="restaurant_add_menu.php"><i class="fa fa-circle-o"></i>Insert Menu Items</a></li>
            <li><a href="restaurant_view_menu.php"><i class="fa fa-circle-o"></i>View Menu Items</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i>
            <span>Orders</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="restaurant_orders.php"><i class="fa fa-circle-o"></i>Manage orders</a></li>
            <li><a href="restaurant_past_orders.php"><i class="fa fa-circle-o"></i>Past Orders</a></li>
          </ul>
        </li>
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>