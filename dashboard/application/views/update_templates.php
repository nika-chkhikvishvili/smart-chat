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
   window.location.assign("<?=base_url();?>templates");
}, 2000);
</script>
<?php
};
?>
        <script type="text/javascript">
        $(document).ready(function(){      
             // delete the entry once we have confirmed that it should be deleted
                $('.delete').click(function() {
                data = {};			
                data['id'] = $(this).parent('tr').attr('data-row-id');
                        var parent = $(this).closest('tr');
                         if(confirm('დარწმუნებული ხართ რომ გინდათ შაბლონის ტექსტის წაშლა?'))
                         {
                         $.ajax({
                                type: "POST",  
                                  url: "<?=base_url()."templates/delete_templates";?>",  
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
.val_notifications p {color:red;} 
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
		<div class="panel-heading"><h3 class="panel-title">შაბლონის რედაქტირება</h3></div>
		<div class="panel-body">

<?php
if($notify==1){
  echo '<div class="alert alert-success" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  შაბლონის ტექსტი განახლებულია
</div>';  
}
?> 

                    
                    <div class="form"> 
  
 <form class="cmxform form-horizontal tasi-form" id="commentForm" method="POST" action="">
  <div class="form-group">
    <label for="cname" class="control-label col-lg-2">სერვისის დასახელება</label>
    <div class="col-lg-9">
       <select class="select2 form-control" data-placeholder="Choose a Country..." name="service_id">
        <option value="0">ყველა სერვისისთვის</option>
         <?php 
            foreach ($sql_services as $institutions):														  
           ?>
           <option value="<?php echo $institutions['category_service_id'];?>" <?php echo set_select('category', $institutions['category_service_id'], ($institutions['category_service_id'] == $sql_templates['service_id'])); ?> ><?php echo $institutions['service_name_geo'];?></option>
           <?php endforeach; ?>
   </select>
    </div>
  </div>
<div class="form-group">
<label for="cname" class="control-label col-lg-2">შაბლონის ტექსტი <img src="<?=base_url();?>assets/flags/geo.png" data-toggle="tooltip"  data-placement="bottom" title="ქართული"></label>
<div class="col-lg-9">
    <input class="form-control" id="cname" name="template_text_ge" value="<?php echo $sql_templates['template_text_ge'];?>" type="text">
<div class="val_notifications"><?php echo form_error('template_text_ge'); ?></div> 
</div>
</div>
<div class="form-group">
<label for="cname" class="control-label col-lg-2"> <img src="<?=base_url();?>assets/flags/rus.png" data-toggle="tooltip" data-placement="bottom" title="რუსული"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="template_text_ru"  value="<?php echo $sql_templates['template_text_ru'];?>"  type="text" >
</div>
</div>
<div class="form-group">
<label for="cname" class="control-label col-lg-2"> <img src="<?=base_url();?>assets/flags/usa.png" data-toggle="tooltip" data-placement="bottom" title="ინგლისური"></label>
<div class="col-lg-9">
<input class="form-control" id="cname" name="template_text_en" value="<?php echo $sql_templates['template_text_en'];?>" type="text" >
</div>
</div>  

		
<div class="form-group">
<div class="col-lg-offset-2 col-lg-9">
   <input type="submit" class="btn btn-primary" type="submit" name="update" value="განახლება" />
   <input type="reset" class="btn btn-danger" type="submit"   value="გასუფთავება" />
</div>
</div>
</form>
</div> <!-- .form -->
<div class="col-lg-offset-2 col-lg-9"><div id="msg" class="alert">                                         

</div></div>
</div> <!-- panel-body -->
</div> <!-- panel -->
					</div>

					  <!-- end of modal info -->
                        </div>
                        <!-- Start Widget -->
               
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
    
    </body>

</html>
