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
                        <?php require_once 'components/user_info.php'; ?>
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
                <span style="float:right;"><a href="javascript:choose_redirect_type();"><img width="27" src="https://bounty.github.com/images/badges/A10-1.png"></a></span>
                <span style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                <span style="float:right;"><a href="javascript:close_chat();"><img width="30" src="http://icons.iconarchive.com/icons/oxygen-icons.org/oxygen/32/Actions-window-close-icon.png"></a></span>
                <span style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                <span style="float:right;"><a href="javascript:ban_person();"><img width="30" src="http://www.fastkashmir.com/wp-content/uploads/2017/03/ban-2.png"></a></span>
                <span style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                <span style="float:right;"><input type="checkbox" id="im_working_checkbox">ავტოშეხსენება</span>            </div>
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
    </select><br>

    <select name="template_service" id="template_service" onchange="">
        <option value="0">All</option>
        <?php
        $services = [];

        foreach ($get_sql_templates as $key => $val) {
            $services[$val['service_id']] = $val['service_name_geo'];
        }

        foreach ($services as $key => $val) {
           echo "<option value='{$key}'>{$val}</option>";
        }

        ?>
    </select>
    <input type="text" id="template_dialog_form_search_field"><br>

    <ul id="template_dialog_form_ul">
        <?php

        foreach ($get_sql_templates as $key => $val) {
            echo "<li data-serviceId='{$val['service_id']}' data-lang='{$val['service_id']}'>{$val['template_text_ge']}</li>";
        }
        ?>
    </ul>
</div>

<script>
    var resizefunc =[];
    var messageTemplates =<?php echo json_encode($get_sql_templates, JSON_UNESCAPED_UNICODE); ?>
</script>

<div id="block-dialog" class="chat-dialog" title="გთხოვთ შეიყვანოთ ბლოკირების მიზეზი">
    <textarea rows="12" cols="50"></textarea>
</div>

<div id="chat-redirect-type-dialog" class="chat-redirect-type-dialog" title="აირჩიეთ ქმედება">
    <a href="javascript:choose_redirect_group();">გადამისამართება ჯგუფზე</a><br>
    <a href="javascript:choose_redirect_person_dialog(1);">გადამისამართება პიროვნებაზე</a><br>
    <a href="javascript:choose_redirect_person_dialog(2);">conference call</a>

</div>

<div id="chat-redirect-group-dialog" class="chat-dialog" title="აირჩიეთ ჯგუფი">
    <table width="500">
        <tr>
            <th width="10%">id</th>
            <th width="30%">repository_name</th>
            <th width="30%">category_name</th>
            <th width="30%">სერვისი</th>
        </tr>
        <tbody>
        <?php
        foreach ($all_services as $key => $val) {
            echo "<tr>
        <td>{$val['category_service_id']}</td>
        <td>{$val['repository_name']}</td>
        <td>{$val['category_name']}</td>
        <td><a href='javascript:redirect_to_service({$val['category_service_id']});'>{$val['service_name_geo']}</a></td>
    </tr>";
        }

        ?>

        </tbody>
    </table>

</div>

<div id="chat-redirect-person-dialog" class="chat-dialog" title="აირჩიეთ პიროვნება">
    <table width="500">
        <thead>
        <tr>
            <th width="20%">id</th>
            <th width="80%">სახელი, გვარი</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>





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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>

        <script type="application/javascript">
            var token = "<?=$this->session->userdata['token'] ; ?>";
            var socketAddr = '<?=substr(base_url(),0,-1);?>';
        </script>
        <script src="<?=base_url();?>assets/js/socket/common.js"></script>

        <script type = "text/javascript" src="<?=base_url();?>assets/js/socket/dashboard.js?<?=rand(); ?>"></script>
    
    </body>

</html>