<!DOCTYPE html>
<html>
<head>
        <?php require_once ('components/styles.php');?>
	<link href="<?=base_url();?>assets/plugins/notifications/notification.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>resources/clockpicker/bootstrap-clockpicker.min.css">      
<script type="text/javascript">
        $(document).ready(function(){        
            
                $('.delete').click(function() {
                data = {};			
                data['id'] = $(this).parent('tr').attr('data-row-id');
                        var parent = $(this).closest('tr');
                         if(confirm('დარწმუნებული ხართ რომ გინდათ უწყების წაშლა?'))
                         {
                         $.ajax({
                                type: "POST",  
                                  url: "http://localhost/chat/services/delete_service",  
                                  cache:false,  
                                  data: data,
                                  dataType: "json",   
                                beforeSend: function() {
                                        parent.animate({'backgroundColor':'#fb6c6c'},300);
                                },
                                success: function(response) {

                                //$("#loading").hide();
                                        if(response.status) {
                                          $.Notification.notify('success','top center', 'ყურადღება', response.msg);
                                          setTimeout(function(){window.location.reload(1); }, 3000);		
                                        } else {
                                           $.Notification.notify('success','top center', 'ყურადღება', response.msg);
                                           setTimeout(function(){window.location.reload(1); }, 3000);		
                                        }
                                }
                        });	 
                         }

                });

        });

        </script>
<style type="text/css">
 img {
    opacity: 0.5;
    filter: alpha(opacity=50); /* For IE8 and earlier */
}

img:hover {
    opacity: 1.0;
    filter: alpha(opacity=100); /* For IE8 and earlier */
}
</style>        
    </head>


<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
                    <?php require_once('components/admin_topbar.php');?>
        <!-- Top Bar End -->


        <!-- ========== Left Sidebar Start ========== -->

<div class="left side-menu">
<div class="sidebar-inner slimscrollleft">
<div class="user-details">
	<div class="pull-left">
	<img src="<?=base_url();?>assets/images/users/girl.png" alt="" class="thumb-md img-circle">
	</div>
	<div class="user-info">
	 <?php require_once 'components/user_info.php'; ?>
	</div>
</div>
<!--- Divider -->
<div id="sidebar-menu">
 <?php require_once('components/admin_menu.php'); ?>
</div>
<div class="clearfix"></div>
</div>
</div>
<!-- Left Sidebar End --> 
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->                      
<div class="content-page">
<!-- Start content -->
<div class="content">
<div class="container">
<!-- Start Widget -->
<div class="row">
<div class="col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title">ავტომოპასუხე</h3></div>
		<div class="panel-body">

                
<div class="form">
<form class="cmxform form-horizontal tasi-form" id="commentForm" method="POST" action="">

<div class="form-group">
    <label for="cname" class="control-label col-lg-3">მისასალმებელი ტექსტი <img src="<?=base_url();?>assets/flags/geo.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="start_chating_geo" value="<?=$get_answering['start_chating_geo'];?>" type="text">
</div>
</div>
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">მისასალმებელი ტექსტი <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip" data-placement="bottom" title="რუსული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="start_chating_rus" value="<?=$get_answering['start_chating_rus'];?>" type="text">
</div>
</div>     
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">მისასალმებელი ტექსტი <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip" data-placement="bottom" title="რუსული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="start_chating_eng" value="<?=$get_answering['start_chating_eng'];?>" type="text">
</div>
</div>  
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">მეილი, რომელზეც offline <br />მიღებული შეტყობინებები მოვა </label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="mail_offline" value="<?=$get_answering['mail_offline'];?>" type="text">
</div>
</div>
     
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">კლიენტთან ჩატის გაწყვეტის ტექსტი <img src="<?=base_url();?>assets/flags/geo.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="connect_failed_geo" value="<?=$get_answering['connect_failed_geo'];?>" type="text">
</div>
</div>
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">კლიენტთან ჩატის გაწყვეტის ტექსტი <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="connect_failed_rus" value="<?=$get_answering['connect_failed_rus'];?>" type="text">
</div>
</div>      
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">კლიენტთან ჩატის გაწყვეტის ტექსტი <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="connect_failed_eng" value="<?=$get_answering['connect_failed_eng'];?>" type="text">
</div>
</div>  
    
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">მომხმარებლის დაბოლიკვის ტექსტი <img src="<?=base_url();?>assets/flags/geo.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="user_block_geo" value="<?=$get_answering['user_block_geo'];?>" type="text">
</div>
</div> 
 
<div class="form-group">
 <label for="cname" class="control-label col-lg-3">მომხმარებლის დაბოლიკვის ტექსტი <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="user_block_rus" value="<?=$get_answering['user_block_rus'];?>" type="text">
</div>
</div> 

