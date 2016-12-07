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
	<link href="<?=base_url();?>assets/plugins/modal-effect/css/component.css" rel="stylesheet">
		
 
    
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
					<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">მომხმარებლის მართვა</h3>
							</div>
							<div class="panel-body">			
								<a href="<?=base_url();?>" class="btn btn-info waves-effect waves-light m-b-5"  id="sa-params">მომხმარებლის მართვა</a>
							</div>
						</div>
					</div>
					
					
                        </div>
                        <!-- Start Widget -->
                         <div class="row">
                      <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><h3 class="panel-title">მომხმარებლის დამატება</h3></div>
                                    <div class="panel-body">
									<?php echo validation_errors('<div class="col-lg-offset-2 col-lg-9"><div class="alert alert-danger">', '</div></div>'); ?>
									
                                        <div class="form">
                                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="POST" action="#" novalidate="novalidate">
                                                <div class="form-group">
                                                    <label for="cname" class="control-label col-lg-2">სახელი (*)</label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" id="cname" name="first_name" type="text" required="" aria-required="true">
                                                    </div>
                                                </div>
												 <div class="form-group">
                                                    <label for="cname" class="control-label col-lg-2">გვარი (*)</label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" id="cname" name="last_name" type="text" required="" aria-required="true">
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label for="cname" class="control-label col-lg-2">მეტსახელი (*)</label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" id="cname" name="nickname" type="text" required="" aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cemail" class="control-label col-lg-2">ელ-ფოსტა (*)</label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" id="cemail" type="text" name="person_mail" required="" aria-required="true">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="curl" class="control-label col-lg-2">დაბადების თარიღი</label>
                                                    <div class="col-lg-10">
                                                       <input type="text" placeholder="" name="birthday" data-mask="99-99-9999" class="form-control">
													   <span class="help-inline">რიცხვი - თვე - წელი</span>
                                                    </div>
                                                </div>
													<div class="form-group">
                                                    <label for="curl" class="control-label col-lg-2">ტელეფონის ნომერი</label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" id="curl" type="text" name="phone">
                                                    </div>
                                                </div>
                                               
												  <div class="form-group">
                                                    <label for="ccomment" class="control-label col-lg-2"></label>
                                                    <div class="col-lg-10">
                                                       <div class="panel-heading"> 
													</div>
													<!-- სისტემური პარამეტრები -->
								<div class="col-md-6">
                                <div class="panel panel-border  panel-danger">
                                    <div class="panel-heading"> 
                                     <h3 class="panel-title">მონიშნეთ მომხმარებლის სისტემური უფლებები</h3>
                                    </div> 
									   <div class="panel-body"> 
									   <?php
										if(!empty($zlib_roles)){
											foreach ($zlib_roles as $roles):
											
									   ?>
										<div class="checkbox checkbox-danger checkbox-circle">
                                            <input id="checkbox-<?=$roles['role_id'];?>" name="<?=$roles['role_id'];?>" value="<?=$roles['role_id'];?>" type="checkbox">
                                            <label for="checkbox-<?=$roles['role_id'];?>">
                                              <?=$roles['role_description'];?>
                                            </label>
                                        </div>
										<?php
											endforeach; }
										?>
                                    </div> 
                                </div>
                            </div>
													<!-- სისტემური პარამეტრები -->
													<!-- საკურატორო სერვისები -->
								<div class="col-md-6">
                                <div class="panel panel-border panel-info">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">მონიშნეთ მომხმარებლის საკურატორო სერვისები</h3> 
                                    </div> 
                                  <div class="panel-body">
									    <?php										
										foreach ($get_sql_services as $services):										
										?>
										<div class="checkbox checkbox-info checkbox-circle">
                                            <input id="checkbox2-<?php echo $services['category_service_id'];?>" name="<?php echo $services['category_service_id'];?>" value="<?php echo $services['category_service_id'];?>" type="checkbox">
                                            <label for="checkbox2-<?php echo $services['category_service_id'];?>">
                                            <?php echo $services['service_name']; ?>
                                            </label>
                                        </div>
										<?php endforeach; ?>
                                    </div> 
                                </div>
                            </div>
													<!-- საკურატორო სერვისები -->
                                                    </div>
												</div>
                                                <div class="form-group">
                                                    <div class="col-lg-offset-2 col-lg-10">
                                                        <input type="submit"  class="btn btn-primary"  name="add_person" value="მომხმარებლის დამატება">
                                                    </div>
                                                </div>
                                            </form>
								
                                        </div> <!-- .form -->
										
                                    </div> <!-- panel-body -->
								
                                </div> <!-- panel -->
								
                                 
                              
                            </div> <!-- col -->
							
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
        
        <!-- Modal-Effect -->
        <script src="<?=base_url();?>assets/plugins/modal-effect/js/classie.js"></script>
        <script src="<?=base_url();?>assets/plugins/modal-effect/js/modalEffects.js"></script>
		

  
        <script src="<?=base_url();?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
      




    
    </body>

</html>
