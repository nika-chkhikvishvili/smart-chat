<!DOCTYPE html>
<html>
<head>
<?php require_once ('components/styles.php');?>
<link href="<?=base_url();?>assets/plugins/notifications/notification.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>resources/clockpicker/bootstrap-clockpicker.min.css"> 
<script src="<?=base_url();?>assets/js/jquery.min.js"></script>
<script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>
<style type="text/css">
/* layout.css Style */


.image-preview-input-title {
    margin-left:2px;
}

.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}

#img-upload{
    width: 100%;
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
        <div class="panel-heading"><h3 class="panel-title">ფაილის ატვირთვა</h3></div>
        <div class="panel-body">									


 <form name="item" action="" method="post" enctype="multipart/form-data">
<div class="card card-block">
<div class="form-group row"> <label class="col-sm-2 form-control-label text-xs-right">
მონიშნეთ ფაილი:
</label>
<div class="col-sm-10"><input type="file" name="userfile" class="form-control boxed" placeholder=""> </div>
</div>
<div class="form-group row">
<div class="col-sm-10 col-sm-offset-2"> 
 <input type="submit" name="submit" value="ფაილის ატვირთვა" class="btn btn-primary" /></div>
</div>

</div>
</form>
<br />

<!-- Upload Finished -->
<div class="container">
	<table class="table">
      <thead>
        <tr>
          <th width="80%">ფაილი</th>
          <th>ქმედება</th>
        </tr>
      </thead>
      <tbody>
         <?php       
         foreach ($sql_files as $files):
         ?> 
        <tr class="">
         
            <td><a href="<?=base_url();?>uploads/<?=$files['file_name'];?>"><?=$files['file_name'];?></a></td>
          <td><a href="<?=$files['files_id'];?>">წაშლა</a></td>
        </tr>
        <?php
        endforeach;
        ?>
      
       
        
      </tbody>
    </table>
</div>
        </div> <!-- panel-body -->
    </div> <!-- panel -->
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
       
    
    </body>

</html>
