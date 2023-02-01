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
    	/* The switch - the box around the slider */
		.switch {
		  position: relative;
		  display: inline-block;
		  width: 40px;
		  height: 23px;
		}

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
    <section class="content-header" style="padding-bottom:15px;">
      <h1>
        Restaurant Menu
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">User profile</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content" style="background-color: white;">
    	<!-- row -->
    	<div class="row">

        <form id="Menuform" name="Menuform">


        <div class="col-md-12">
          <!-- The time line -->
          <ul class="timeline">
            <!-- timeline time label -->
            <li class="time-label">
                  <span class="bg-red">
                    Restaurant Menu
                  </span>
            </li>
            <!-- /.timeline-label -->
            

            <!-- timeline item -->
            <li ng-repeat="cuisine in Menu.cuisines">

              <i class="fa fa-th-list bg-black"></i>
              
              <div class="timeline-item" style="box-shadow:none;margin-left:70px;">

              	<ng-form id="subform{{$index}}" name="subform{{$index}}" style="display: flex;flex-flow: column;">

                <div class="timeline-header no-border" style="padding-top:0px;padding-bottom:0px;">
                	<div class="row">
	                  	
                  		<div class="col-md-3" style="background-color: #3c8dbc;color:white;padding-top:5px;padding-bottom:15px;">
                  			<label style="font-size: 13px;margin-bottom:2px;">Select Cuisine:</label>
                  			
			                <select id="{{'selectbox'+$index}}" ng-model="cuisine.cuisine_id" style="padding-bottom: 4px;" ng-change="cuisine_changed($index,cuisine.cuisine_id)" required>

			                <!-- ng-options="current_cuisine.cuisine_id as current_cuisine.cuisine_name for current_cuisine in current_all_cuisines_list" -->

			                <option ng-repeat="current_cuisine in current_all_cuisines_list" ng-value="current_cuisine['cuisine_id']" ng-selected="current_cuisine['cuisine_id']==cuisine.cuisine_id">{{current_cuisine['cuisine_name']}}</option>
			                </select>
						</div>

						<div class="col-md-2" style="background-color: #3c8dbc;color:white;padding-top:5px;padding-bottom:17px;">
							
							<button type="button" style="margin-top:20px;" class="btn btn-warning" ng-model="add_cuisine_btn" ng-click="add_cuisine($index,cuisine.cuisine_id)" ng-hide="Menu.cuisines[$index].food_items.length>0">
								<!-- Here in add_cuisine() parameters "cuisine.cuisine_id" is selectbox angular model-name. It gives "value" of selectbox. So if "cuisine.cuisine_name" is used as selectbox model-name and parameter, same result(value of selected option) is achieved-->								
								<span>Add</span>
							</button>
							
							<button type="button" style="margin-top:20px;" class="btn btn-danger pull-right" ng-model="remove_cuisine_btn" ng-click="Remove_cuisine($index)" >
								<span>Remove</span>
							</button>
						</div>
					</div>
						
                </div>

                <!-- sub_timeline start -->

                <div>
	            <ul class="timeline">
	            	<br/>


	             	<li ng-repeat="food_item in cuisine.food_items">

	              	<i class="fa fa-cutlery bg-red"></i>

		              	<div class="timeline-item">

		                <div class="timeline-header" style="background-color: black;color:white;padding-bottom:15px;">
		                  <div class="row" style="padding-left: 5px;">

		                  		<div class="col-md-3">
									
									<label style="font-size: 13px;margin-bottom:2px;">Food Item Name:</label>
									
									<input type="text" style="min-width:110px;" ng-model="food_item.food_name" name="food_name_{{$parent.$index}}_{{$index}}" class="form-control" placeholder="Enter Food item name" value="{{food_item.food_name}}" required/>

									<p class="error" ng-show="(subform{{$parent.$index}}.food_name_{{$parent.$index}}_{{$index}}.$touched || subform{{$parent.$index}}.food_name_{{$parent.$index}}_{{$index}}.$dirty) && subform{{$parent.$index}}.food_name_{{$parent.$index}}_{{$index}}.$invalid">Please enter food item name</p>

								</div>	

								<div class="col-md-2">

									<label style="font-size: 13px;margin-bottom:2px;">Price(INR): </label>

									<input type="number" class="form-control" ng-model="food_item.food_price" name="food_price_{{$parent.$index}}_{{$index}}" placeholder="Enter price" min="0" ng-value="{{food_item.food_price}}" required/>
									
									<p class="error" ng-show="(subform{{$parent.$index}}.food_price_{{$parent.$index}}_{{$index}}.$touched || subform{{$parent.$index}}.food_price_{{$parent.$index}}_{{$index}}.$dirty) && subform{{$parent.$index}}.food_price_{{$parent.$index}}_{{$index}}.$invalid">Please enter price</p>

								</div>

								<div class="col-md-2">
									<label style="font-size: 13px;margin-bottom:2px;">Ingredients: </label>

									<textarea rows="1" class="form-control food_ingredients" name="food_ingredients_{{$parent.$index}}_{{$index}}" ng-model="food_item.food_ingredients" placeholder="Enter ingredients">{{food_item.food_ingredients}}</textarea>
								</div>

								<div class="col-md-3">
									<br/>
									<!-- Rectangular switch -->
									<label>Veg &nbsp;&nbsp;</label>
									<label class="switch">
									  <input type="checkbox" ng-model="food_item.food_type" name="food_type_{{$parent.$index}}_{{$index}}" ng-checked="food_item.food_type">
									  <span class="slider round"></span>
									</label>
									<label>&nbsp;&nbsp; Non-veg</label>

									<p class="error" ng-show="(subform{{$parent.$index}}.food_type_{{$parent.$index}}_{{$index}}.$touched || subform{{$parent.$index}}.food_type_{{$parent.$index}}_{{$index}}.$dirty) && subform{{$parent.$index}}.food_type_{{$parent.$index}}_{{$index}}.$invalid">Please select food type</p>
								
								</div>

								<div class="col-md-2">
									<br>
									<button type="button" class="btn btn-danger" ng-model="remove_food_item_btn"  ng-click="Remove_food_item($parent.$index,$index)">
									<span>Remove</span></button>
								</div>


		                  	</div>
		                  	<!-- end class="row" -->
		                </div>
		                <!-- end class="timeline-header" -->
		     
		               	</div>
		                <!-- end class="timeline-time" -->

		            <br/>

	            	</li>
					
					<li>
						<button class="btn btn-success" ng-model="add_more_item" ng-click="Add_more_food_item($index)" ng-show="cuisine.food_items.length>=1" ng-disabled="subform{{$index}}.$invalid">Add more item</button>

						
					</li>
	            	<li>
		              <i class="fa fa-clock-o bg-gray"></i>
		            </li>

	            </ul>
	            	<br/>
	       		</div>
	       		<!-- End sub_timeline -->
              	</ng-form>
              </div>
          

            </li>
            <!-- END timeline item -->
            
            <li>
            	<button class="btn btn-primary" ng-model="add_more_cuisine" ng-hide="Menu.cuisines.length==0 || Menu.cuisines[0].food_items.length==0" ng-click="Add_more_cuisine()" ng-disabled="is_form_invalid()">Add more Cuisine</button>
            </li> 

            <li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
          </ul>
        </div>
        <!-- /.col -->

        </form>

      </div>
      <!-- /.row -->
      <br/>
      <div class="row" style="text-align:center;">
      	<button class="btn btn-warning" style="width:100px;" id="submit" name="submit" ng-disabled="is_form_invalid()" ng-click="submit_menu()">Submit</button>
      </div>
      <!-- {{Menu}} -->
      <br/>
      <div class="row">

      	<div class="form-group messagediv">
            <div class="col-sm-4 col-sm-offset-4">
              <div class="alert alert-success">
                <span id="message"></span>
              </div>
            </div>
        </div>
      </div>
      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('rest_control_sidebar.php');?>
