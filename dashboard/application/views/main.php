<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 5/11/16
 * Time: 7:15 PM
 */

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Smart Logic  Open Source Chat System">
    <meta name="author" content="Coderthemes">
    <link rel="shortcut icon" href="<?=base_url();?>assets/images/favicon_1.ico">
    <title><?php echo $title; ?></title>
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
    <link href="<?=base_url();?>assets/plugins/modal-effect/css/component.css" rel="stylesheet">
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
        <?php echo $admin_topbar;?>
        <!-- Top Bar End -->


        <!-- ========== Left Sidebar Start ========== -->
        <?php echo $main_left_slidebar;?>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->

        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <?php echo $content; ?>

            <footer class="footer text-right">2016 © Smart Logic.</footer>
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
    <!-- Modal-Effect -->
    <script src="<?=base_url();?>assets/plugins/modal-effect/js/classie.js"></script>
    <script src="<?=base_url();?>assets/plugins/modal-effect/js/modalEffects.js"></script>
</body>

</html>
