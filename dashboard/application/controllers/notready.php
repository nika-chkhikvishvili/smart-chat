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
				 echo "<tr>";
				
				 
				 if($services['state_id']==0)
				  {
					  $x = "<span style='color:#F13E12;'>სტაუტი დაკავებული ვარ</span>";
					  $monishvna = $services['change_date'];
					  $id = $services['id'];
					  
					  $get_out = $this->dashboard_model->get_notready_bygrid(@$_POST['user_id'],$id);
					  if($get_out['id']){
						  $active = "ხელმისაწვდომია";
						  $on_active = $get_out['change_date'];
						
						$start_date = new DateTime($monishvna);
						$end_date = new DateTime($on_active);
						$interval = $start_date->diff($end_date);
						$hours   = $interval->format('%h'); 
						$minutes = $interval->format('%i');
						$full_interval = 'ინტერვალი: '.($hours * 60 + $minutes);
					  }
						echo "<td class='thick-line'>".$services['first_name']."&nbsp;". $services['last_name']."</td>";
						echo "<td class='thick-line text-center'>".$x."&nbsp;".$monishvna."</td>";
						echo "<td class='thick-line text-center'>".$active."&nbsp;".$on_active."</td>"; 
						echo "<td class='thick-line text-center'>&nbsp;".$full_interval."</td>";
						
						
						
				  }
				  else
				  {
					  $x = "ხელმისაწვდომია";
					  $monishvna = $services['change_date'];
					  $xx = "<span style='color:#F13E12;'>ხელმისაწვდომია</span>";
					  $monishvna2 = $services['change_date']; 
					  
					 
                       
				  }
				   echo "</tr>";
					
				 
				
						
				  
                        
                }		
       
	}
    
  
   
}
