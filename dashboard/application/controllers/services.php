<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
        $session_data = $this->session->userdata('user');
        $data['error_message']="";
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');		
        $data['get_sql_services'] = $this->dashboard_model->get_services($session_data->repo_id);
        $data['sql_institutions'] = $this->dashboard_model->get_institutions($session_data->repo_id);

        if ($this->input->post('add_service')) {
           
            
            $this->form_validation->set_rules(
                'service_name_geo', 'სერვისის დასახელება',
                'required|min_length[5]',
                array(
                    'required' => $this->lang->line('required'),
                    'valid_email' => $this->lang->line('valid_email')
                ));
           
            if($this->input->post('repo_categories')>=1)
            {
            if ($this->form_validation->run() == TRUE) {
                $add_institution_data = array
                (
                    'repo_category_id' => $this->input->post('repo_categories'),
                    'service_name_geo' => $this->input->post('service_name_geo'),
                    'service_name_rus' => $this->input->post('service_name_rus'),
                    'service_name_eng' => $this->input->post('service_name_eng'),
                    'start_time' => $this->input->post('start_time'),
                    'end_time' => $this->input->post('end_time')
                );
                if ($this->dashboard_model->add_services($add_institution_data)) {
                    echo '<meta http-equiv="refresh" content="0">';
                }}            
            } 
            else 
            {
              $data['error_message'] = "მონიშნეთ უწყების დასახელება";              
            }

        }       
        $this->load->view('add_services', $data);
    }
    
    
    
  public function update_service() {
        $session_data = $this->session->userdata('user');
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');

        $data['sql_institutions'] = $this->dashboard_model->get_institutions($session_data->repo_id);
        $data['service'] = $this->dashboard_model->get_service($session_data->repo_id, $this->uri->segment(3, 0));

        if ($data['service'] == 0) {
            $data['error_code'] = "Error!";
            $data['error_title'] = "არასწორი პარამეტრების გადმოცემა!";
            $data['error_description'] = "სერვისის რედაქტირებისთვის მოხდა არასწორი პარამეტრების გადმოცემა, ვერცერთი ოპერაცია ვერ შესრულდება.თქვენ შეგიძლიათ მთავარ გვერდზე დაბრუნება!";
            $this->load->view('notification', $data);
        } else {
            if ($this->input->post('update')) {               
                if ($this->dashboard_model->update_services($this->uri->segment(3, 0), $this->input->post())) {
                    echo '<meta http-equiv="refresh" content="3">';
                }
            }
            $this->load->view('update_services', $data);
        }

    }

    public function delete_service(){
        if ($this->input->post('id')) {
            $this->load->library('lib_services');
            echo($this->lib_services->delete_services($this->input->post('id')));
        }
    }
   

    function logout(){
        redirect('logout');
    }
}
