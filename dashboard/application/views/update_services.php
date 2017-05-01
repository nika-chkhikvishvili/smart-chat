<!DOCTYPE html>
<html>
<head>
<?php require_once ('components/styles.php');?>
<link href="<?=base_url();?>assets/plugins/notifications/notification.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>resources/clockpicker/bootstrap-clockpicker.min.css"> 
<script src="<?=base_url();?>assets/js/jquery.min.js"></script>
<script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>
<?php
if($notify==1){
?>
<script type="text/javascript">
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
   window.location.assign("<?=base_url();?>services");
}, 2000);
</script>
<?php
};
?>
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
<div class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">ნატალია<span class="caret"></span></a>
<ul class="dropdown-menu">
    <li><a href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile<div class="ripple-wrapper"></div></a></li>
    <li><a href="javascript:void(0)"><i class="md md-settings"></i> Settings</a></li>
    <li><a href="javascript:void(0)"><i class="md md-lock"></i> Lock screen</a></li>
    <li><a href="javascript:void(0)"><i class="md md-settings-power"></i> Logout</a></li>
</ul>
</div>

<p class="text-muted m-0">ადმინისტრატორი</p>
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
<?php
if($notify==1){
  echo '<div class="alert alert-info" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  სერვისი განახლებულია
</div>';  
}
?>

	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title">სერვისის განახლება</h3></div>
		<div class="panel-body">
        <div class="form">
	<form class="cmxform form-horizontal tasi-form" id="commentForm" method="POST" action="">
            <div class="form-group">
                <label for="cname" class="control-label col-lg-2">უწყების დასახელება</label>
                <div class="col-lg-9">
                <select class="select2 form-control" data-placeholder="Choose a Country..." name="repo_category_id">
                <option value="#" name="category">აირჩიეთ უწყება</option>
                  <?php 
                        foreach ($sql_institutions as $institutions):														  
                  ?>
                  <option value="<?php echo $institutions['repo_category_id'];?>" <?php echo set_select('category', $institutions['repo_category_id'], ($institutions['repo_category_id'] == $service['repo_category_id'])); ?> ><?php echo $institutions['category_name'];?></option>
                  <?php endforeach; ?>
                  </select>
                </div>
		</div>
		<div class="form-group">
                <label for="cname" class="control-label col-lg-2">სერვისის დასახელება</label>
                <div class="col-lg-9">
                        <input class="form-control" id="cname" name="service_name_geo" value="<?=$service['service_name_geo']?>" type="text" required="" aria-required="true">
                </div>
		</div>
                <div class="form-group">
                <label for="cname" class="control-label col-lg-2">სერვისის დასახელება <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip" data-placement="bottom" title="რუსული"></label>
                <div class="col-lg-9">
                        <input class="form-control" id="cname" name="service_name_rus" value="<?=$service['service_name_rus']?>" type="text" >
                </div>
                </div>
                <div class="form-group">
                <label for="cname" class="control-label col-lg-2">სერვისის დასახელება <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip" data-placement="bottom" title="ინგლისური"></label>
                <div class="col-lg-9">
                        <input class="form-control" id="cname" name="service_name_eng" value="<?=$service['service_name_eng']?>"  type="text" >
                </div>
                </div>  
		 <div class="form-group">
                <label for="cname" class="control-label col-lg-2" data-toggle="tooltip" data-placement="top" title="სამუშაო საათები 00:00 დან 00:00 მდე იგულისხმება 24 საათიანი მომსახურება">სამუშაო საათები</label>
                <div class="col-lg-3">
                <div class="clearfix">
                <div class="input-group clockpicker-with-callbacks">
                <input type="text" class="form-control" name="start_time" value="<?=$service['start_time']?>">
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-time"></span>
                </span>
                </div>
                </div>													  
                </div>
		<div class="col-lg-3">
                <div class="clearfix">
                <div class="input-group clockpicker-with-callbacks">
                <input type="text" class="form-control" name="end_time" value="<?=$service['end_time']?>">
                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                </span>
                </div>
                </div>													  
              </div>
            
		</div>
		
		<div class="form-group">
                <div class="col-lg-offset-2 col-lg-9">
                <input type="submit" class="btn btn-primary" type="submit" name="update" value="განახლება" />
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
