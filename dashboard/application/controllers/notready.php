<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class notready extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
        
        $this->load->model('dashboard_model');
        $data['get_banlist'] = $this->dashboard_model->get_banlist();
        $data['get_blocklist'] = $this->dashboard_model->get_blocklist();
		
        $data['get_persons'] = $this->dashboard_model->get_persons();
        $this->load->view('not_ready',$data);
    }
	
	
		function get_all_notread()
	{
		
		   
		   $this->load->model('dashboard_model');
           $byservice = $this->dashboard_model->get_notready_byuser(@$_POST['user_id']);
            echo '<table class="table table-condensed">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody>
                ';
				
		foreach($byservice as $services){
				  if($services['state_id']==0)
				  {
					  $x = "<span style='color:#F13E12;'>მონიშნა სტატუსი დაკავებული ვარ</span>";
				  }
				  else{
					  $x = "მოიხსნა სტატუსი დაკავებული ვარ";
				  }
                  echo "<tr>
                        <td class='thick-line'>".$services['first_name']."&nbsp;". $services['last_name']."</td>                        
                        <td class='thick-line text-center'>".$x."</td>
                        <td class='thick-line text-right'>".$services['change_date']."</td>
                        </tr>";          
                }		
       
	}
    
  
   
}
