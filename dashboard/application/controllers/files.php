<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Files extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
        
        $this->load->model('dashboard_model');
        $session_data = $this->session->userdata('user');
        
        $config['upload_path']          = 'uploads/';
        $config['allowed_types'] = "gif|jpg|jpeg|png|iso|dmg|zip|rar|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt|exe|avi|mpeg|mp3|mp4|3gp";
        $config['max_size']             = 2048;        
       // $config['file_name']= 'file';
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
                
                $error = array('error' => $this->upload->display_errors());

        }
        else
        {
          $data = array('upload_data' => $this->upload->data()); 
        
          $insert_data = array(
              'files_repo_id' => $session_data->repo_id,
              'file_name' => $this->upload->data()['orig_name']
          );
          $this->dashboard_model->add_files($insert_data);
          echo '<meta http-equiv="refresh" content="3;'.$url.'">';
        }
        
        $data['sql_files'] = $this->dashboard_model->get_files($session_data->repo_id);
        $this->load->view('files',$data);
    }
    
   
   
}
