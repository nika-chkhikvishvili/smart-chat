<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Blacklist extends CI_Controller{

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
       
        $this->load->view('blacklist',$data);
    }
    
   public function blacklist_chat()
    {
      
        $data['get_uri_chat_id'] = $this->uri->segment(3);
        $this->load->model('dashboard_model');
        $data['get_chat'] = $this->dashboard_model->get_chat_history($data['get_uri_chat_id']);
        $data['ban_list'] = $this->dashboard_model->get_one_banlist($data['get_uri_chat_id']);
        
        $this->load->view('blacklist_chat',$data);
    }
    
    public function reconfirm_banlist()
    {   
        $error = true;
        $this->load->model('dashboard_model');
        $data['get_banlist'] = $this->dashboard_model->reconfirm_banlist($this->input->post('val'));       
         $msg = array('status' => !$error, 'msg' => 'მომხმარებლის IP მისამართი დაბლოკილია!');
         echo json_encode($msg);
       
    }
    
    public function confutation_banlist()
    {   
        $error = true;
        $this->load->model('dashboard_model');
        $data['get_banlist'] = $this->dashboard_model->confutation_banlist($this->input->post('val'));       
        $msg = array('status' => !$error, 'msg' => 'მომხმარებლის IP მისამართზე ბანი გაუქმებულია.');
        echo json_encode($msg);        
       
    }
   
}
