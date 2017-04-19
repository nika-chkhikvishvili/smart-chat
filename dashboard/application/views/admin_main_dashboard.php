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
                                                    <table class="table" id="online_chats_list">
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
                                                           
															<?php /*
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
														*/	?>
                                                            
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



<!--<div class="clearfix" id="msgbox_container">-->
<!--    ONLY FOR EXAMPLE-->
<!--    <div class="msgbox_chat_window msgbox_readonly" id="DRJadCx7WYIRB0IGlSsaCqz5QBhAi5Jh">-->
<!--        <div class="msgbox_chat_minimized titlebar">giga kokaia</div>-->
<!--        <div class="msgbox_chat_content">-->
<!--            <div class="titlebar">-->
<!--                <div class="msgbox_left">giga kokaia</div>-->
<!--                <div class="msgbox_right">-->
<!--                    <input type="checkbox">-->
<!--                    <a class="msgbox_close" href="#">x</a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="msgbox_chat_area scrollable">-->
<!---->
<!--            </div>-->
<!--            <div class="msgbox_chat_">-->
<!--                <textarea name="usermsg" type="text" class="msgbox_usermsg"></textarea>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="wrapper_chat">
    <div class="chat_open_button" style="display: none; cursor: pointer; ">X</div>
    <div class="container_chat">
        <div class="left">
            <div class="top">
                <div class="chat_close_button" style="display: inline;position: relative;top: -24px;left: -208px; cursor: pointer;">X</div>
                <input type="text" />
                <a href="javascript:;" class="search"></a>
            </div>
            <ul class="people">
                <li class="person" data-chat="person1" style="display: none;">
                    <img src="http://s13.postimg.org/ih41k9tqr/img1.jpg" alt="" />
                    <span class="name">Thomas Bangalter</span>
                    <span class="time">2:09 PM</span>
                    <span class="preview">I was wondering...</span>
                </li>
            </ul>
        </div>
        <div class="right">
            <div class="top"><span>To: <span class="name"> </span></span>
                <span style="float:right;"><a href="javascript:close_chat();">დასრულება</a></span> <span style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                <span style="float:right;"><a href="javascript:ban_person();">დაბლოკვა</a></span></div>
            <div class="chats_container">
                <div class="chat" data-chat="person1" style="display: none;">
                    <div class="conversation-start">
                        <span>Monday, 1:27 PM</span>
                    </div>
                    <div class="bubble you">
                        So, how's your new phone?
                    </div>
                    <div class="bubble you">
                        You finally have a smartphone :D
                    </div>
                    <div class="bubble me">
                        Drake?
                    </div>
                    <div class="bubble me">
                        Why aren't you answering?
                    </div>
                    <div class="bubble you">
                        howdoyoudoaspace
                    </div>
                </div>
            </div>
            <div class="write">
                <a href="javascript:;" class="write-link attach"></a>
                <input type="text" />
                <a href="javascript:;" class="write-link smiley"></a>
                <a href="javascript:;" class="write-link send"></a>
            </div>
        </div>
    </div>
</div>

<div id="template-dialog-form" class="chat-dialog" title="აირჩიეთ შაბლონი">
    <select name="template_lang" id="template_lang">
        <option value="ka">Georgian</option>
        <option value="en">English</option>
        <option value="ru">Russian</option>
    </select>

    <select name="template_service" id="template_service" onchange="">
        <option value="0">All</option>
        <option value="1">service 1</option>
        <option value="2">service 2</option>
        <option value="3">service 3</option>
    </select>

    <ul id="template_dialog_form_ul">
        <?php

        foreach ($get_sql_templates as $key => $val) {
            echo "<li data-serviceId='{$val['service_id']}' data-lang='{$val['service_id']}'>{$val['template_text_ge']}</li>";
        }
        ?>
    </ul>
    <script>
        var resizefunc =[];
        var messageTemplates =<?php echo json_encode($get_sql_templates, JSON_UNESCAPED_UNICODE); ?>
    </script>

</div>
<div id="block-dialog" class="chat-dialog" title="გთხოვთ შეიყვანოთ ბლოკირების მიზეზი">
    <textarea rows="12" cols="80">
    </textarea>
</div>

        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.min.js"></script>

        <script type="application/javascript">
            var token = "<?=$this->session->userdata['token'] ; ?>";
            var socketAddr = '<?=substr(base_url(),0,-1);?>';
        </script>
        <script src="<?=base_url();?>assets/js/socket/common.js"></script>
        <script>
            var s = document.createElement("script");
            s.type = "text/javascript";
            s.src = "<?=base_url();?>assets/js/socket/dashboard.js?"+Math.random();
            $("body").append(s);
        </script>
    
    </body>

</html>