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
	 <?php require_once 'components/user_info.php'; ?>
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

                        <?php if(has_role('SHOW_GUESTS_QUEE')) : ?>

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
                        <?php endif;
                        if (has_role('SHOW_ONLINE_CHATS')) : ?>
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
                        </div> <!-- end row --><?php endif;  ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">მიმდინარე საუბრები</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <div class="wrapper_chat">
                                                        <div class="container_chat">
                                                            <div class="left">
                                                                <ul class="people" style="list-style-type: none;">
                                                                    <li class="person" data-chat="person1" style="display: none;">
                                                                        <img src1="//s13.postimg.org/ih41k9tqr/img1.jpg" alt="" />
                                                                        <span class="name">Thomas Bangalter</span>
                                                                        <span class="time">2:09 PM</span>
                                                                        <span class="preview">I was wondering...</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="right">
                                                                <div class="top"><span>To: <span class="name"> </span></span>
                                                                    <span class="close_on_readonly" style="float:right;"><a href="javascript:close_chat();"><img width="30" src="/assets/chat/images/close.png"></a></span>
                                                                    <span class="close_on_readonly" style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                                                                    <span class="close_on_readonly" style="float:right;"><a href="javascript:choose_redirect_type();"><img width="27" src="/assets/chat/images/redirect.png"></a></span>
                                                                    <span class="close_on_readonly" style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                                                                    <span class="close_on_readonly" style="float:right;"><a href="javascript:ban_person();"><img width="30" src="/assets/chat/images/ban_user.png"></a></span>
                                                                    <span class="close_on_readonly" style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
<!--                                                                    <span class="close_on_readonly" style="float:right;"><input type="checkbox" id="im_working_checkbox">ავტოშეხსენება</span>-->
                                                                    <img class="close_on_readonly" style="float:right;height: 29px;" id="im_working_checkbox" src="<?=base_url();?>assets/chat/images/autoremind_off.png">
                                                                    <span class="show_on_readonly" style="float:right;"><a href="javascript:takeRoom()">ჩარევა</a></span>

                                                                </div>
                                                                <div class="chats_container"  style="height: 480px;">
                                                                </div>
                                                                <div class="write close_on_readonly">
                                                                    <a href="javascript:;" class="write-link attach"></a>
                                                                    <a href="javascript:;" class="write-link draft"></a>
                                                                    <input type="text" />
                                                                    <a href="javascript:;" class="write-link send"></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!--end table-responsive-->
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

<!--<div class="clearfix" id="msgbox_container">
    ONLY FOR EXAMPLE
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
</div>-->
<!--
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
                    <img src="//s13.postimg.org/ih41k9tqr/img1.jpg" alt="" />
                    <span class="name">Thomas Bangalter</span>
                    <span class="time">2:09 PM</span>
                    <span class="preview">I was wondering...</span>
                </li>
            </ul>
        </div>
        <div class="right">
            <div class="top"><span>To: <span class="name"> </span></span>
                <span style="float:right;"><a href="javascript:choose_redirect_type();"><img width="27" src="/assets/chat/redirect.png"></a></span>
                <span style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                <span style="float:right;"><a href="javascript:close_chat();"><img width="30" src="/assets/chat/close.png"></a></span>
                <span style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                <span style="float:right;"><a href="javascript:ban_person();"><img width="30" src="/assets/chat/ban_user.png"></a></span>
                <span style="float:right;">&nbsp; &nbsp; &nbsp; &nbsp;</span>
                <span style="float:right;"><input type="checkbox" id="im_working_checkbox">ავტოშეხსენება</span>            </div>
            <div class="chats_container">
            </div>
            <div class="write">
                <a href="javascript:;" class="write-link attach"></a>
                <a href="javascript:;" class="write-link draft"></a>
                <input type="text" />
                <a href="javascript:;" class="write-link send"></a>
            </div>
        </div>
    </div>
</div>-->