<?php require_once('rest_footer.php');?>
<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>

<script>

  	$(function () {
		initialize_SlimSelect('selectbox0');
		$('.messagediv').hide();
  	});

	var selected_cuisine;
	function initialize_SlimSelect(selectbox_id){
		new SlimSelect({
		  select:  document.querySelector('#'+selectbox_id+''),
		  searchFilter: (option, search) => {
		    return option.text.substr(0, search.length).toLowerCase() == search.toLowerCase()
		  },
		  addable: function (value) {
		    // return false or null if you do not want to allow value to be submitted

		    var already_exists=false;
		  	
		  	//It will fetch angularjs '$scope' from given div element outside angularjs scope
		  	var scope = angular.element($("#wrapper")).scope();

		  	//This method can be used to perform operations on scope variables outside angularjs scope
		    scope.$apply(function(){
		        $.each(scope.current_all_cuisines_list, function (i, obj) {
				    if(obj.cuisine_name.toUpperCase()==value.toUpperCase())
				    {
				    	already_exists=true;
				    }
				});
				if(already_exists==false)
				{
			        scope.current_all_cuisines_list.push({'cuisine_id':value,'cuisine_name':value});
		    	}
		    });

		    if (already_exists === true) {return false}


		    else{
				// Return the value string
			    return value // Optional - value alteration // ex: value.toLowerCase()

			    // Optional - Return a valid data object. See methods/setData for list of valid options
			    return {
			      text: value,
			      value: value.toLowerCase()
			    }		    	
		    }

		  }

		})
	}

