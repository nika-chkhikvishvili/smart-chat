<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="Smart Logic  Open Source Chat System">
<meta name="author" content="Coderthemes">
<link rel="shortcut icon" href="<?=base_url();?>assets/images/favicon_1.ico">
<title>ჩეთის ადმინისტრატორი</title>
<link href="<?=base_url();?>assets/plugins/nestable/jquery.nestable.css" rel="stylesheet">
<link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?=base_url();?>assets/css/core.css" rel="stylesheet" type="text/css">
<link href="<?=base_url();?>assets/css/icons.css" rel="stylesheet" type="text/css">
<link href="<?=base_url();?>assets/css/components.css" rel="stylesheet" type="text/css">
<link href="<?=base_url();?>assets/css/pages.css" rel="stylesheet" type="text/css">
<link href="<?=base_url();?>assets/css/menu.css" rel="stylesheet" type="text/css">
<link href="<?=base_url();?>assets/css/responsive.css" rel="stylesheet" type="text/css">
<script src="<?=base_url();?>assets/js/modernizr.min.js"></script>
<link href="<?=base_url();?>assets/plugins/notifications/notification.css" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<script src="<?=base_url();?>assets/js/jquery.min.js"></script>
<script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>

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
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading bg-light">
        <h3 class="panel-title">საძიებო პანელი</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
			 <form action="" method="post" />
                <div class="col-md-2">
                    <div class="">
                        <label for="firstname" class="control-label">მომხ.სახელი</label>
                        <input type="text" class="form-control" name="firstname" value="<?php echo set_value('firstname'); ?>" id="firstname" placeholder="">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="lastname" class="control-label">მომხ.გვარი</label>
                        <input type="text" class="form-control" name="lastname" value="<?php echo set_value('lastname'); ?>"  id="lastname" placeholder="">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="lastname" class="control-label">სერვისი</label>
                        <select class="select2 form-control" name="service_id" id='catID'>
                      <option value="">ყველა სერვისი</option>
                       <?php
                       if(is_array($get_services))
                       {
                           foreach($get_services as $services):


                       ?>
                       <option value="<?=$services['category_service_id'];?>" <?php echo set_select('service_id',  $services['category_service_id']); ?>><?=$services['service_name_geo'];?></option>

                        <?php                                        
                       endforeach; }
                        ?>        
                    </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="lastname" class="control-label">ოპერატორი</label>
                         <select class="form-control" id="sel1" name="operator_name">
                         <option value="">ყველა ოპერატორი</option>   
                          
                        <?php
                        foreach ($persons as $get_persons):
						 ?>
                            <option value='<?=$get_persons['person_id'];?>' <?php echo set_select('operator_name',  $get_persons['person_id']); ?>><?=$get_persons['first_name'];?> &nbsp; <?=$get_persons['last_name']; ?></option>
						 <?php	
                        endforeach;
                        ?>
                             </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="lastname" class="control-label">Start </label>
                        <input type="text" class="form-control"  placeholder="DD/MM/YYYY-დან" name="start_date"  id="lastname">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <label for="lastname" class="control-label">End </label>
                        <input type="text" class="form-control"  placeholder="DD/MM/YYYY-დან" name="end_date"  id="lastname">
                    </div>
                </div>
            </div>
        </div>
		<br />
		 <div class="col-md-3">
					 <input  type="submit" name="submit" class="btn btn-primary" value="დამუშავება">
                       <a href="<?=base_url();?>history" name="reset" class="btn btn-primary"> გასუფთავება</a>
                   
                   
      </div>
	</form>
<div class="row">
<div class="col-lg-4">

</div>
</div>
        </div>
    </div>
</div>
</div>                        
                        
 <div class="row">
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading bg-warning">
        <h3 class="panel-title">ჩეთის ისტორია</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>მომხმარებელი</th>
                                <th>ოპერატორი</th>
                                <th>სერვისი</th>
                                <th>საუბრის დრო</th>
                                <th>ისტორია</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $record = array();
                             $name = array();
                             
                            if(!empty($history))
                            {
                               
                                foreach($history as $key=>$value){
                                  if(!in_array($value['chat_id'], $name)){
                                         $name[] = $value['chat_id'];
                                         $record[$key] = $value;
                                  }

                                }
                              
                                foreach ($record as $banlist):


                            ?>
                            <tr>
                                <td></td>
                                <td><?=$banlist['online_users_name'];?>&nbsp;<?=$banlist['online_users_lastname'];?></td>
                                <td><?=$banlist['first_name'];?>&nbsp;<?=$banlist['last_name'];?></td>
                                <td><?=$banlist['service_name_geo'];?></td>
                                <td><?=$banlist['add_date'];?></td>
                                <td>
                                <a href="<?=base_url();?>view_history/<?=$banlist['chat_id'];?>" class="btn btn-primary btn-rounded waves-effect waves-light m-b-5">საუბრის ისტორია</a>
                               
                                </td>

                            </tr>
                           <?php
                                endforeach; }
                           ?>
                        </tbody>
                    </table>
                </div>
                  <?php echo @$links; ?>
            </div>
        </div>
<div class="row">
<div class="col-lg-4">

</div>
</div>
        </div>
    </div>
</div>
</div> <!-- End row -->


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
        <script src="<?=base_url();?>assets/plugins/tagsinput/jquery.tagsinput.min.js"></script>
        <script src="<?=base_url();?>assets/plugins/toggles/toggles.min.js"></script>
        <script src="<?=base_url();?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/plugins/colorpicker/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/plugins/jquery-multi-select/jquery.multi-select.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/plugins/jquery-multi-select/jquery.quicksearch.js"></script>
        <script src="<?=base_url();?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?=base_url();?>assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
       
     <script>
	$(document).ready(function(){
		var start_date=$('input[name="start_date"]'); //our date input has the name "date"		
		var end_date=$('input[name="end_date"]'); //our date input has the name "date"		
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		start_date.datepicker({
			format: 'dd-mm-yyyy',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
                end_date.datepicker({
			format: 'dd-mm-yyyy',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
</script>
    </body>

</html>