<div class="modal fade" id="info_message_panel" tabindex="-1" role="dialog" aria-labelledby="info_message_panel-Label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="info_message_panel-Label"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dialog-form-files" tabindex="-1" role="dialog" aria-labelledby="dialog-form-files-title">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="dialog-form-files-title">ფაილი</h4>
            </div>
            <div class="modal-body">
                    <input type="text" id="dialog_form_files_search_field"><br>
                <table class="table table-striped" id="dialog_form_files_table">
                    <thead>
                    <tr>
                        <th width="50">დათვალიერება</th>
                        <th>ფაილის გაგზავნა</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($files as $key => $val) {
                        $fileName = mb_strlen($val['file_name'])>70? mb_substr($val['file_name'], 0, 67). ' ... ':$val['file_name'];
                        echo "<tr data-keywords='{$val['file_name']}'>";
                        echo "<td class='{$val['file_type']}'><a target='_blank' href='/uploads/{$val['file_name']}''><img width='32'/></a></td>";
//                        echo "<td class='{$val['file_type']}'><span class=\"fa fa-file-image-o\"></span></td>";
                        echo "<td><a href='javascript:send_file({$val['files_id']},\"{$val['file_name']}\");'>{$fileName}</a></td>";
//                        echo "<td>{$fileName}</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
<!--                    <tfoot>
                    <ul class="pagination">
                        <li><a href="#">&laquo;</a></li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#">&raquo;</a></li>
                    </ul>

                    </tfoot>-->
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dialog-form-template" tabindex="-1" role="dialog" aria-labelledby="dialog-form-template-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="dialog-form-template-label">აირჩიეთ შაბლონი</h4>
            </div>
            <div class="modal-body">
                <select name="template_lang" id="template_lang">
                    <option value="ka">Georgian</option>
                    <option value="en">English</option>
                    <option value="ru">Russian</option>
                </select>

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
                    <table class="table table-striped" id="template_dialog_form_table">
<!--                        <thead>-->
<!--                        <tr>-->
<!--                            <th>ტექსტი</th>-->
<!--                        </tr>-->
<!--                        </thead>-->
                        <tbody>
                        <?php
                        foreach ($get_sql_templates as $key => $val) {
                            $keywords = $val['template_text_en'] . ' ' . $val['template_text_ge'] . ' ' . $val['template_text_ru'] . ' ';
                            echo "<tr data-keywords='{$keywords}'  data-service='{$val['service_id']}'  data-lang='en'><td style='text-align: justify;'>{$val['template_text_en']}</td></tr>";
                            echo "<tr data-keywords='{$keywords}'  data-service='{$val['service_id']}'  data-lang='ka'><td style='text-align: justify;'>{$val['template_text_ge']}</td></tr>";
                            echo "<tr data-keywords='{$keywords}'  data-service='{$val['service_id']}'  data-lang='ru'><td style='text-align: justify;'>{$val['template_text_ru']}</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var resizefunc =[];
    var messageTemplates =<?php echo json_encode($get_sql_templates, JSON_UNESCAPED_UNICODE); ?>

</script>

<div class="modal fade" id="dialog-user-block" tabindex="-1" role="dialog" aria-labelledby="dialog-user-block-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="dialog-user-block-title">გთხოვთ შეიყვანოთ ბლოკირების მიზეზი</h4>
            </div>
            <div class="modal-body">
                    <textarea rows="12" style="margin-left: 0px; margin-right: 0px; width: 537px;"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal" onclick="approve_ban_person();">Ok</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chat-redirect-type-dialog" tabindex="-1" role="dialog" aria-labelledby="chat-redirect-type-dialog-Label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="chat-redirect-type-dialog-Label">აირჩიეთ ქმედება</h4>
            </div>
            <div class="modal-body">
                <a href="javascript:choose_redirect_group();">გადამისამართება ჯგუფზე</a><br>
                <a href="javascript:choose_redirect_person_dialog(1);">გადამისამართება პიროვნებაზე</a><br>
                <a href="javascript:choose_redirect_person_dialog(2);">კონფერენცია</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chat-redirect-group-dialog" tabindex="-1" role="dialog" aria-labelledby="chat-redirect-group-dialog-Label">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="chat-redirect-group-dialog-Label">აირჩიეთ ჯგუფი</h4>
        </div>
        <div class="modal-body">
            <table class="table table-hover table-striped">
                <thead><tr>
                    <th width="30%">repository_name</th>
                    <th width="30%">category_name</th>
                    <th width="30%">სერვისი</th>
                </tr></thead>
                <tbody>
                <?php
                foreach ($all_services as $key => $val) {
                    echo "<tr>
        <td>{$val['repository_name']}</td>
        <td>{$val['category_name']}</td>
        <td><a href='javascript:redirect_to_service({$val['category_service_id']}, \"{$val['service_name_geo']}\");'>{$val['service_name_geo']}</a></td>
    </tr>";
                }

                ?>

                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="chat-redirect-person-dialog" tabindex="-1" role="dialog" aria-labelledby="chat-redirect-person-dialog-Label" style="overflow-y: scroll;">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="chat-redirect-person-dialog-Label">აირჩიეთ პიროვნება</h4>
        </div>
        <div class="modal-body">
            <table width="500" class="table table-hover table-striped">
                <thead>
                <tr>
                    <th width="20">id</th>
                    <th>სახელი, გვარი</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($all_users as $key => $val) {
//                    var_dump($val);
                    echo '<tr id="redirect_person_tr_' . $val['person_id'] . '">';
                    echo '<td>' . $val['person_id'] . '</td>';
                    echo '<td class="user_status"><i class="fa fa-circle" aria-hidden="true"></i> &nbsp;&nbsp;<a href="javascript:redirect_to_person(' . $val['person_id'] . ');">' . $val['first_name'] . ' ' . $val['last_name'] . '</a></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
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

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>

    <script src="<?=base_url();?>assets/js/jquery.app.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>


    <script type="application/javascript">
        var token = "<?=$this->session->userdata['token'] ; ?>";
        var socketAddr = '<?=substr(base_url(),0,-1);?>';
        var imWorkingDelay = 1000* <?php  echo $params['repeat_auto_answering'];  ?>;

    </script>
    <script src="<?=base_url();?>assets/chat/js/common.js"></script>

    <script type = "text/javascript" src="<?=base_url();?>assets/chat/js/dashboard-chat.js?<?=rand(); ?>"></script>
    <script type = "text/javascript" src="<?=base_url();?>assets/chat/js/dashboard.js?<?=rand(); ?>"></script>
    <div id="overlay"><div id="loader"></div></div>
</body>

</html>