</script>



    <script type="text/javascript">


        var app = angular.module('MyApp', []);
        app.controller('MyController',  ['$scope','$filter', '$window', '$http', function($scope,$filter, $window, $http) {
        
    	$scope.current_all_cuisines_list=<?php echo json_encode($cuisines_list); ?>;

		//current_cuisines_list gives list of all current selected cuisines by user.
		$scope.current_cuisines_list=[];

    	$scope.Menu={'cuisines':[]};

		var tmp_cuisine={};
    	tmp_cuisine.cuisine_id = null;
        tmp_cuisine.cuisine_name = null;
        tmp_cuisine.food_items = [];

        $scope.Menu.cuisines.push(tmp_cuisine);
        
        var json_str = JSON.stringify($scope.Menu); 
        $scope.Menu=JSON.parse(json_str);

       	$scope.cuisine_changed=function(index, selected_cuisine_id){
        	$scope.Menu.cuisines[index].cuisine_id=selected_cuisine_id;
        	
        	//To add cuisine in Menu, here we are filtering current_all_cuisines_list to get cuisine_name based on selected_cuisine_id.
        	cuisine_object = $filter('filter')($scope.current_all_cuisines_list, function (d) {return d.cuisine_id === selected_cuisine_id;})[0];
        	$scope.Menu.cuisines[index].cuisine_name=cuisine_object.cuisine_name;
        }

        $scope.add_cuisine=function(index, selected_cuisine_id){
        	
        	$scope.Menu.cuisines[index].cuisine_id=selected_cuisine_id;
        	
        	//To add cuisine in Menu, here we are filtering current_all_cuisines_list to get cuisine_name based on selected_cuisine_id.
        	cuisine_object = $filter('filter')($scope.current_all_cuisines_list, function (d) {return d.cuisine_id === selected_cuisine_id;})[0];
        	$scope.Menu.cuisines[index].cuisine_name=cuisine_object.cuisine_name;

        	//alert(index+':'+selected_cuisine_id);
        	var tmp_food_item = {};
	        tmp_food_item.food_name = null;
	        tmp_food_item.food_price =null;
	        tmp_food_item.food_ingredients = null;
	        tmp_food_item.food_type = false;

	        $scope.Menu.cuisines[index].food_items[0]=tmp_food_item;
	        var json_str = JSON.stringify($scope.Menu); 
        	$scope.Menu=JSON.parse(json_str);


        }

        $scope.is_form_invalid=function()
        {
        	var is_invalid=false;

        	angular.forEach($scope.Menu.cuisines,function(item, index) {
        		var is_food_item_invalid=false;
        		angular.forEach(item.food_items,function(item, index){
        			if(item.food_name==null || item.food_price==null || item.food_price<0)
        			{
        				is_food_item_invalid=true;
        			}
        		});

      			if(item.cuisine_id==null || item.cuisine_id==name || item.food_items.length<1 || is_food_item_invalid==true)
      			{
      				is_invalid=true;
      				return is_invalid;
      			}
      		});
      		return is_invalid;

        }

        $scope.Add_more_food_item=function(index){
			var tmp_food_item = {};
	        tmp_food_item.food_name = null;
	        tmp_food_item.food_price =null;
	        tmp_food_item.food_ingredients = null;
	        tmp_food_item.food_type = false;

	        $scope.Menu.cuisines[index].food_items.push(tmp_food_item);
	        var json_str = JSON.stringify($scope.Menu); 
        	$scope.Menu=JSON.parse(json_str);        	
        }

        $scope.Add_more_cuisine=function()
        {
        	var new_cuisine_selectbox_id='selectbox'+($scope.Menu.cuisines.length);

        	var tmp_cuisine={};
        	tmp_cuisine.cuisine_id=null;
        	tmp_cuisine.cuisine_name=null;
        	tmp_cuisine.food_items=[];
        	
        	$scope.Menu.cuisines.push(tmp_cuisine);
        	
        	var json_str = JSON.stringify($scope.Menu); 
        	$scope.Menu=JSON.parse(json_str);  
        	
        	//here initialize_SlimSelect method is called before changes in loop are reflected in UI.So we have used $scope.$apply() that change models (if any), and a $digest cycle starts to ensure your changes are reflected in the view. However we should not use it.
        	$scope.$apply();

        	initialize_SlimSelect(new_cuisine_selectbox_id);

        }

        $scope.Remove_cuisine = function (index) {
            //Remove the whole cuisine from Menu using Index.
            $scope.Menu.cuisines.splice(index, 1);      
        }
        $scope.Remove_food_item = function (parent_index,index) {
            $scope.Menu.cuisines[parent_index].food_items.splice(index, 1);
        }

        $scope.submit_menu=function()
        {
        	$http({
			    method : "POST",
			      url : "restaurant_add_menu_data.php",
			      data:'menu_details='+JSON.stringify($scope.Menu),
			      headers:{'Content-Type': 'application/x-www-form-urlencoded'},
			  }).then(function mySuccess(response) {
			  	$('.messagediv').show();
			  	if(response.data=='success')
			  	{
        			$('.message').html("Menu inserted successfully");
        			window.reload();
			  	}
			    
			  }
			  );
        	/*$.ajax({
        		url:'restaurant_add_menu_data.php',
        		type:'POST',
        		data:{'menu_details':JSON.stringify($scope.Menu)},
        		success:function(result)
        		{
        			console.log(JSON.stringify($scope.Menu));
        			$('.messagediv').show();
        			$('.message').html(result);
        		}
        	});*/

        }

        /*$scope.Menu = {'cuisines':[
        	{
	        	'cuisine_id':'1',
	        	'cuisine_name':'gujarati',
	        	'food_items':[
	        		{
	        			'food_name':'dhokla',
	        			'food_price':120,
	        			'food_ingredients':'aa,bb,cc',
	        			'food_type':false
	        		},
	        		{
	        			'food_name':'dhokla',
	        			'food_price':120,
	        			'food_ingredients':'aa,bb,cc',
	        			'food_type':true
	        		}
	        	]
        	},
        	{
        		'cuisine_id':'2',
        		'cuisine_name':'punjabi',
        		'food_items':[
        			{
        				'food_name':'dhokla',
        				'food_price':120,
        				'food_ingredients':'aa,bb,cc',
        				'food_type':false
        			}
        		]
        	}
        ]};*/
             
   

        }]);
    </script>
