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
			max-width:130px;
		}
		.error{
			color:red;
		}
    </style>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.18.10/slimselect.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.18.10/slimselect.min.css" rel="stylesheet"></link>

    	<!-- row -->
    	<div class="row" style="background-color: white;">

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
                  			<label style="font-size: 13px;margin-bottom:2px;">Cuisine:</label>
                  			

                  			<input type="text" style="min-width:110px;" ng-model="cuisine.cuisine_name" class="form-control" ng-value="cuisine.cuisine_name" style="padding-bottom: 4px;" readonly/>

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
									
									<input type="text" style="min-width:110px;" ng-model="food_item.food_name" name="food_name_{{$parent.$index}}_{{$index}}" class="form-control" value="{{food_item.food_name}}" readonly/>
									
								</div>	

								<div class="col-md-2">

									<label style="font-size: 13px;margin-bottom:2px;">Price(INR): </label>

									<input type="number" class="form-control" ng-model="food_item.food_price" name="food_price_{{$parent.$index}}_{{$index}}" ng-value="{{food_item.food_price}}" readonly/>
									
								</div>

								<div class="col-md-2">
									<label style="font-size: 13px;margin-bottom:2px;">Ingredients: </label>

									<textarea rows="1" class="form-control food_ingredients" ng-model="food_item.food_ingredients" readonly>{{food_item.food_ingredients}}</textarea>
								</div>

								<div class="col-md-5">
									<br/>
									<!-- Rectangular switch -->
									<label style="font-size: 13px;">Veg&nbsp;</label>
									<label class="switch">
									  <input type="checkbox" ng-model="food_item.food_type" name="food_type_{{$parent.$index}}_{{$index}}" ng-checked="food_item.food_type" disabled>
									  <span class="slider round"></span>
									</label>
									<label style="font-size: 13px;">&nbsp;Non-veg</label>

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
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
          </ul>
        </div>
        <!-- /.col -->

        </form>
	</div>
  <!-- /.content-wrapper -->

<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>





