<footer class="main-footer">

    <strong>Copyright &copy; <a href="">HungerOnTrain</a>.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
<!-- <meta name="google-signin-client_id" content="201821190466-q8gaa2rlo141sq2lej0al12dqk99j4dq.apps.googleusercontent.com"> -->

<script type="text/javascript">
  var profile=null;
  var id_token=null;
  var authUser=null;

/*  $(document).ready(function onload() {  
          gapi.load('auth2', function() {         
            gapi.auth2.init(); 
          });
  });*/


  function onLoad() {
    //this function is called by apis.google.com/js/platform.js library onload 
    gapi.load('auth2', function() {
      gapi.auth2.init();
    });
  }

  function onSignIn(googleUser) {
    profile = googleUser.getBasicProfile();
    id_token = googleUser.getAuthResponse().id_token;
    $.ajax({
      type:'POST',
      url:'google_signin_data.php',
      data:{email:profile.getEmail(),name:profile.getName()},
      success:function(result){
        if(result=='success')
        {
          window.location.href='restaurant/restaurant_dashboard.php';
        }
      },
    });
  }

	function signOut()
	{

    var auth2 = gapi.auth2.getAuthInstance();
  	auth2.signOut().then(function () {
		});
		auth2.disconnect();
		$.ajax({
			type:'POST',
			url:'restaurant_logout.php',
			data:{logout_type:'google_logout'},
			success:function(result)
			{
				if(result=='success')
        {
          window.location.href='../restaurant_login.php';
        }
			}
		});
	}

</script>




<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<!-- For Bootstrap toggle switch -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<!-- For SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- To use any animations in bootstrap-notify.js, we need to also inculde another library called animate.css. Without animations like Bounce, fade from right, etc won't work -->
<link rel="stylesheet" type="text/css" href="bower_components/bootstrap_notify_js/animate.css">

<script type="text/javascript" src="bower_components/bootstrap_notify_js/bootstrap-notify.min.js"></script>

<style type="text/css">
  .alert-minimalist {
  border-radius: 3px;
  padding: 10px;
  }
</style>

<script type="text/javascript">
  $(document).ready(function() {
      
      $.ajax({
        url:'notification_check.php',
        method:'POST',
        data:{'action':'load_notifications_cart'},
        success: function(result)
        {
            var cart_notifications=JSON.parse(result);
            
            //It will fetch angularjs '$scope' from given div element outside angularjs scope
            var scope = angular.element($("#notifications")).scope();

            //This method can be used to perform operations on scope variables outside angularjs scope
            scope.$apply(function(){
                scope.current_all_notifications=cart_notifications;
            });

            notifications();
        }
      });
          
  });
</script>

