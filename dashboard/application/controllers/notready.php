<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Notready extends CI_Controller{

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
    
  
   
}