<div class="form-group">
<label for="cname" class="control-label col-lg-3">მომხმარებლის დაბოლიკვის ტექსტი <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="user_block_eng" value="<?=$get_answering['user_block_eng'];?>" type="text">
</div>
</div> 
    
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">ავტომოპასუხის ტექსტი <img src="<?=base_url();?>assets/flags/geo.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="auto_answering_geo" value="<?=$get_answering['auto_answering_geo'];?>" type="text">
</div>
</div>

<div class="form-group">
    <label for="cname" class="control-label col-lg-3">ავტომოპასუხის ტექსტი <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="auto_answering_rus" value="<?=$get_answering['auto_answering_rus'];?>" type="text">
</div>
</div> 
    
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">ავტომოპასუხის ტექსტი <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="auto_answering_eng" value="<?=$get_answering['auto_answering_eng'];?>" type="text">
</div>
</div>  
    
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">ავტომოპასუხის განმეორების ინტერვალი</label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="repeat_auto_answering" value="<?=$get_answering['repeat_auto_answering'];?>" type="text">
</div>
</div>
     
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">არასამუშაო საათების შემთხვევაში ავტომოპასუხის <img src="<?=base_url();?>assets/flags/geo.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="time_off_geo" value="<?=$get_answering['time_off_geo'];?>" type="text">
</div>
</div>
    
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">არასამუშაო საათების შემთხვევაში ავტომოპასუხის <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="time_off_rus" value="<?=$get_answering['time_off_rus'];?>" type="text">
</div>
</div>
    
<div class="form-group">
    <label for="cname" class="control-label col-lg-3">არასამუშაო საათების შემთხვევაში ავტომოპასუხის <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="time_off_eng" value="<?=$get_answering['time_off_eng'];?>" type="text">
</div>
</div>      
		
<div class="form-group">
<div class="col-lg-offset-2 col-lg-9">
   <input type="submit" class="btn btn-primary" type="submit" name="save_answering" value="შენახვა" />
   
</div>
</div>
</form>
</div> <!-- .form -->
<div class="col-lg-offset-2 col-lg-9"><div id="msg" class="alert">                                         

</div></div>
</div> <!-- panel-body -->
</div> <!-- panel -->
</div> <!-- col -->
</div> <!-- col -->
 <div class="col-md-12">

			</div>
		</div>
		<!-- end row -->

	</div> <!-- container -->
			   
</div> <!-- content -->

                <footer class="footer text-right">
                    2016 © Smart Logic.
                </footer>

            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


            <!-- Right Sidebar -->
			<?php require_once('components/admin_online_chatlist.php');?>
            <!-- /Right-bar -->

        </div>
        <!-- END wrapper -->


    
  
         <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        
      
        <script src="<?=base_url();?>assets/js/detect.js"></script>
        <script src="<?=base_url();?>assets/js/fastclick.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.blockUI.js"></script>
        <script src="<?=base_url();?>assets/js/waves.js"></script>
        <script src="<?=base_url();?>assets/js/wow.min.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.scrollTo.min.js"></script>

        <script src="<?=base_url();?>assets/js/jquery.app.js"></script>
		
	<script src="<?=base_url();?>assets/plugins/notifyjs/dist/notify.min.js"></script>
        <script src="<?=base_url();?>assets/plugins/notifications/notify-metro.js"></script>
        <script src="<?=base_url();?>assets/plugins/notifications/notifications.js"></script>

      
        <!--script for this page only-->
        <script src="<?=base_url();?>assets/plugins/nestable/jquery.nestable.js"></script>
       
        

	<script type="text/javascript" src="<?=base_url();?>resources/clockpicker/bootstrap-clockpicker.min.js"></script>
	<script type="text/javascript">
	$('.clockpicker').clockpicker()
		.find('input').change(function(){
			console.log(this.value);
		});
	var input = $('#single-input').clockpicker({
		placement: 'bottom',
		align: 'left',
		autoclose: true,
		'default': 'now'
	});

	$('.clockpicker-with-callbacks').clockpicker({
			donetext: 'Done',
			init: function() { 
				console.log("colorpicker initiated");
			},
			beforeShow: function() {
				console.log("before show");
			},
			afterShow: function() {
				console.log("after show");
			},
			beforeHide: function() {
				console.log("before hide");
			},
			afterHide: function() {
				console.log("after hide");
			},
			beforeHourSelect: function() {
				console.log("before hour selected");
			},
			afterHourSelect: function() {
				console.log("after hour selected");
			},
			beforeDone: function() {
				console.log("before done");
			},
			afterDone: function() {
				console.log("after done");
			}
		})
		.find('input').change(function(){
			console.log(this.value);
		});

	// Manually toggle to the minutes view
	$('#check-minutes').click(function(e){
		// Have to stop propagation here
		e.stopPropagation();
		input.clockpicker('show')
				.clockpicker('toggleView', 'minutes');
	});
	if (/mobile/i.test(navigator.userAgent)) {
		$('input').prop('readOnly', true);
	}
</script>
    </body>

</html>
