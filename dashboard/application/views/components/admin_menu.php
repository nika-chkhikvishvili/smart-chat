<ul>
    <li>
        <a href="<?= base_url(); ?>dashboard" class="waves-effect active"><i class="md md-home"></i><span> მთავარი გვერდი </span></a>
    </li>

    <li class="has_sub">
        <a href="#" class="waves-effect"><i class="md md-mail"></i><span>შეტყობინებები</span><span class="pull-right"><i
                    class="md md-add"></i></span></a>
        <ul class="list-unstyled">
            <li><a href="inbox.html">შემოსული</a></li>
            <li><a href="email-compose.html">მაილის გაგზავნა</a></li>
            <li><a href="email-read.html">შეტყობინების ნახვა</a></li>
        </ul>
    </li>

    <li>
        <a href="calendar.html" class="waves-effect"><i class="md md-event"></i><span> ისტორია </span></a>
    </li>

    <li class="has_sub">
        <a href="#" class="waves-effect"><i class="md md-palette"></i> <span> ადმინისტრირება </span> <span
                class="pull-right"><i class="md md-add"></i></span></a>
        <ul class="list-unstyled">
            <li><a href="<?= base_url(); ?>add_institution">უწყებების მართვა</a></li>
            <li><a href="<?= base_url(); ?>add_services">სერვისების მართვა</a></li>
            <li><a href="<?= base_url(); ?>persons">მომხმარებლის მართვა</a></li>
            <li><a href="buttons.html">სისტემის მართვა</a></li>
            <li><a href="panels.html">შაბლონების მართვა</a></li>
            <li><a href="panels.html">პირადი პარამეტრები</a></li>
        </ul>
    </li>


    <li class="has_sub">
        <a href="<?= base_url(); ?>dashboard/persons" class="waves-effect"><i class="md md-share"></i><span>მომხმარებლები</span><span
                class="pull-right"><i class="md md-add"></i></span></a>
        <ul>
            <li class="has_sub">
                <a href="javascript:void(0);" class="waves-effect"><span>Menu Level 1.1</span> <span class="pull-right"><i
                            class="md md-add"></i></span></a>
                <ul style="">
                    <li><a href="javascript:void(0);"><span>Menu Level 2.1</span></a></li>
                    <li><a href="javascript:void(0);"><span>Menu Level 2.2</span></a></li>
                    <li><a href="javascript:void(0);"><span>Menu Level 2.3</span></a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"><span>Menu Level 1.2</span></a>
            </li>
        </ul>
    </li>
</ul>
<div class="clearfix"></div>
