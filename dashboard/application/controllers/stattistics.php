<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class stattistics extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
       $data['notify'] = ""; 
       $this->load->model('dashboard_model'); 
       $session_data = $this->session->userdata('user');
       $data['get_services'] = $this->dashboard_model->get_services($session_data->repo_id);
       $data['get_persons'] = $this->dashboard_model->get_persons();
      
       $this->load->view('stattistics', $data);
    }
    
    
    function get_all_data()
    {
        $session_data = $this->session->userdata('user');
        $this->load->model('dashboard_model');
        
        if(@$_POST['service_id'] || @$_POST['user_id'] || @$_POST['date']){
        $service_id = "";
        $user_id = "";
        $by_date = "";
        if(@$_POST['service_id']>=1 || @$_POST['user_id'] || $_POST['date']!="")
        {
          $service_id = @$_POST['service_id'];
          $user_id = @$_POST['user_id'];
          $by_date = @$_POST['date'];
        }
        $waiting = $this->dashboard_model->get_statistic_byarg($service_id,$user_id,$by_date,0);
        $active = $this->dashboard_model->get_statistic_waiting($service_id,$user_id,$by_date,1);
        $redirecting = $this->dashboard_model->get_statistic_waiting($service_id,$user_id,$by_date,2);
        $closed = $this->dashboard_model->get_statistic_waiting($service_id,$user_id,$by_date,3); 
       
        echo '
	<ul class="list-group">
        <li class="list-group-item">
            <span class="badge badge-primary">'.$waiting.'</span>
           საუბრის დაწყების მოლოდინში
        </li>
        <li class="list-group-item">
            <span class="badge badge-purple">'.$active.'</span>
            საუბარში ჩართული მომხამრებლები
        </li>
        <li class="list-group-item">
            <span class="badge badge-inverse">'.$redirecting.'</span>
            სულ გადამისამართებული
        </li>
        <li class="list-group-item">
            <span class="badge badge-pink">'.$closed.'</span>
           სულ საუბრები
        </li>
        </ul>';
        
        
        }
        else 
        {
        $waiting = $this->dashboard_model->get_statistic_waiting(0);
        $active = $this->dashboard_model->get_statistic_waiting(1);
        $redirecting = $this->dashboard_model->get_statistic_waiting(2);
        $closed = $this->dashboard_model->get_statistic_waiting(3);    
        echo '
	<ul class="list-group">
        <li class="list-group-item">
            <span class="badge badge-primary">'.$waiting.'</span>
           საუბრის დაწყების მოლოდინში
        </li>
        <li class="list-group-item">
            <span class="badge badge-purple">'.$active.'</span>
            საუბარში ჩართული მომხამრებლები
        </li>
        <li class="list-group-item">
            <span class="badge badge-inverse">'.$redirecting.'</span>
            სულ გადამისამართებული
        </li>
        <li class="list-group-item">
            <span class="badge badge-pink">'.$closed.'</span>
           სულ საუბრები
        </li>
        </ul>';
        
        }
        
       
      
      
    }
   

    function logout(){
        redirect('logout');
    }
}
