<!DOCTYPE html>
<html>
<head>
<?php require_once ('components/main_head.php'); ?>
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
                            <img src="assets/images/users/girl.png" alt="" class="thumb-md img-circle">
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
						<?php require_once('components/admin_menu.php');?>
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
                                    <div class="panel-heading">
                                        <h3 class="panel-title">რიგი</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th width="30">კატეგორია</th>
                                                            <th>რიგშია</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="clients_queee_body">
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div> <!--end table-responsive-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                           <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">ჩეთის მიმდინარეობა</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>ოპერატორი</th>
                                                                <th>მომხმარებელი</th>
                                                                <th>სერვისი</th>
                                                                <th>საუბრის დასაწყისი</th>
                                                                <th>ქმედება</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                           
															<?php
															 for($i=1; $i <=10; $i++)
															 {
																echo '      <tr>
                                                                <td>'.$i.'</td>
                                                                <td>თამარ თეთრაძე</td>
                                                                <td>მომხმრებელი</td>
                                                                <td>არქივის სერვისები</td>
                                                                <td>3.03.2015</td>
                                                                <td>
																	<a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>
																	<a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>
																	<a href="#" class="on-default edit-row"><i class="fa md-pageview" data-toggle="tooltip" data-placement="left" title="დათვალიერება"></i></a>&nbsp;&nbsp;
																	<a href="#" class="on-default edit-row"><i class="fa fa-play-circle-o" data-toggle="tooltip" data-placement="right" title="საუბარში ჩართვა"></i></a>
																	
																</td>
                                                            </tr>
                                                            ';
															 }																 
															?>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->

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



<div class="clearfix" id="msgbox_container">
    <div class="msgbox_chat_window msgbox_readonly" id="DRJadCx7WYIRB0IGlSsaCqz5QBhAi5Jh">
        <div class="msgbox_chat_minimized titlebar">giga kokaia</div>
        <div class="msgbox_chat_content">
            <div class="titlebar">
                <div class="msgbox_left">giga kokaia</div>
                <div class="msgbox_right">
                    <input type="checkbox">
                    <a class="msgbox_close" href="#">x</a>
                </div>
            </div>
            <div class="msgbox_chat_area scrollable">

            </div>
            <div class="msgbox_chat_">
                <textarea name="usermsg" type="text" class="msgbox_usermsg"></textarea>
            </div>
        </div>
    </div>
</div>



        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

        <!--        <script src="--><?//=base_url();?><!--assets/js/jquery.min.js"></script>-->
        <script src="<?=base_url();?>assets/js/bootstrap.min.js"></script>
        <script src="<?=base_url();?>assets/js/detect.js"></script>
        <script src="<?=base_url();?>assets/js/fastclick.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.blockUI.js"></script>
        <script src="<?=base_url();?>assets/js/waves.js"></script>
        <script src="<?=base_url();?>assets/js/wow.min.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?=base_url();?>assets/js/jquery.scrollTo.min.js"></script>

        <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
        <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>

        <script src="<?=base_url();?>assets/js/jquery.app.js"></script>

        <script src="http://cdn.socket.io/socket.io-1.4.1.js"></script>

        <script type="application/javascript">
            var token = "<?=$this->session->userdata['token'] ; ?>";
            var socketAddr = '<?=substr(base_url(),0,-1);?>';
        </script>
        <script src="<?=base_url();?>assets/js/socket/common.js"></script>
        <script src="<?=base_url();?>assets/js/socket/dashboard.js"></script>
    
    </body>

</html>