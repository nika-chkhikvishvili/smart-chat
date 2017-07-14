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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>

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
<div class="col-md-8"> 
   
<div class="panel panel-default">
    <div class="panel-heading"> 
        <h3 class="panel-title">Chat</h3> 
    </div> 
    <div class="panel-body"> 
        <div class="chat-conversation">
           
            <ul class="conversation-list nicescroll">
                <?php
                foreach ($get_chat as $his):
                 
                if($his['online_user_id']>=1) 
                { 
                ?>
                <li class="clearfix odd">
                    <div class="chat-avatar">

                        <i><?=$his['message_date'];?></i>
                    </div>
                    <div class="conversation-text">
                        <div class="ctext-wrap">
                            <i><?=$his['online_users_name'];?> &nbsp; <?=$his['online_users_lastname'];?></i>
                            <p>
                                <?=$his['chat_message'];?>
                            </p>
                        </div>
                    </div>
                </li>
                <?php
                }
                else 
                {
                 
                ?>
                 <li class="clearfix">
                    <div class="chat-avatar">

                        <i><?=$his['message_date'];?></i>
                    </div>
                    <div class="conversation-text">
                        <div class="ctext-wrap">
                            <i><?=$his['first_name'];?> &nbsp; <?=$his['last_name'];?></i>
                            <p>
                                <?=$his['chat_message'];?>
                            </p>
                        </div>
                    </div>
                </li>
                 <?php
                }
                 endforeach ;
                 ?>
            </ul>

        </div>
    </div> 
  </div> 
</div> <!-- end col-->
     <div class="col-lg-4">
    <div class="panel panel-default panel-fill">
        <div class="panel-heading"> 
            <h3 class="panel-title">
                <a href="<?=base_url();?>history"  class="btn btn-danger btn-custom waves-effect waves-light m-b-5" id="">ჩეთის ისტორია</a>
            </h3> 
        </div> 
        <div class="panel-body"> 
            <div class="inbox-widget">
            <a href="#">
                <div class="inbox-item">
                    <div class="inbox-item-img"></div>
                    <p class="inbox-item-author">ოპერატორი</p>
                    <p class="inbox-item-text"><?=$his['first_name'];?> &nbsp; <?=$his['last_name'];?></p>                   
                </div>
            </a>
            <a href="#">
                <div class="inbox-item">
                    <div class="inbox-item-img"></div>
                    <p class="inbox-item-author">მომხმარებელი</p>
                    <p class="inbox-item-text"><?=$his['online_users_name'];?> &nbsp; <?=$his['online_users_lastname'];?></p>  
                </div>
            </a>
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
         <script src="<?=base_url();?>assets/pages/jquery.chat.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.app.js"></script>
     
		<script src="<?=base_url();?>assets/plugins/notifyjs/dist/notify.min.js"></script>
        <script src="<?=base_url();?>assets/plugins/notifications/notify-metro.js"></script>
        <script src="<?=base_url();?>assets/plugins/notifications/notifications.js"></script>
      
        <!--script for this page only-->
        <script src="<?=base_url();?>assets/plugins/nestable/jquery.nestable.js"></script>
       
    
    </body>

</html>
