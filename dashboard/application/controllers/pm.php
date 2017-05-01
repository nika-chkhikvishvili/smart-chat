<?php

class Pm extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
        
    }
     public function index(){
        $this->load->model('dashboard_model');
        $data['get_offline_messages'] = $this->dashboard_model->get_offline_messages();
        $data['selected_service'] = $this->uri->segment(2);
        $this->load->view('inbox',$data);
    }
    
    function read_pm()
    {
        $this->load->model('dashboard_model');
        $session_data = $this->session->userdata('user');
        
        $data['offline_messages'] = $this->dashboard_model->get_offline_messages_by_id($this->uri->segment(2));
        if(!empty($data['offline_messages']))
        {
        $add_message_status = array (
            'offline_message_status_message_id' => $this->uri->segment(2),
            'offline_message_status_operator_id' => $session_data->person_id,
            'offline_message_status_date' => date('Y-m-d H:i:s')
        );
        if($this->uri->segment(3))
        {           
           $this->dashboard_model->add_offline_message_status($add_message_status); 
        }
        
       
        
        if($this->input->post('answer_text'))
        {          
           $answer_message = array(
            'offline_messages_from' => 'info@psh.gov.ge',
            'offline_messages_name' =>  $session_data->first_name, 
            'offline_messages_lastname' =>  $session_data->last_name, 
            'offline_messages_titile' =>  $this->input->post('answer_title'), 
            'offline_messages_text' =>  $this->input->post('answer_text'), 
            'offline_messages_date' =>  date('Y-m-d H:i:s'), 
            'offline_messages_operator' =>  $session_data->person_id, 
            'offline_messages_parent' => $this->uri->segment(2), 
           );
           $data['messages_answers'] = $this->dashboard_model->add_offline_messages_answer($answer_message);
           echo '<meta http-equiv="refresh" content="5">';
        }
        
        $data['messages_answers'] = $this->dashboard_model->get_offline_messages_answer($this->uri->segment(2));
        $this->load->view('read_inbox',$data);
        }
        else 
        {
          $this->load->view('500',$data);  
        }    
        
    }
}
