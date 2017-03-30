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
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title">სერვისის დამატება</h3></div>
		<div class="panel-body">
<?php echo validation_errors('<div class="col-lg-offset-2 col-lg-9"><div class="alert alert-warning">', '</div></div>'); ?>
<?php if(!empty($error_message)) {  echo '<div class="col-lg-offset-2 col-lg-9"><div class="alert alert-warning">'.$error_message.'</div></div>'; }?> 
                
<div class="form">
 <form class="cmxform form-horizontal tasi-form" id="commentForm" method="POST" action="">
  <div class="form-group">
    <label for="cname" class="control-label col-lg-2">უწყების დასახელება</label>
    <div class="col-lg-9">
       <select class="select2 form-control" data-placeholder="Choose a Country..." name="repo_categories">
        <option value="#">აირჩიეთ უწყება</option>
          <?php 
                foreach ($sql_institutions as $institutions):														  
          ?>
          <option value="<?php echo $institutions['repo_category_id'];?>"><?php echo $institutions['category_name'];?></option>
          <?php endforeach; ?>
   </select>
    </div>
  </div>
<div class="form-group">
    <label for="cname" class="control-label col-lg-2">სერვისის დასახელება <img src="<?=base_url();?>assets/flags/geo.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="service_name_geo" type="text">
</div>
</div>
<div class="form-group">
<label for="cname" class="control-label col-lg-2">სერვისის დასახელება <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip" data-placement="bottom" title="რუსული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="service_name_rus" type="text" >
</div>
</div>
<div class="form-group">
<label for="cname" class="control-label col-lg-2">სერვისის დასახელება <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip" data-placement="bottom" title="ინგლისური"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="service_name_eng" type="text" >
</div>
</div>    
<div class="form-group">
<label for="cname" class="control-label col-lg-2" data-toggle="tooltip" data-placement="top" title="სამუშაო საათები 00:00 დან 00:00 მდე იგულისხმება 24 საათიანი მომსახურება">სამუშაო საათები</label>
    <div class="col-lg-3">
    <div class="clearfix">
    <div class="input-group clockpicker-with-callbacks">
            <input type="text" class="form-control" name="start_time" value="00:00">
            <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
            </span>
    </div>
    </div>													  
    </div>
    <div class="col-lg-3">
    <div class="clearfix">
    <div class="input-group clockpicker-with-callbacks">
            <input type="text" class="form-control" name="end_time" value="00:00">
            <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
            </span>
    </div>
    </div>													  
    </div>
  <script type="text/javascript">
  $('.clockpicker').clockpicker();
  </script>
</div>
		
<div class="form-group">
<div class="col-lg-offset-2 col-lg-9">
   <input type="submit" class="btn btn-primary" type="submit" name="add_service" value="შენახვა" />
   <input type="reset" class="btn btn-danger" type="submit"  value="გასუფთავება" />
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
 
		
		
<div class="panel panel-default">
 
	<div class="panel-body"> 
		<div class="portlet">
		<div class="portlet-heading bg-info">
		<h3 class="portlet-title">
		   სერვისები
		</h3>
                <div class="portlet-widgets">
                    <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                    <span class="divider"></span>
                    <a data-toggle="collapse" data-parent="#accordion1" href="#bg-success"><i class="ion-minus-round"></i></a>
                    <span class="divider"></span>
                    <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                </div>
                <div class="clearfix"></div>
		</div>
	   <div id="bg-success" class="panel-collapse collapse in">
	   <div class="portlet-body">
	   <table id="employee_grid" class="table table-condensed table-hover table-striped bootgrid-table" width="60%" cellspacing="0">
	   <tbody id="_editable_table">
	   <tr>
	    <th>სერვისის სახელწოდება</th>
	    <th>ჩართვის დრო</th>		
	    <th>გამორთვის დრო</th>
	    <th>სტატუსი</th>
	    <th>რედაქტირება</th>		
	    <th>წაშლა</th>
	  </tr>
	  <?php foreach($get_sql_services as $res) :?>
            <tr data-row-id="<?php echo $res['category_service_id'];?>">
            <td class="editable-col" contenteditable="true"  col-index='0' title="<?php echo $res['category_name'];?>" oldVal ="<?php echo $res['service_name_geo'];?>"><?php echo $res['service_name_geo'];?>
            / <?php echo $res['service_name_rus'];?> / <?php echo $res['service_name_eng'];?>
            </td>
            <td class="editable-col" contenteditable="true"  col-index='1' title="<?php echo $res['start_time'];?>" oldVal ="<?php echo $res['start_time'];?>"><?php echo $res['start_time'];?></td>
            <td class="editable-col" contenteditable="true"  col-index='2' title="<?php echo $res['end_time'];?>" oldVal ="<?php echo $res['end_time'];?>"><?php echo $res['end_time'];?></td>
            <td class="edit"> <a href="<?=base_url();?>add_services/edit/<?php echo $res['category_service_id'];?>" class="on-default" data-toggle="tooltip" data-placement="right" title="სტატუსი"><img src='<?=base_url()."assets/images/online.png" ?>'></a></td>
            <td class="edit"> <a href="<?=base_url();?>services/update_service/<?php echo $res['category_service_id'];?>" class="on-default" data-toggle="tooltip" data-placement="right" title="რედაქტირება"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
            <td class="delete"> <a href="#" class="on-default remove-row" data-toggle="tooltip" data-placement="right" title="წაშლა"><i class="fa fa-trash-o"></i></a></td>
		 
	  </tr>
	<?php endforeach;?>
	   </tbody>
	</table>
	</div>
	</div>
	</div>
	
	
	<div class="dd" id="nestable_list_3">
	<ol class="dd-list">
			 
		  
	</ol>
	</div>
	</div>
				</div>
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
