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
       
      <link href="<?=base_url();?>assets/plugins/tagsinput/jquery.tagsinput.css" rel="stylesheet">
        <link href="<?=base_url();?>assets/plugins/toggles/toggles.css" rel="stylesheet">


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
                    
                     <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title">სისტემური პარამეტრები</h3></div>
                            <div class="panel-body">
                            
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="p-20">
                                               
                                                    
                                                    <div class="form-group">
                                                        <label class="control-label">ოპერატორის მაქსიმალური დატვირვა</label>
                                                        <input id="demo0" type="text" value="<?=$get_sys_control['operator_max_load'];?>" name="operator_max_load" data-bts-min="0" data-bts-max="900" data-bts-init-val="" data-bts-step="1" data-bts-decimal="0" data-bts-step-interval="100" data-bts-force-step-divisibility="round" data-bts-step-interval-delay="500" data-bts-prefix="" data-bts-postfix="" data-bts-prefix-extra-class="" data-bts-postfix-extra-class="" data-bts-booster="true" data-bts-boostat="10" data-bts-max-boosted-step="false" data-bts-mousewheel="true" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">რამდენი კალენდრული დღის გასვლის შემდეგ წაშალოს სისტემამ ჩეთის ისტორია</label>
                                                        <input id="demo0" type="text" value="<?=$get_sys_control['history_life_time'];?>" name="history_life_time" data-bts-min="0" data-bts-max="900" data-bts-init-val="" data-bts-step="1" data-bts-decimal="0" data-bts-step-interval="100" data-bts-force-step-divisibility="round" data-bts-step-interval-delay="500" data-bts-prefix="" data-bts-postfix="" data-bts-prefix-extra-class="" data-bts-postfix-extra-class="" data-bts-booster="true" data-bts-boostat="10" data-bts-max-boosted-step="false" data-bts-mousewheel="true" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary"/>
                                                    </div>
                                                    
                                                
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="p-20">
                                               

                                                  <div class="form-group">
                                                        <label class="control-label">რამდენი დღით განისაზღვროს სისტემაში ჩართული მომხმარებლების პაროლის სიცოცხლის ციკლი</label>
                                                        <input id="demo0" type="text" value="<?=$get_sys_control['pass_life_time'];?>" name="pass_life_time" data-bts-min="0" data-bts-max="900" data-bts-init-val="" data-bts-step="1" data-bts-decimal="0" data-bts-step-interval="100" data-bts-force-step-divisibility="round" data-bts-step-interval-delay="500" data-bts-prefix="" data-bts-postfix="" data-bts-prefix-extra-class="" data-bts-postfix-extra-class="" data-bts-booster="true" data-bts-boostat="10" data-bts-max-boosted-step="false" data-bts-mousewheel="true" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary"/>
                                                    </div>  
                                                    <div class="form-group">
                                                        <label class="control-label">პასიურ მომხმარებელთან საუბრის ავტომატურად დახურვის დრო (წამებში)</label>
                                                        <input id="demo0" type="text" value="<?=$get_sys_control['passive_client_time'];?>" name="passive_client_time" data-bts-min="0" data-bts-max="900" data-bts-init-val="" data-bts-step="1" data-bts-decimal="0" data-bts-step-interval="100" data-bts-force-step-divisibility="round" data-bts-step-interval-delay="500" data-bts-prefix="" data-bts-postfix="" data-bts-prefix-extra-class="" data-bts-postfix-extra-class="" data-bts-booster="true" data-bts-boostat="10" data-bts-max-boosted-step="false" data-bts-mousewheel="true" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary"/>
                                                    </div>  
                                                  
                                               
                                            </div>
                                        </div>
                                    </div>
									 <div class="form-group">
													
								   <input type="submit" class="btn btn-primary" type="submit" name="save_sys" value="პარამეტრების შენახვა" />
								   
								
								</div>
                                </form>

                            </div> <!-- panel-body -->
                        </div> <!-- panel -->
                    </div> <!-- col -->


                   
                                    </div>
                                </form>
                            
                            </div> <!-- panel-body -->
                        </div> <!-- panel -->
                    </div> <!-- col -->

                 </div> <!-- End row -->
       
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
       

     
        <script src="<?=base_url();?>assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>

       
      
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
          <script src="<?=base_url();?>assets/plugins/tagsinput/jquery.tagsinput.min.js"></script>
        <!--script for this page only-->
        <script src="<?=base_url();?>assets/plugins/nestable/jquery.nestable.js"></script>
        
        <!-- Modal-Effect -->
        <script src="<?=base_url();?>assets/plugins/modal-effect/js/classie.js"></script>
        <script src="<?=base_url();?>assets/plugins/modal-effect/js/modalEffects.js"></script>
		

  
        <script src="<?=base_url();?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?=base_url();?>assets/plugins/tagsinput/jquery.tagsinput.min.js"></script>
        <script src="<?=base_url();?>assets/plugins/toggles/toggles.min.js"></script>
        <script src="<?=base_url();?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/plugins/colorpicker/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/plugins/jquery-multi-select/jquery.multi-select.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/plugins/jquery-multi-select/jquery.quicksearch.js"></script>
        <script src="<?=base_url();?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?=base_url();?>assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>


  <script>
            jQuery(document).ready(function() {

                // Tags Input
                jQuery('#tags').tagsInput({width:'auto'});

                // Form Toggles
                jQuery('.toggle').toggles({on: true});

                // Time Picker
                jQuery('#timepicker').timepicker({defaultTIme: false});
                jQuery('#timepicker2').timepicker({showMeridian: false});
                jQuery('#timepicker3').timepicker({minuteStep: 15});

                // Date Picker
                jQuery('#datepicker').datepicker();
                jQuery('#datepicker-inline').datepicker();
                jQuery('#datepicker-multiple').datepicker({
                    numberOfMonths: 3,
                    showButtonPanel: true
                });
                //colorpicker start

                $('.colorpicker-default').colorpicker({
                    format: 'hex'
                });
                $('.colorpicker-rgba').colorpicker();


                //multiselect start

                $('#my_multi_select1').multiSelect();
                $('#my_multi_select2').multiSelect({
                    selectableOptgroup: true
                });

                $('#my_multi_select3').multiSelect({
                    selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                    selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                    afterInit: function (ms) {
                        var that = this,
                            $selectableSearch = that.$selectableUl.prev(),
                            $selectionSearch = that.$selectionUl.prev(),
                            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                            .on('keydown', function (e) {
                                if (e.which === 40) {
                                    that.$selectableUl.focus();
                                    return false;
                                }
                            });

                        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                            .on('keydown', function (e) {
                                if (e.which == 40) {
                                    that.$selectionUl.focus();
                                    return false;
                                }
                            });
                    },
                    afterSelect: function () {
                        this.qs1.cache();
                        this.qs2.cache();
                    },
                    afterDeselect: function () {
                        this.qs1.cache();
                        this.qs2.cache();
                    }
                });



                // Select2
                jQuery(".select2").select2({
                    width: '100%'
                });

                //Bootstrap-TouchSpin
                $(".vertical-spin").TouchSpin({
                    verticalbuttons: true,
                    verticalupclass: 'ion-plus-round',
                    verticaldownclass: 'ion-minus-round'
                });
                var vspinTrue = $(".vertical-spin").TouchSpin({
                    verticalbuttons: true
                });
                if (vspinTrue) {
                    $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();
                }

                $("input[name='demo1']").TouchSpin({
                    min: 0,
                    max: 100,
                    step: 0.1,
                    decimals: 2,
                    boostat: 5,
                    maxboostedstep: 10,
                    postfix: '%'
                });
                $("input[name='demo2']").TouchSpin({
                    min: -1000000000,
                    max: 1000000000,
                    stepinterval: 50,
                    maxboostedstep: 10000000,
                    prefix: '$'
                });
                $("input[name='demo3']").TouchSpin();
                $("input[name='demo3_21']").TouchSpin({
                    initval: 40
                });
                $("input[name='demo3_22']").TouchSpin({
                    initval: 40
                });
				$("input[name='operator_max_load']").TouchSpin({});
				$("input[name='history_life_time']").TouchSpin({});
				$("input[name='pass_life_time']").TouchSpin({});
                $("input[name='passive_client_time']").TouchSpin({});
            });
        </script>

    
    </body>

</html>
