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
        $this->load->view('admin_main_dashboard',$data);
    }
    
    function message_templates()
    {
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
             }
            
        }
        $this->load->view('message_templates',$data);
    }
    
    function system()
    {
      
      $this->load->view('system');  
    }
    
    function inbox()
    {
        $this->load->view('inbox');
    }
    
    function read_inbox()
    {
        $this->load->view('read_inbox');
    }
    
    public function blacklist()
    {
        $this->load->model('dashboard_model');
        $data['get_banlist'] = $this->dashboard_model->get_banlist();
        $data['get_blocklist'] = $this->dashboard_model->get_blocklist();
       
        $this->load->view('blacklist',$data);
    }
    
    public function blacklist_chat()
    {
        $chat_id =  $this->uri->segment(3);
        $this->load->model('dashboard_model');
        $data['get_chat'] = $this->dashboard_model->get_chat_history(1);
        var_dump($data['get_chat']);
        $this->load->view('blacklist_chat',$data);
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