<script type="text/javascript">
   var notif_app = angular.module('NotificationApp', ['angularMoment']);
  notif_app.controller('NotificationController',  ['$scope','$filter', '$window', '$http', function($scope,$filter, $window, $http) {

    $scope.current_all_notifications=[];
    
    $scope.new_order_ids=[];
    $scope.del_order_ids=[];
    $scope.delivery_per_assigned_order_ids=[];

    $scope.del_notification_ids=[];
    
    $scope.manage_notifications=function(new_notifications){
        
        var url = window.location.href;
        var filename = url.split('/').pop().split('#')[0].split('?')[0];
        
        $scope.new_order_ids=[];
        angular.forEach(new_notifications.new_orders, function(item){
          
          $scope.current_all_notifications.push(item);
          $scope.new_order_ids.push(item.order_id);

        });


        if(new_notifications.new_orders.length>0 && filename=='restaurant_orders.php')
        {
          //It will fetch angularjs '$scope' from given div element outside angularjs scope
          var scope = angular.element($("#content_wrapper")).scope();

          //This method can be used to perform operations on scope variables outside angularjs scope
          scope.$apply(function(){
              scope.get_new_orders_based_on_notif($scope.new_order_ids);
          });
        }


        $scope.delivery_per_assigned_order_ids=[];
        angular.forEach(new_notifications.delivery_person_assigned, function(item){
          var index=$scope.current_all_notifications.findIndex( record => record.order_id == item.order_id);
          $scope.current_all_notifications[index].notification_type= item.notification_type; //or =2
          
          $scope.delivery_per_assigned_order_ids.push(item.order_id);

        });

        if(new_notifications.delivery_person_assigned.length>0 && filename=='restaurant_orders.php')
        {
          //It will fetch angularjs '$scope' from given div element outside angularjs scope
          var scope = angular.element($("#content_wrapper")).scope();

          //This method can be used to perform operations on scope variables outside angularjs scope
          scope.$apply(function(){

              scope.delivery_per_assigned_based_on_notif($scope.delivery_per_assigned_order_ids);
              
          });

        }

        angular.forEach(new_notifications.orders_picked_up, function(item){
          var index=$scope.current_all_notifications.findIndex( record => record.order_id == item.order_id);
          $scope.current_all_notifications[index].notification_type= item.notification_type; //or =2
        });

        $scope.del_notification_ids=[];
        $scope.del_order_ids=[];
        angular.forEach(new_notifications.orders_delivered, function(item){
          var index=$scope.current_all_notifications.findIndex( record => record.order_id == item.order_id);
          $scope.current_all_notifications.splice(index, 1);
          
          $scope.del_order_ids.push(item.order_id);
          $scope.del_notification_ids.push(item.notification_id);
        });

        if(new_notifications.orders_delivered.length>0 && filename=='restaurant_orders.php')
        {
            //It will fetch angularjs '$scope' from given div element outside angularjs scope
            var scope = angular.element($("#content_wrapper")).scope();

            //This method can be used to perform operations on scope variables outside angularjs scope
            scope.$apply(function(){
                scope.remove_delivered_orders_based_on_notif($scope.del_order_ids);    
            });

        }
        
        if($scope.del_notification_ids.length>0){
          $http({
            method : "POST",
              url : "notification_check.php",
              data: $.param({'action':'delete_notifications','del_notification_ids':$scope.del_notification_ids}),
              headers:{'Content-Type': 'application/x-www-form-urlencoded'},
          }).then(function mySuccess(response) {
            //alert(response.data);             
            }
          );
        }


        
    };





/*    $scope.test_manage_notifications=function (new_notifications){
      $scope.test_notif={"new_orders":[{"notification_id":"303","notification_type":"0","order_id":"20190308101","created_at":"2019-03-21 20:43:22"},{"notification_id":"308","notification_type":"0","order_id":"20190308105","created_at":"2019-03-25 20:43:11"},{"notification_id":"307","notification_type":"0","order_id":"20190308102","created_at":"2019-03-21 20:45:22"},{"notification_id":"312","notification_type":"0","order_id":"20190308106","created_at":"2019-03-25 20:45:22"}],"delivery_person_assigned":[{"notification_id":"304","notification_type":"2","order_id":"20190308101","created_at":"2019-03-21 20:43:26"},{"notification_id":"309","notification_type":"2","order_id":"20190308105","created_at":"2019-03-25 20:43:44"}],"orders_picked_up":[{"notification_id":"305","notification_type":"3","order_id":"20190308101","created_at":"2019-03-21 21:20:22"},{"notification_id":"310","notification_type":"3","order_id":"20190308105","created_at":"2019-03-24 03:20:33"}],"orders_delivered":[{"notification_id":"306","notification_type":"4","order_id":"20190308101","created_at":"2019-03-21 22:20:22"},{"notification_id":"311","notification_type":"4","order_id":"20190308102","created_at":"2019-03-26 22:20:55"}]};

    };*/

  }]);

  
</script>









