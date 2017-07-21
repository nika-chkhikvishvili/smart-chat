<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class History extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
        
        $this->load->model('dashboard_model');
        
        $this->load->library("pagination");
        $sql_history = $this->dashboard_model->count_history('','','');
        $record = array();
        $name = array();
        foreach($sql_history as $key=>$value){
        if(!in_array($value['chat_id'], $name)){
               $name[] = $value['chat_id'];
               $record[$key] = $value;
        }

      }
        $config = array();
        $config["base_url"] = base_url()."history";
        $config["total_rows"] = count($record);
        $config["per_page"] = 15;
        $config["uri_segment"] = 2;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config["full_tag_close"] = '</ul>';	
        $config["first_link"] = "პირველი";
        $config["first_tag_open"] = "<li>";
        $config["first_tag_close"] = "</li>";
        $config["last_link"] = "ბოლო";
        $config["last_tag_open"] = "<li>";
        $config["last_tag_close"] = "</li>";
        $config['next_link'] = 'მომდევნო';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '<li>';
        $config['prev_link'] = 'წინა';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '<li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links(); 
        $data['history'] = $this->dashboard_model->get_all_history('','','',$page);	
        
       $data['get_services'] = $this->dashboard_model->get_all_services();
        $this->load->view('chat_history',$data);
    }
    
    
    function view_history()
    {
        $data['get_uri_chat_id'] = $this->uri->segment(2);
        $this->load->model('dashboard_model');
        $data['get_chat'] = $this->dashboard_model->get_chat_history($data['get_uri_chat_id']);
        
        $this->load->view('view_history',$data);
    }
    
 
    
    
   
}
