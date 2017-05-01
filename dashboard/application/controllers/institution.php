<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class institution extends CI_Controller{

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
        $data['get_institutions'] = $this->dashboard_model->get_institutions($session_data->repo_id);
        $data['institution_name'] = "";

        if ($this->input->post('add_institution')) {
            $this->form_validation->set_rules(
                'institution_name', 'უწყების დასახელება',
                'required|min_length[5]',
                array(
                    'required' => $this->lang->line('required'),
                    'valid_email' => $this->lang->line('valid_email')
                ));
            if ($this->form_validation->run() == TRUE) {
                $add_institution_data = array
                (
                    'repository_id' => $session_data->repo_id,
                    'category_name' => $this->input->post('institution_name')
                );
                $information_object = array(
                    'information_object_repo' => $session_data->repo_id,
                    'information_object_table' => 'repo_categories',
                    'information_object_rowid' => '',
                    'information_object_person' => $session_data->person_id,
                    'information_object_event' => 'insert',
                    'information_object_date' => date("Y-m-d H:i:s"),
                );
                if ($this->dashboard_model->add_institution($add_institution_data, $information_object)) {
                    $data['notify'] = 1;
                }
            }

        }
       
       $this->load->view('add_institution', $data);
    }
    
    
    

    public function update_institution(){
        $data['notify'] = 0;
        $session_data = $this->session->userdata('user');
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');       
        $data['sql_institutions'] = $this->dashboard_model->get_one_institutions($this->uri->segment(3));
        if ($this->input->post('update')) 
         {           
         $this->dashboard_model->update_institution($this->uri->segment(3, 0), $this->input->post('institution_name')); 
          
         $data['notify'] = 1;                    
          
         }
        $this->load->view('update_institution', $data);
    }

    public function delete_institution(){
        if ($this->input->post('id')) {
            $this->load->library('institutions');
            echo($this->institutions->delete_institution($this->input->post('id')));
        }
    }

   

    function logout(){
        redirect('logout');
    }
}
