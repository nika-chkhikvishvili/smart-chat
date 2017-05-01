<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Templates extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
        $data['notify'] = 0;
        $session_data = $this->session->userdata('user');       
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');	
        
        $data['get_sql_services'] = $this->dashboard_model->get_services($session_data->repo_id);
        $data['get_sql_templates'] = $this->dashboard_model->get_message_templates();
        if($this->input->post('add_tem')){
           
            $this->form_validation->set_rules(
                'template_text_ge', 'შაბლონის ტექსტი ქართულად',
                'required',
                array('required' => $this->lang->line('required')));
             
             if ($this->form_validation->run() == TRUE) {
                 unset($_POST['add_tem']);
                 $this->dashboard_model->add_message_template($_POST);
                  $data['notify'] = 1;
             }
            
        }
        $this->load->view('message_templates',$data);
    }
    
     public function update_templates(){
        $data['notify'] = 0;
        $session_data = $this->session->userdata('user');
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');
        
        $data['sql_templates'] = $this->dashboard_model->get_one_message_templates($this->uri->segment(3));
        $data['sql_services'] = $this->dashboard_model->get_services($session_data->repo_id);
        if ($this->input->post('update')) 
         {           
         $this->dashboard_model->update_message_template($this->uri->segment(3, 0), $_POST); 
          
         $data['notify'] = 1;                    
          
         }
        $this->load->view('update_templates', $data);
    }
    
     public function delete_templates(){
        $this->load->model('dashboard_model');
      
        if ($this->input->post('id')) {
            $error = true;
            $this->dashboard_model->delete_message_templates($this->input->post('id'));
            $msg = array('status' => !$error, 'msg' => 'შაბლონის ტექსტი წაშლილია...');
            echo json_encode($msg);
        }
    }
    
   
}