<script type="text/javascript">
    $('#status').bootstrapToggle();
    $('#status').on('change',function(){

      var status=$('#status').prop('checked');
      
      $.ajax({
        url:'restaurant_update_status_data.php',
        method:'POST',
        data:{'status':status},
        success: function(result)
        {
          if(result=='success')
          {
            //swal(title,description,type) from sweetalert.min.js
            if(status==true){
              swal("Online mode activated","","success")
            }
            else
            {
              swal("Offline mode activated","","success")
            }
          }
          else{
            alert(result);
          }
        }
      });
    });


    //Check and generate notifications
    function notifications() {

      $.ajax({
        url:'notification_check.php',
        method:'POST',
        data:{'action':'check_new_notification'},
        success: function(result)
        {
            var notifications=JSON.parse(result);

            var new_orders = notifications.new_orders;
            var delivery_person_assigned= notifications.delivery_person_assigned;
            var orders_picked_up = notifications.orders_picked_up;
            var orders_delivered= notifications.orders_delivered;

            if(new_orders.length>0 || delivery_person_assigned.length>0 || orders_picked_up.length>0 || orders_delivered.length>0)
            {
              $('<audio id="notification_audio"><source src="notification_ping.mp3" type="audio/mp3"></audio>').appendTo('body');
              $('#notification_audio')[0].play();
            }

            //It will fetch angularjs '$scope' from given div element outside angularjs scope
            var scope = angular.element($("#notifications")).scope();

            //This method can be used to perform operations on scope variables outside angularjs scope
            scope.$apply(function(){
                scope.manage_notifications(notifications);
            });

            if(new_orders.length>0)
            {
                var new_orders_message='';
                var new_orders_notif_limit=1;
                if(new_orders.length>1)
                {
                  new_orders_notif_limit=2;
                }
                for(var i=0;i<new_orders_notif_limit;i++)
                {
                  new_orders_message=new_orders_message+'<div class="row"><div class="col-md-4">#'+new_orders[i].order_id+'</div><div class="col-md-8"> <span class="glyphicon glyphicon-time" style="font-size:12px;"></span> '+new_orders[i].created_at+'</div></div>';
                }
                if(new_orders.length>2)
                {
                  new_orders_message=new_orders_message+'<div class="row"><div class="col-md-4">(+'+(new_orders.length-2)+' more)</div></div>';
                }

                $.notify({
                  icon: 'images/foodorder3.png',
                  title: new_orders.length+ ' new order(s)',
                  message: new_orders_message
                },{
                  element: 'body',
                  position: null,
                  type: "info",
                  allow_dismiss: true,
                  newest_on_top: false,
                  showProgressbar: false,
                  type: 'minimalist',
                  delay: 5000,
                  icon_type: 'image',
                  template: '<div data-notify="container" class="col-xs-11 col-md-3 alert alert-{0}" role="alert" style="background-color: #080707;">' +
                    '<div class="row" style="color:white;">'+
                    '<div class="col-xs-3 col-md-2">'+
                    '<img style="height: 50px;margin-top:4px;background-color:white;" data-notify="icon" class="img-circle pull-left">' +
                    '</div>'+
                    '<div class="col-xs-9 col-md-10">'+
                    '<span data-notify="title" style="font-size:16px;"><b>{1}</b></span>' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss" style="margin-top:-5px;"><span style="color:white;">&nbsp;×&nbsp;</span></button>' +
                    '<hr style="margin-top:0px;margin-bottom:1px;margin-right:15px;border-style:dashed;">'+
                    '<span data-notify="message">{2}</span>' + 
                    '</div>'+
                  '</div>',
                  animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                  },
                });
            }




            //delivery person assigned notification
            if(delivery_person_assigned.length>0)
            {
                var delivery_person_message='';
                var delivery_person_notif_limit=1;
                if(delivery_person_assigned.length>1)
                {
                  delivery_person_notif_limit=2;
                }
                for(var i=0;i<delivery_person_notif_limit;i++)
                {
                  delivery_person_message=delivery_person_message+'<div class="row"><div class="col-md-4">#'+delivery_person_assigned[i].order_id+'</div><div class="col-md-8"> <span class="glyphicon glyphicon-time" style="font-size:12px;"></span> '+delivery_person_assigned[i].created_at+'</div></div>';
                }
                if(delivery_person_assigned.length>2)
                {
                  delivery_person_message=delivery_person_message+'<div class="row"><div class="col-md-4">(+'+(delivery_person_assigned.length-2)+' more)</div></div>';
                }

                $.notify({
                  icon: 'images/delivery_person2.png',
                  title: delivery_person_assigned.length+' delivery person(s) assigned',
                  message: delivery_person_message
                },{
                  element: 'body',
                  position: null,
                  type: "info",
                  allow_dismiss: true,
                  newest_on_top: false,
                  showProgressbar: false,
                  type: 'minimalist',
                  delay: 5000,
                  icon_type: 'image',
                  template: '<div data-notify="container" class="col-xs-11 col-md-3 alert alert-{0}" role="alert" style="background-color: rgb(210, 30, 30);">' +
                    '<div class="row" style="color:white;">'+
                    '<div class="col-xs-3 col-md-2">'+
                    '<img style="height: 50px;margin-top:4px;background-color:white;" data-notify="icon" class="img-circle pull-left">' +
                    '</div>'+
                    '<div class="col-xs-9 col-md-10">'+
                    '<span data-notify="title" style="font-size:16px;"><b>{1}</b></span>' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss" style="margin-top:-5px;"><span style="color:white;">&nbsp;×&nbsp;</span></button>' +
                    '<hr style="margin-top:0px;margin-bottom:1px;margin-right:15px;border-style:dashed;">'+
                    '<span data-notify="message">{2}</span>' + 
                    '</div>'+
                  '</div>',
                  animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                  },
                });
            }





            //Order picked up notification
            if(orders_picked_up.length>0)
            {
                var orders_picked_up_message='';
                var orders_picked_up_notif_limit=1;
                if(orders_picked_up.length>1)
                {
                  orders_picked_up_notif_limit=2;
                }
                for(var i=0;i<orders_picked_up_notif_limit;i++)
                {
                  orders_picked_up_message=orders_picked_up_message+'<div class="row"><div class="col-md-4">#'+orders_picked_up[i].order_id+'</div><div class="col-md-8"> <span class="glyphicon glyphicon-time" style="font-size:12px;"></span> '+orders_picked_up[i].created_at+'</div></div>';
                }
                if(orders_picked_up.length>2)
                {
                  orders_picked_up_message=orders_picked_up_message+'<div class="row"><div class="col-md-4">(+'+(orders_picked_up.length-2)+' more)</div></div>';
                }

                $.notify({
                  icon: 'images/order_picked2.png',
                  title: orders_picked_up.length+' order(s) picked up',
                  message: orders_picked_up_message
                },{
                  element: 'body',
                  position: null,
                  type: "info",
                  allow_dismiss: true,
                  newest_on_top: false,
                  showProgressbar: false,
                  type: 'minimalist',
                  delay: 5000,
                  icon_type: 'image',
                  template: '<div data-notify="container" class="col-xs-11 col-md-3 alert alert-{0}" role="alert" style="background-color: rgb(30, 65, 93);">' +
                    '<div class="row" style="color:white;">'+
                    '<div class="col-xs-3 col-md-2">'+
                    '<img style="height: 50px;margin-top:4px;background-color:white;" data-notify="icon" class="img-circle pull-left">' +
                    '</div>'+
                    '<div class="col-xs-9 col-md-10">'+
                    '<span data-notify="title" style="font-size:16px;"><b>{1}</b></span>' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss" style="margin-top:-5px;"><span style="color:white;">&nbsp;×&nbsp;</span></button>' +
                    '<hr style="margin-top:0px;margin-bottom:1px;margin-right:15px;border-style:dashed;">'+
                    '<span data-notify="message">{2}</span>' + 
                    '</div>'+
                  '</div>',
                  animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                  },
                });
            }




            //Order Delivered notification
            if(orders_delivered.length>0)
            {
                var orders_delivered_message='';
                var orders_delivered_notif_limit=1;
                if(orders_delivered.length>1)
                {
                  orders_delivered_notif_limit=2;
                }
                for(var i=0;i<orders_delivered_notif_limit;i++)
                {
                  orders_delivered_message=orders_delivered_message+'<div class="row"><div class="col-md-4">#'+orders_delivered[i].order_id+'</div><div class="col-md-8"> <span class="glyphicon glyphicon-time" style="font-size:12px;"></span> '+orders_delivered[i].created_at+'</div></div>';
                }
                if(orders_delivered.length>2)
                {
                  orders_delivered_message=orders_delivered_message+'<div class="row"><div class="col-md-4">(+'+(orders_delivered.length-2)+' more)</div></div>';
                }

                $.notify({
                  icon: 'images/delivered.png',
                  title: orders_delivered.length+' order(s) delivered',
                  message: orders_delivered_message
                },{
                  element: 'body',
                  position: null,
                  type: "info",
                  allow_dismiss: true,
                  newest_on_top: false,
                  showProgressbar: false,
                  type: 'minimalist',
                  delay: 5000,
                  icon_type: 'image',
                  template: '<div data-notify="container" class="col-xs-11 col-md-3 alert alert-{0}" role="alert" style="background-color: rgb(34, 144, 81);">' +
                    '<div class="row" style="color:white;">'+
                    '<div class="col-xs-3 col-md-2">'+
                    '<img style="height: 50px;margin-top:4px;background-color:white;" data-notify="icon" class="img-circle pull-left">' +
                    '</div>'+
                    '<div class="col-xs-9 col-md-10">'+
                    '<span data-notify="title" style="font-size:16px;"><b>{1}</b></span>' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss" style="margin-top:-5px;"><span style="color:white;">&nbsp;×&nbsp;</span></button>' +
                    '<hr style="margin-top:0px;margin-bottom:1px;margin-right:15px;border-style:dashed;">'+
                    '<span data-notify="message">{2}</span>' + 
                    '</div>'+
                  '</div>',
                  animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                  },
                });
              }


        }
      });
      
      

      setTimeout(notifications, 7000);


    }

    //end function notifications


</script>

</body>
</html>
