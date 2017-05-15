<?php
$session_data = $this->session->userdata('user');
?>
<div class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?=$session_data->first_name;?></a>

</div>
<p class="text-muted m-0"><?=$session_data->last_name;?></p>