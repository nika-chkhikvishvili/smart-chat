<?php
/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 5/11/16
 * Time: 9:35 PM
 */
?><div class="left side-menu">
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
            <?php echo $sidebar_menu; ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>