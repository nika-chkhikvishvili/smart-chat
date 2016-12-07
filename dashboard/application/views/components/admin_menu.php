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
            <li><a href="<?=base_url();?>">სისტემის მართვა</a></li>
            <li><a href="<?=base_url();?>message_templates">შაბლონების მართვა</a></li>
            <li><a href="panels.html">პირადი პარამეტრები</a></li>
        </ul>
    </li>


   
</ul>
<div class="clearfix"></div>
