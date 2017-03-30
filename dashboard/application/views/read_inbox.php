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
        <script type="text/javascript">
        $(document).ready(function(){
          $('td.editable-col').on('focusout', function() {
                data = {};
                data['val'] = $(this).text();
                data['id'] = $(this).parent('tr').attr('data-row-id');
                data['index'] = $(this).attr('col-index');
                if($(this).attr('oldVal') === data['val'])
                return false;

                if(confirm('განვაახლოთ მონაცემები ?'))
                         {
                          $.ajax({
                          type: "POST",  
                          url: "http://localhost/chat/institution/update_institution",  
                          cache:false,  
                          data: data,
                          dataType: "json",       
                          success: function(response)  
                          {   
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
             // delete the entry once we have confirmed that it should be deleted
                $('.delete').click(function() {
                data = {};			
                data['id'] = $(this).parent('tr').attr('data-row-id');
                        var parent = $(this).closest('tr');
                         if(confirm('დარწმუნებული ხართ რომ გინდათ უწყების წაშლა?'))
                         {
                         $.ajax({
                                type: "POST",  
                                  url: "http://localhost/chat/institution/delete_institution",  
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
                         <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="pull-left page-title">შეტყობინებები</h4>
                             
                            </div>
                        </div>

                        <div class="row">
                        
                            <!-- Left sidebar -->
                            <div class="col-lg-3 col-md-4">
                                <a href="email-compose.html" class="btn btn-danger waves-effect waves-light btn-block">ახალი შეტყობინება</a>
                                <div class="panel panel-default p-0 m-t-20">
                                    <div class="panel-body p-0">
                                        <div class="list-group mail-list">
                                          <a href="#" class="list-group-item no-border active"><i class="fa fa-download m-r-5"></i>სულ შემოსული <b>(8)</b></a>
                                          <a href="#" class="list-group-item no-border"><i class="fa fa-star-o m-r-5"></i>პასუხის მოლოდინში</a>                                          
                                          <a href="#" class="list-group-item no-border"><i class="fa fa-paper-plane-o m-r-5"></i>გაგზავნილი</a>                                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Left sidebar -->
                        
                            <!-- Right Sidebar -->
                            <div class="col-lg-9 col-md-8">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="btn-toolbar" role="toolbar">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fa fa-inbox"></i></button>                                                
                                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fa fa-trash-o"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- End row -->
                                
   <div class="panel panel-default m-t-20">
<div class="panel-heading"> 
	<h3 class="panel-title">შეტყობინება</h3> 
</div>
<div class="panel-body">
	<div class="media m-b-30">
		
		<div class="media-body">
			<span class="media-meta pull-right">07:23 AM</span>
			<h4 class="text-primary m-0">ლუკა რაზიკაშვილი</h4>
			<small class="text-muted">ელ-ფოსტა: test@test.ge</small>
		</div>
	</div> <!-- media -->

	<p><b>ზოგი რამ ფიქრებიდან</b></p>
	<p>მწერლობა მაშინ ასრულებს თავის წმინდა მოვალეობას, როცა უკეთესად ემსახურება ქვეყანას</p>
	

	<hr>

	

</div> <!-- panel-body -->
</div> <!-- End panel -->
                                <!-- End message -->
                                  <!-- Replay -->
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="media">
                                            <a href="#" class="pull-left">
                                               
                                            </a>
                                            <div class="media-body">
                                                <textarea class="wysihtml5 form-control" rows="9" placeholder="პასუხი..."></textarea>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-primary waves-effect waves-light m-t-30 w-md">გაგზავნა</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Replay -->
                            </div> <!-- end Col-9 -->
                        
                        </div><!-- End row -->
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
