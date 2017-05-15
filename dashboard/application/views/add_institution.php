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
        $(document).ready(function(){      
             // delete the entry once we have confirmed that it should be deleted
                $('.delete').click(function() {
                data = {};			
                data['id'] = $(this).parent('tr').attr('data-row-id');
                        var parent = $(this).closest('tr');
                         if(confirm('დარწმუნებული ხართ რომ გინდათ უწყების წაშლა?'))
                         {
                         $.ajax({
                                type: "POST",  
                                  url: "<?=base_url()."institution/delete_institution";?>",  
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
                        <!-- Start Widget -->
                         <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><h3 class="panel-title">უწყების მართვა</h3></div>
                                    <div class="panel-body">									
                                   <?php
                                    if($notify==1){
                                      echo '<div class="alert alert-success" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      ახალი უწყების დასახელება დამატებულია
                                    </div>';  
                                    }
                                    ?> 
                                    <div class="form">
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="POST" action="" ">
                                            <div class="form-group">
                                                    <label for="cname" class="control-label col-lg-2">უწყების დასახელება</label>
                                                    <div class="col-lg-9">
                                                            <input class="form-control" id="cname" name="institution_name" type="text">
                                                            <div class="val_notifications"><?php echo form_error('institution_name'); ?></div> 
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <div class="col-lg-offset-2 col-lg-9">
                                                    <input type="submit" class="btn btn-primary" type="submit" name="add_institution" value="შენახვა" />
                                                    <input type="reset" class="btn btn-danger"  value="გასუფთავება" />
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
                                        ჩართული უწყებები
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
                        <tbody id="">
                            <tr>
                            <th width="95%">უწყების სახელწოდება</th>                            
                            <th>რედაქტირება</th>		
                            <th>წაშლა</th>
                          </tr>  
                          <?php foreach($get_institutions as $res) :?>
                          <tr data-row-id="<?php echo $res['repo_category_id'];?>">
                          <td class="editable-col" contenteditable="true" col-index='0' oldVal ="<?php echo $res['category_name'];?>"><?php echo $res['category_name'];?></td>
                          <td class="edit"> <a href="<?=base_url();?>institution/update_institution/<?php echo $res['repo_category_id'];?>" class="on-default" data-toggle="tooltip" data-placement="right" title="რედაქტირება"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                          <td class="delete"> <a href="#" class="on-default remove-row" data-toggle="tooltip" id="<?php echo $res['repo_category_id'];?>" data-placement="right" title="წაშლა"><i class="fa fa-trash-o"></i></a></td>
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
