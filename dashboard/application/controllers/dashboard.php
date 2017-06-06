<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Dashboard extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
        $session_data = $this->session->userdata('user');
        $this->load->model('dashboard_model');
        $data['get_sql_templates'] = $this->dashboard_model->get_message_templates();
        $data['all_services'] = $this->dashboard_model->get_all_full_services();
        $data['params'] = $this->dashboard_model->get_answering();
        $data['files'] = $this->dashboard_model->get_files($session_data->repo_id);
        $this->load->view('admin_main_dashboard',$data);
    }
    
   
    
    function system()
    {      
      $this->load->view('system');  
    }
    
    public function answering()
    {
        $this->load->model('dashboard_model');
        $data['get_answering'] = $this->dashboard_model->get_answering();
        if($this->input->post('save_answering'))
        {
           unset($_POST['save_answering']);
           	
           $this->dashboard_model->update_answering(1,$_POST);
          echo '<meta http-equiv="refresh" content="0">';
        }
         $this->load->view('answering',$data);
    }
    
    function logout(){
        redirect('logout');
    }
}
