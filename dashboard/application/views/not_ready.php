<!DOCTYPE html>
<html>
<head>
<?php require_once ('components/styles.php');?>
<link href="<?=base_url();?>assets/plugins/notifications/notification.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>resources/clockpicker/bootstrap-clockpicker.min.css">      
<?php
if($notify==1){
?>
<script type="text/javascript">
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
   window.location.assign("<?=base_url();?>institution");
}, 2000);
</script>
<?php
};
?>


<style type="text/css">

thead tr:first-child {
  background: #ed1c40;
  color: #fff;
  border: none;
}

th:first-child,
td:first-child {
  padding: 0 15px 0 20px;
}

thead tr:last-child th {
  border-bottom: 3px solid #ddd;
}

tbody tr:hover {
  background-color: #FAFAFA;
  cursor: default;
}

tbody tr:last-child td {
  border: none;
}

tbody td {
  border-bottom: 1px solid #ddd;
}

td:last-child {
  text-align: right;
  padding-right: 10px;
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
   
    <div class="row">
    	<div class="col-md-12">
<div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title"><strong>ოპერატორების ხელმისაწვდომობა:</strong></h3>
        </div>
        <div class="panel-body">
               <div class="panel panel-border panel-primary">
                    <div class="row">
                         <form action="generation_excel" method="post" />
                    <div class="col-sm-4">
                    <label for="cname" class="control-label col-lg-5">აირჩიეთ ოპერატორი</label>

                       <select class="select2 form-control" name='user_id' id='by_users'>
                        <option value="0">ყველა ოპერატორი</option>

                       <?php
                       if(is_array($get_persons))
                       {
                           foreach($get_persons as $person):


                       ?>
                       <option value="<?=$person['person_id'];?>"><?=$person['first_name'];?>&nbsp;<?=$person['last_name'];?>&nbsp;</option>

                        <?php                                        
                       endforeach; }
                        ?>        
                       </select>
                    </div>
                    <div class="col-sm-4">
                       <label for="cname" class="control-label col-lg-12">კიდური თარიღები</label> 
                      <input class="form-control" id="start_date" name="start_date"  placeholder="DD/MM/YYYY-დან" type="text" style="width: 150px; float:left; margin-right: 15px;"/>



                      <input class="form-control" id="end_date" name="end_date" placeholder="DD/MM/YYYY-მდე" type="text" style="width: 150px;"/>
                    </div>
                  </div>

                    <br />
                    <button type="button" id="submit" name="submit" class="btn btn-primary">დამუშავება</button>
					
                    <a href=""  id="submit" class="btn btn-primary">გასუფთავება</a>
                     
                    
                     </div>
                    </form>
                </div>
                <div class="table-responsive" id="table-responsive">
                </div>
        </div>
</div>
    	</div>
    </div>
</div>


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
