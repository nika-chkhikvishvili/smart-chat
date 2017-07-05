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
<script type="text/javascript">
        data = {};
        $(document).ready(function(){
         $.ajax({
            type: "POST", 
            url: "http://localhost/chat/stattistics/get_all_data/",
            data: data,
            success: function(html) {
                $("#response").html(html);
            }
        });    
            
        $('#submit').click(function() {
        data = {};
        data['service_id'] = $("#catID").val();
        data['user_id'] = $("#by_users").val(); 
        $.ajax({
            type: "POST", 
            url: "http://localhost/chat/stattistics/get_all_data/",
            data: data,
            success: function(html) {
                $("#response").html(html);
            }
        });     
        });
        });
</script>  


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
                            <div class="col-lg-6">
                                <div class="panel panel-border panel-primary">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">პარამეტრები</h3> 
                                    </div>
                                    <form action="" method="post" />
                                    <div class="panel-body"> 
                                    <label for="cname" class="control-label col-lg-3">აირჩიეთ სერვისი</label>
                                 
                                   <select class="select2 form-control" name='catID' id='catID'>
                                       <option value="#">ყველა სერვისი</option>
                                       <?php
                                       if(is_array($get_services))
                                       {
                                           foreach($get_services as $services):
                                       
                                       
                                       ?>
                                       <option value="<?=$services['category_service_id'];?>"><?=$services['service_name_geo'];?></option>
                                               
                                        <?php                                        
                                       endforeach; }
                                        ?>        
                                     </select>
                                </div>
                                    
                                 <div class="panel-body"> 
                                    <label for="cname" class="control-label col-lg-3">აირჩიეთ მომხმარებელი</label>
                                
                                   <select class="select2 form-control" name='by_users' id='by_users'>
                                       <?php
                                        var_dump($get_persons);
                                       ?>
                                       <option value="#">ყველა მომხმარებელი</option>
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
                                    <br />
                                    <button type="button" id="submit" class="btn btn-primary">დამუშავება</button>
                                 </div>
                                    </form>
                                </div></div>
                                
                            <div class="col-lg-6">
                                <div class="panel panel-border panel-primary">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">სტატისტიკური მონაცემები</h3> 
                                    </div> 
                                    <div class="panel-body"> 
                                        <div id="pie-chart">
                                            <div id="pie-chart-container" class="flot-chart" style="height: 320px">
                                                 <div name='response' id='response'></div>
                                            </div>
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
       
       
    
    </body>

</html>
