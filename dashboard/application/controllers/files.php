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
            $known_fields = array('file_name', 'file_type', 'file_path', 'full_path', 'raw_name', 'orig_name',
                'client_name', 'file_ext', 'file_size', 'is_image', 'image_width', 'image_height', 'image_type',
                'image_size_str' );
            $insert_unused_data = array();
            $insert_data = array(
                'files_repo_id' => $session_data->repo_id,
                'file_name' => $this->upload->data()['orig_name']
            );

            foreach ($data['upload_data'] as $key => $value) {
                if (in_array($key, $known_fields)) {
                    $insert_data[$key] = $value;
                } else {
                    $insert_unused_data[$key] = $value;
                }
            }
            if (count($insert_unused_data) > 0)
            $insert_data['full'] = json_encode($insert_unused_data, JSON_UNESCAPED_UNICODE);

          $this->dashboard_model->add_files($insert_data);
          echo '<meta http-equiv="refresh" content="3">';
         
        }
        
        $data['sql_files'] = $this->dashboard_model->get_files($session_data->repo_id);
        $this->load->view('files',$data);
    }
    
    function del_file()
    {
        $del_file_id =  $_POST['id'];
        $this->load->model('dashboard_model');
        
        $get_file_path = $this->dashboard_model->get_one_file($del_file_id);
       
        $file = 'uploads/'.$get_file_path['file_name'];
        
        if (@!unlink($file))
        {
            $error = true;           
            $msg = array('status' => !$error, 'msg' => 'ფაილის წაშლა ვერ ხერხდება!');
            echo json_encode($msg);
        }
        else
        {
            $get_file_path = $this->dashboard_model->del_files($del_file_id);
            $error = true;           
            $msg = array('status' => !$error, 'msg' => 'ფაილი წაშლილია!');
            echo  json_encode($msg);
        }
       
        
    }
    
   
   
}
