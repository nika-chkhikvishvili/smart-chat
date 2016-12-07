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
				  url: "http://localhost/chat/dashboard/update_institution",  
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
					  url: "http://localhost/chat/dashboard/delete_institution",  
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
                            <img src="<?=base_url();?>assets/images/users/avatar-1.jpg" alt="" class="thumb-md img-circle">
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
                         <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><h3 class="panel-title">უწყების მართვა</h3></div>
                                    <div class="panel-body">									
									<?php echo validation_errors('<div class="col-lg-offset-2 col-lg-9"><div class="alert alert-danger">', '</div></div>'); ?>
																	
                                    <div class="form">
									<form class="cmxform form-horizontal tasi-form" id="commentForm" method="POST" action="" ">
										<div class="form-group">
											<label for="cname" class="control-label col-lg-2">უწყების დასახელება</label>
											<div class="col-lg-9">
												<input class="form-control" id="cname" name="institution_name" type="text" required="" aria-required="true">
											</div>
										</div>
										<div class="form-group">
											<div class="col-lg-offset-2 col-lg-9">
											<input type="submit" class="btn btn-primary" type="submit" name="add_institution" value="შენახვა" />
											<input type="reset" class="btn btn-danger" type="submit"  value="გასუფთავება" />
											</div>
										</div>
										<div class="col-lg-offset-2 col-lg-9"><div id="msg" class="alert">                                         
										                                    
                                        </div></div>		
									</form>
										 
                                        </div> <!-- .form -->
                                    </div> <!-- panel-body -->
                                </div> <!-- panel -->
                            </div> <!-- col -->
							 <div class="col-md-12">
                                <div class="panel panel-default">
                                 
                                    <div class="panel-body">
									<div class="portlet">
										<div class="portlet-heading bg-info">
                                        <h3 class="portlet-title">
                                           სერვისები
                                        </h3>
											<div class="portlet-widgets">
												<a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
												<span class="divider"></span>
												<a data-toggle="collapse" data-parent="#accordion1" href="#bg-success"><i class="ion-minus-round"></i></a>
												<span class="divider"></span>
												<a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
											</div>
											<div class="clearfix"></div>
										</div>
									   <div id="bg-success" class="panel-collapse collapse in">
									   <div class="portlet-body">
									<table id="employee_grid" class="table table-condensed table-hover table-striped bootgrid-table" width="60%" cellspacing="0">
								   <tbody id="_editable_table">
									  <?php foreach($get_institutions as $res) :?>
									  <tr data-row-id="<?php echo $res['repo_categories_id'];?>">
										 <td class="editable-col" contenteditable="true" col-index='0' oldVal ="<?php echo $res['category_name'];?>"><?php echo $res['category_name'];?></td>
										  <td class="delete"> <a href="#" class="on-default remove-row" data-toggle="tooltip" data-placement="right" title="წაშლა"><i class="fa fa-trash-o"></i></a></td>
									  </tr>
									<?php endforeach;?>
									   </tbody>
									</table>
									</div>
									</div>
									</div>
                                        <div class="dd" id="nestable_list_3">
                                              <ol class="dd-list">
											 
										  
                                              </ol>
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
