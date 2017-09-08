<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 2017-05-20
 * Time: 8:32 PM
 */


$conn = new mysqli('localhost','smartchat','smartchat','smartchat');
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die('Error : ('. $conn->connect_errno .') '. $conn->connect_error);
}

$sys_control_params_results = $conn->query("SELECT operator_max_load, pass_life_time, history_life_time, passive_client_time FROM sys_control limit 1");


$auto_answering_results = $conn->query("SELECT
start_chating_geo, start_chating_rus, start_chating_eng, waiting_message_geo, 
waiting_message_rus, waiting_message_eng, connect_failed_geo, connect_failed_rus, connect_failed_eng, 
user_block_geo, user_block_rus, user_block_eng, auto_answering_geo, auto_answering_rus, auto_answering_eng, 
repeat_auto_answering, time_off_geo, time_off_rus, time_off_eng, passive_client_geo, passive_client_rus, 
passive_client_eng FROM auto_answering limit 1");

$services_list_results = $conn->query(
        'SELECT cs.category_service_id, rc.repository_id, rc.category_name, 
               cs.service_name_geo, cs.service_name_rus, cs.service_name_eng, cs.start_time, cs.end_time
           FROM category_services cs, repo_categories rc 
     WHERE cs.repo_category_id = rc.repo_category_id');

$lang = 'ka_GE';
$service_name = 'service_name_geo';
$begin_btn_text = 'საუბრის დაწყება';
if (array_key_exists('lang', $_GET)) {
    switch ($_GET['lang']){
        case 'ka_GE': $lang = 'ka_GE'; $service_name = 'service_name_geo'; $begin_btn_text = 'საუბრის დაწყება'; break;
        case 'en_US': $lang = 'en_US'; $service_name = 'service_name_eng'; $begin_btn_text = 'Start conversation';  break;
        case 'ru_RU': $lang = 'ru_RU'; $service_name = 'service_name_rus'; $begin_btn_text = 'Начать';  break;
    }
}

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online support</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" type="text/css" />
    <link rel="stylesheet" href="css/flag-icon.min.css" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css"/>

    <link rel="stylesheet" href="libs/bootstrap-formhelpers/bootstrap-formhelpers.min.css" type="text/css" />
<style>
    html, body {
        height:97%
    }

    body{
        margin-top:20px;
        background:#ebeef0;
    }
    .panel {
        box-shadow: 0 2px 0 rgba(0,0,0,0.075);
        border-radius: 0;
        border: 0;
        margin-bottom: 24px;
    }
    .panel .panel-heading, .panel>:first-child {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    .panel-heading {
        position: relative;
        height: 50px;
        padding: 0;
        border-bottom:1px solid #eee;
    }
    .panel-control {
        height: 100%;
        position: relative;
        float: right;
        padding: 0 15px;
    }
    .panel-title {
        font-weight: normal;
        padding: 0 20px 0 20px;
        font-size: 1.416em;
        line-height: 50px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .panel-control>.btn:last-child, .panel-control>.btn-group:last-child>.btn:first-child {
        border-bottom-right-radius: 0;
    }
    .panel-control .btn, .panel-control .dropdown-toggle.btn {
        border: 0;
    }
    .nano {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .nano>.nano-content {
        position: absolute;
        overflow-y: scroll;
        overflow-x: hidden;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    .pad-all {
        padding: 15px;
    }
    .mar-btm {
        margin-bottom: 15px;
    }
    .media-block .media-left {
        display: block;
        float: left;
    }
    .img-sm {
        width: 46px;
        height: 46px;
    }
    .media-block .media-body {
        display: block;
        overflow: hidden;
        width: auto;
    }
    .pad-hor {
        padding-left: 15px;
        padding-right: 15px;
    }
    .speech {
        position: relative;
        /*background: #b7dcfe;*/
        background: #7ab800;
        /*color: #317787;*/
        color: white;
        display: inline-block;
        border-radius: 24px;
        max-width: 80%;
        padding: 12px 20px;
    }
   /* .speech:before {
        content: "";
        display: block;
        position: absolute;
        width: 0;
        height: 0;
        left: 0;
        top: 0;
        border-top: 7px solid transparent;
        border-bottom: 7px solid transparent;
        border-right: 7px solid #b7dcfe;
        margin: 15px 0 0 -6px;
    }
    .speech-right>.speech:before {
        left: auto;
        right: 0;
        border-top: 7px solid transparent;
        border-bottom: 7px solid transparent;
        border-left: 7px solid #ffdc91;
        border-right: 0;
        margin: 15px -6px 0 0;
    }*/
    .speech .media-heading {
        font-size: 1.2em;
        /*color: #317787;*/
        color: white;
        display: block;
        border-bottom: 1px solid rgba(0,0,0,0.1);
        margin-bottom: 10px;
        padding-bottom: 5px;
        font-weight: 300;
    }
    .speech-time {
        margin-top: 20px;
        margin-bottom: 0;
        font-size: .8em;
        font-weight: 300;
    }
    .media-block .media-right {
        float: right;
    }
    .speech-right {
        text-align: right;
    }
    .pad-hor {
        padding-left: 15px;
        padding-right: 15px;
    }
    .speech-right>.speech {
        /*background: #ffda87;*/
        background: #8d8a8a;
        /*color: #a07617;*/
        /*color: white;*/
        text-align: right;
    }
    .speech-right>.speech .media-heading {
        /*color: #a07617;*/
        /*color: white;*/
    }
    .btn-primary, .btn-primary:focus, .btn-hover-primary:hover, .btn-hover-primary:active, .btn-hover-primary.active, .btn.btn-active-primary:active, .btn.btn-active-primary.active, .dropdown.open>.btn.btn-active-primary, .btn-group.open .dropdown-toggle.btn.btn-active-primary {
        background-color: #579ddb;
        border-color: #5fa2dd;
        color: #fff !important;
    }
    .btn {
        cursor: pointer;
        /* background-color: transparent; */
        color: inherit;
        padding: 6px 12px;
        border-radius: 0;
        border: 1px solid 0;
        font-size: 11px;
        line-height: 1.42857;
        vertical-align: middle;
        -webkit-transition: all .25s;
        transition: all .25s;
    }
    .form-control {
        font-size: 11px;
        height: 100%;
        border-radius: 0;
        box-shadow: none;
        border: 1px solid #e9e9e9;
        transition-duration: .5s;
    }
    .nano>.nano-pane {
        background-color: rgba(0,0,0,0.1);
        position: absolute;
        width: 5px;
        right: 0;
        top: 0;
        bottom: 0;
        opacity: 0;
        -webkit-transition: all .7s;
        transition: all .7s;
    }

    #operator_is_working, #operator_is_writing{
        display : none;
        color :red;
    }

    #chat_window, #wait_operator {
        display: none;
    }
    #asarchevi{
        /*width: 300px;*/
        /*margin: auto;*/
    }
    .center-block{
        float: none;
        margin: 0 auto;
    }

    @-webkit-keyframes typing { from { width: 0; } }
    @-webkit-keyframes blink-caret { 50% { border-color: transparent; } }

    .operator_is_working {
        /*display:none;*/
        /*bottom: 83px;*/
        /*position: fixed;*/
        z-index: 0;
        font: bold Consolas, Monaco, monospace;
        border-right: .1em solid black;
        width: 19.5em;
        width: 20ch;
        margin: 2em 1em;
        white-space: nowrap;
        overflow: hidden;
        -webkit-animation: typing 2s steps(21, end),
        blink-caret .5s step-end infinite alternate;
    }

    #popover_big_image {height: 300px; position: absolute;}

    .nopadding {
        padding-top: 0;
        padding-right: 0;
        padding-bottom: 0;
        padding-left: 0;
    }

</style>
</head>
<body>
<div class="container" style="height:100%">
    <div class="user_ban"></div>

    <div id="asarchevi" class="col-md-6 col-md-offset-3">
        <div class="form-group">
            <div class="bfh-selectbox bfh-languages" data-language="<?php
            echo $lang; ?>" data-available="ka_GE,en_US,ru_RU" data-flags="true" data-blank="false">
            </div>
        </div>
        <div class="form-group">
            <label for="first_name" id="first_name_label">First Name:</label>
            <input type="text" class="form-control" id="first_name" placeholder="First Name">
        </div>
        <div class="form-group">
            <label for="last_name" id="last_name_label">Last Name:</label>
            <input type="text" class="form-control" id="last_name" placeholder="Last Name">
        </div>
        <div class="form-group">
            <label for="select_theme" id="service_label">Choose Service:</label>
            <select id="select_theme" name="select_theme" class="selectpicker col-md-12 col-xs-12 nopadding">
                <?php

                if ($services_list_results) {
                    while($row = mysqli_fetch_assoc($services_list_results)) {
                        $services_list[$row['category_service_id']] = $row;
                        echo "<option value='{$row['category_service_id']}'>{$row[$service_name]}</option>";
                     }      
                }
                ?>
            </select>
        </div>

        <button class="btn btn-default" id="begin_btn" ><?php echo $begin_btn_text; ?></button>
    </div>

    <div id="wait_operator" class="col-md-12 col-lg-8 center-block" style="height:90%">
        გთხოვთ დაელოდოთ ოპერატორს, თუ არ გსურთ ლოდინი, თქვენი შეკითხვა შეგიძლიათ გამოაგზავნოთ <a href="/offline.php" >მაილზე</a>
    </div>

    <div id ="chat_window" class="col-md-12 col-lg-8 center-block" style="height:90%">
        <div class="panel" style="height:100%">
        	<!--Heading-->
    		<div class="panel-heading">
    			<div class="panel-control">
<!--    				<div class="btn-group">
    					<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#chat-body"><i class="fa fa-chevron-down"></i></button>
    					<button type="button" class="btn btn-default" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
    					<ul class="dropdown-menu dropdown-menu-right">
    						<li><a href="#">Available</a></li>
    						<li><a href="#">Busy</a></li>
    						<li><a href="#">Away</a></li>
    						<li class="divider"></li>
    						<li><a id="demo-connect-chat" href="#" class="disabled-link" data-target="#chat-body">Connect</a></li>
    						<li><a id="demo-disconnect-chat" href="#" data-target="#chat-body">Disconect</a></li>
    					</ul>
    				</div>-->
                    <span id="operator_is_writing"> <img src="/assets/images/typing.gif" style="height: 16px;margin-left: 10px;" > </span>
                    <a id="disconnect-chat" href="#" data-target="#chat-body"><img src="assets/images/close.png"
                       style="margin-top: 13px;height: 20px;" /></a>
    			</div>
<!--    			<h3 class="panel-title">Chat</h3>-->
    		</div>
    		<!--Widget body-->
    		<div id="chat-body" class="collapse in" style="height:100%">
    			<div class="nano has-scrollbar" style="height:90%">
    				<div class="nano-content pad-all" tabindex="0" style="/*right: -17px;*/" id="scrooldiv">
    					<ul class="list-unstyled media-block" id="chat-body-ul">
    						<li class="mar-btm">
    							<div class="media-left">
    								<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="img-circle img-sm" alt="Profile Picture">
    							</div>
    							<div class="media-body pad-hor">
    								<div class="speech">
    									<a href="#" class="media-heading">John Doe</a>
    									<p>Hello Lucy, how can I help you today ?</p>
    									<p class="speech-time">
    									<i class="fa fa-clock-o fa-fw"></i>09:23AM
                                        </p>
    								</div>
    							</div>
    						</li>
    						<li class="mar-btm">
    							<div class="media-right">
    								<img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="img-circle img-sm" alt="Profile Picture">
    							</div>
    							<div class="media-body pad-hor speech-right">
    								<div class="speech">
    									<a href="#" class="media-heading">Lucy Doe</a>
    									<p>Hi, I want to buy a new shoes.</p>
    									<p class="speech-time">
    										<i class="fa fa-clock-o fa-fw"></i> 09:23AM
                                        </p>
    								</div>
    							</div>
    						</li>
    						<li class="mar-btm">
    							<div class="media-left">
    								<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="img-circle img-sm" alt="Profile Picture">
    							</div>
    							<div class="media-body pad-hor">
    								<div class="speech">
    									<a href="#" class="media-heading">John Doe</a>
    									<p>Shipment is free. You\'ll get your shoes tomorrow!</p>
    									<p class="speech-time">
    										<i class="fa fa-clock-o fa-fw"></i> 09:25
    									</p>
    								</div>
    							</div>
    						</li>
    					</ul>
    				</div>
    			<div class="nano-pane"><div class="nano-slider" style="height: 141px; transform: translate(0px, 0px);"></div></div></div>

    			<!--Widget footer-->
    			<div class="panel-footer">
    				<div class="row">
    					<div class="col-xs-9">
    						<input type="text" placeholder="Enter your text" class="form-control chat-input" id="usermsg">
    					</div>
    					<div class="col-xs-3">
    						<button class="btn btn-primary btn-block" style="background: black;" type="submit" id="submitmsg">Send</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>
<img id="popover_big_image" src="" style="display: none">

<div class="modal fade" id="info_message_panel" tabindex="-1" role="dialog" aria-labelledby="info_message_panel-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="info_message_panel-label">  </h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    <?php
    echo 'var sys_control_params = ';
    if ($sys_control_params_results) {
        $row = $sys_control_params_results->fetch_assoc();
        echo  json_encode($row,JSON_UNESCAPED_UNICODE),";\n";
    } else {
        echo "{};\n";
    }

    echo 'var auto_answering = ';
    if ($auto_answering_results) {
        $row = $auto_answering_results->fetch_assoc();
        echo  json_encode($row, JSON_UNESCAPED_UNICODE),";\n";
    } else {
        echo "{};\n";
    }

    echo 'var services_list = ';
    if ($services_list) {
        echo  json_encode($services_list, JSON_UNESCAPED_UNICODE),";\n";
    } else {
        echo "{};\n";
    }

    ?>
</script>
<script
    src="https://code.jquery.com/jquery-3.2.1.js"
    integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
    crossorigin="anonymous"></script>

<script src='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>


<script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>
<script src="//jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="//jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
<script src="libs/bootstrap-formhelpers/bootstrap-formhelpers.min.js"></script>
<script src="libs/bootstrap-formhelpers/bootstrap-formhelpers-languages.js"></script>
<script src="chat_client.js?<?=rand(); ?>"></script>
<script src="main_client.js?<?=rand(); ?>"></script>
</body>
</html>
