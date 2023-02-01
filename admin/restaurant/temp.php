<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Restaurant Menu</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php require_once('rest_common_libraries.php'); ?>

    <!-- Select2 -->
    <style type="text/css">
		.switch {
		  position: relative;
		  display: inline-block;
		  width: 40px;
		  height: 23px;
		}
    	/* The switch - the box around the slider */
		

		/* Hide default HTML checkbox */
		.switch input {
		  opacity: 0;
		  width: 0;
		  height: 0;
		}

		/* The slider */
		.slider {
		  position: absolute;
		  cursor: pointer;
		  top: 0;
		  left: 0;
		  right: 0;
		  bottom: 0;
		  background-color: green;
		  -webkit-transition: .4s;
		  transition: .4s;
		}

		.slider:before {
		  position: absolute;
		  content: "";
		  height: 18px;
		  width: 18px;
		  left: 2px;
		  bottom: 3px;
		  background-color: #ccc;
		  -webkit-transition: .4s;
		  transition: .4s;
		}

		.slider:after {
		  background-color: red;
		}


		input:checked + .slider {
		  background-color: red;
		}


		input:checked + .slider:before {
		  -webkit-transform: translateX(18px);
		  -ms-transform: translateX(18px);
		  transform: translateX(18px);
		}

		/* Rounded sliders */
		.slider.round {
		  border-radius: 34px;
		}

		.slider.round:before {
		  border-radius: 50%;
		}

    </style>

    <style type="text/css">
    	.food_ingredients{
			width:130px;
		}
		.error{
			color:red;
		}
    </style>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.18.10/slimselect.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.18.10/slimselect.min.css" rel="stylesheet"></link>

</head>
<body class="hold-transition skin-blue sidebar-mini">

<div id="wrapper" class="wrapper" ng-app="MyApp" ng-controller="MyController">
  <?php
  require_once('rest_navbar.php');
  
  $cuisines_list=$rest->get_all_cuisines_list();

  ?>



  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

  </div>
  <!-- /.content-wrapper -->

<?php require_once('rest_control_sidebar.php');?>
<?php require_once('rest_footer.php');?>
