<!DOCTYPE html>
<html>
<head>
<?php require_once ('components/styles.php');?>
<link href="<?=base_url();?>assets/plugins/notifications/notification.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>resources/clockpicker/bootstrap-clockpicker.min.css">
<script src="<?=base_url();?>assets/js/jquery.min.js"></script>
<script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>

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
                        <div class="col-sm-12">
                            <div class="bg-picture text-center" style="background-image:url('assets/images/big/bg.jpg')">
                                <div class="bg-picture-overlay"></div>
                                <div class="profile-info-name">
                                    <img src="assets/images/users/girl.png" class="thumb-lg img-circle img-thumbnail" alt="profile-image">
                                    <h3 class="text-white"><?php echo $profile_person_data['first_name']; ?> <?php echo $profile_person_data['last_name']; ?></h3>
                                </div>
                            </div>
                            <!--/ meta -->
                        </div>
                    </div>
                        <!-- Start Widget -->
                    <div class="row">
                        <div class="col-lg-5"> 
                        
                                   
                            <div class="tab-pane" id="settings-2">
                                <!-- Personal-Information -->
                                <div class="panel panel-default panel-fill">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">პირადი ინფორმაცია</h3> 
                                    </div> 
                                    <div class="panel-body">                                        
                                            <div class="form-group">
                                                <label for="FullName">სახელი</label>
                                                <input type="text" readonly="" value="<?php echo $profile_person_data['first_name']; ?>" id="" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="Email">გვარი</label>
                                                <input type="text" readonly="" value="<?php echo $profile_person_data['last_name']; ?>" id="Email" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="Username">მეტსახელი</label>
                                                <input type="text" readonly="" value="<?php echo $profile_person_data['nickname']; ?>" id="" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="Username">ელ-ფოსტა</label>
                                                <input type="text" readonly="" value="<?php echo $profile_person_data['email']; ?>" id="" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="Username">დაბადების თარიღი</label>
                                                <input type="text" readonly="" value="<?php echo $profile_person_data['birth_date']; ?>" id="" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="Username">ტელეფონის ნომერი</label>
                                                <input type="text" readonly="" value="<?php echo $profile_person_data['phone']; ?>" id="" class="form-control">
                                            </div>
                        </div> 
                    </div>
                    </div>
                </div> <!-- container -->
                 <div class="col-lg-5">
                <!-- Personal-Information -->
                <div class="panel panel-default panel-fill">
                    <div class="panel-heading"> 
                        <h3 class="panel-title">საკურატორო სერვისები</h3> 
                    </div> 
                    <div class="panel-body"> 
                    <ul class="list-group">
                    <?php
                    foreach ($profile_data as $service_data):
                    ?>
                    <a href="#" class="list-group-item"><?=$service_data['service_name_geo'];?></a>                    
                    <?php
                        endforeach;
                    ?>
                    </ul>
                    </div> 
                </div>                
                </div>
              <div class="col-lg-5">
                <!-- Personal-Information -->
                <div class="panel panel-default panel-fill">
                    <div class="panel-heading"> 
                        <h3 class="panel-title">პაროლის განახლება</h3> 
                    </div>
                    <?php
                    if($notify==1){
                      echo '<div class="alert alert-info" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      '.$error_message.'
                    </div>';  
                    }
                    ?>
                    <div class="panel-body"> 
                        <form action="" method="post">                              
                            <div class="form-group">
                                <label for="old_password">ძველი პაროლი</label>
                                <input type="password" name="old_password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_password1">ახალი პაროლი</label>
                                <input type="password"  name="new_password1"  class="form-control">
                            </div>

                              <div class="form-group">
                                <label for="new_password2">გაიმეორეთ ახალი პაროლი</label>
                                <input type="password"  name="new_password2" class="form-control">
                            </div>

                            <input  class="btn btn-primary waves-effect waves-light w-md submit" name="submit" type="submit" value="პაროლის განახლება" />
                        </form>
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


        <!-- jQuery  -->
      
       
    
    </body>

</html>
