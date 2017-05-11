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
                <a href="<?=base_url();?>persons/add_person" class="btn btn-info waves-effect waves-light m-b-5"  id="sa-params">მომხმარებლის დამატება</a>
                </div>
                </div>
                    </div>
       
        <!-- modal info -->
               <div class="md-modal md-effect-6" id="modal-7">
               <div class="md-content">
                       <h3>ინფორმაცია</h3>
                       <div>
        <div class="tab-pane" id="profile-2">
         <!-- Personal-Information -->
         <div class="panel panel-default panel-fill">

               <div class="panel-body"> 
                       <div class="timeline-2">
                       <div class="time-item">
                    <div class="item-info">
                            <div class="text-muted">09:00:53</div>
                            <p><strong>სისტემაში ავტორიზაცია </strong></p>
                    </div>
                       </div>

                       <div class="time-item">
                    <div class="item-info">
                            <div class="text-muted">30 minutes ago</div>
                            <p><a href="#" class="text-info">Lorem</a> commented your post.</p>

                    </div>
                       </div>

                       <div class="time-item">
                        <div class="item-info">
                                <div class="text-muted">59 minutes ago</div>
                                <p><a href="#" class="text-info">Jessi</a> attended a meeting with<a href="#" class="text-success">John Doe</a>.</p>

                        </div>
                       </div>
               </div>

               </div> 
                </div>
                <!-- Personal-Information -->
                </div> 

                 <button class="md-close btn btn-primary waves-effect waves-light">დახურვა</button>
                 </div>
                 </div>
                     </div>
					  <!-- end of modal info -->
                        </div>
                        <!-- Start Widget -->
                         <div class="row">
                        <?php
                        if(!empty($persons)){
                                foreach ($persons as $list):
                               # var_dump($list);
                        ?>
                        <div class="col-sm-4 col-lg-4">
                        <div class="panel">
                        <div class="panel-body">
                        <div class="media-main">
                        <a class="pull-left" href="#">
                                <img class="thumb-lg img-circle" src="<?=base_url();?>assets/images/users/girl.png" alt="">
                        </a>
                         <?php
                         if($list['is_admin']==0) {
                                 $del_url  = base_url()."del_person/".$list['person_id'];
                                 $edit_url  = base_url()."persons/edit_person/".$list['person_id'];
                                 echo '<div class="pull-right btn-group-sm">
                                <a href="'.$edit_url.'" class="btn btn-success waves-effect waves-light tooltips" data-placement="top" data-toggle="tooltip" data-original-title="რედაქტირება">
                                        <i class="fa fa-pencil"></i>
                                </a>
                                <a href="'.$del_url.'" class="btn btn-danger waves-effect waves-light tooltips" data-placement="top" data-toggle="tooltip" data-original-title="ანგარიშის გაუქმება">
                                        <i class="fa fa-close"></i>
                                </a>
                        </div>';
                         }
                         ?>

                        <div class="info">
                        <h4><?=$list['first_name'];?> &nbsp; <?=$list['last_name'];?></h4>
                        <p class="text-muted"><?php if ($list['is_admin']==1) { echo "ადმინისტრატორი";} else {echo "ოპერატორი";}?></p>
                        </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <ul class="social-links list-inline">
                        <li>
                        <a title="" data-placement="right" data-toggle="tooltip" href="javascript:;" class="tooltips md-trigger waves-effect waves-light" data-modal="modal-<?php echo $list['person_id'];?>">
                        <i class="fa  fa-info-circle"></i></a>
                        </li>
                         <li>
                        <a title="" data-placement="right" data-toggle="tooltip" href="javascript:;" class="tooltips md-trigger waves-effect waves-light" data-modal="modal-7">
                        <i class="fa fa-history"></i></a>
                        </li>
							
						</ul>
					</div> <!-- panel-body -->
				</div> <!-- panel -->
				</div> <!-- end col -->
                                 <!-- modal info -->
                                 <div class="md-modal md-effect-4" id="modal-<?php echo $list['person_id'];?>">					
                        <div class="md-content panel panel-border panel-info">
                      
                    <div class="panel-heading"> 
                     <h3 class="panel-title">პერსონალური ინფორმაცია</h3> 
                     </div> 
                        <div class="panel-body"> 
                                <div class="">
                                        <strong>სახელი გვარი</strong>
                                        <br>
                                        <p><?=$list['first_name'];?> &nbsp; <?=$list['last_name'];?></p>
                                </div>
                                <div class="">
                                        <strong>მეტსახელი</strong>
                                        <br>
                                        <p><?=$list['nickname'];?></p>
                                </div>
                                <div class="">
                                        <strong>ელ-ფოსტა</strong>
                                        <br>
                                        <p><?=$list['email'];?></p>
                                </div>
                                <div class="">
                                        <strong>დაბადების თარიღი</strong>
                                        <br>
                                        <p><?=$list['birth_date'];?></p>
                                </div>
                                <div class="">
                                        <strong>ტელეფონი</strong>
                                        <br>
                                        <p><?=$list['phone'];?></p>
                                </div>
                                <div class="">
                                        <strong> <p>საკურატორო სერვისები </p></strong>                                      
                                        <ul>
                                            <li><strong>საარქივო სერვისები</strong></li>
                                            <li><strong>ID ბარათი</strong> </li>
                                            <li><strong>ბიზნესი</strong> </li>
                                        </ul>
                                </div>
                        
                            <button class="md-close btn btn-primary waves-effect waves-light">დახურვა</button>
                        </div> 
                        
                        
                        </div>
                        </div>
				<?php endforeach; }?>
                            </div> <!-- col -->
                        <div class="row">
                          <div class="col-sm-12">
                        <ul class="pagination pull-right">
                                <li>
                                  <a href="#" aria-label="Previous">
                                        <i class="fa fa-angle-left"></i>
                                  </a>
                                </li>
                                <li><a href="#">1</a></li>
                                <li class="active"><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li class="disabled"><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li>
                                  <a href="#" aria-label="Next">
                                        <i class="fa fa-angle-right"></i>
                                  </a>
                                </li>
                        </ul>
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
     
        <script src="<?=base_url();?>assets/plugins/notifications/notify-metro.js"></script>
        <script src="<?=base_url();?>assets/plugins/notifications/notifications.js"></script>
      
        <!--script for this page only-->
        <script src="<?=base_url();?>assets/plugins/nestable/jquery.nestable.js"></script>
        
        <!-- Modal-Effect -->
        <script src="<?=base_url();?>assets/plugins/modal-effect/js/classie.js"></script>
        <script src="<?=base_url();?>assets/plugins/modal-effect/js/modalEffects.js"></script>
    
    </body>

</html>
