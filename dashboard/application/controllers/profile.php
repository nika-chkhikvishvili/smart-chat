<?php

class Profile extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
        
    }
     public function index(){
        $data['notify']=0;
        $session_data = $this->session->userdata('user');
        
        $this->load->model('dashboard_model');
        $data['profile_data'] = $this->dashboard_model->get_profile_services($session_data->person_id);
        $data['profile_person_data'] = $this->dashboard_model->profile_person_data($session_data->person_id);

          $old_password = sha1($this->input->post('old_password'));
          $new_password1 = $this->input->post('new_password1');
          $new_password2 = $this->input->post('new_password2');
          if($this->input->post('submit'))
          {
          if($old_password!=$session_data->person_password)
          {
             $data['notify']=1;
             $data['error_message'] = "ძველი პაროლი არასწორია!";
             echo '<meta http-equiv="refresh" content="2">';
          }
          elseif($new_password1!=$new_password2)
          {
             $data['notify']=1;
             $data['error_message'] = "ახალი პაროლები არ ემთხვევა ერთმანეთს!";
             echo '<meta http-equiv="refresh" content="2">';
          }
          else
          {
            $this->dashboard_model->update_password($session_data->person_id,sha1($new_password1));
             $data['notify']=1;
             $data['error_message'] = "პაროლი განახლებულია... გთხოვთ ახალი პაროლით გაიაროთ ავტორიზაცია";
             $url = base_url()."logout";
             echo '<meta http-equiv="refresh" content="3;'.$url.'">';
             
          }    
          }
          
        $this->load->view('profile',$data);
    }

    function change_password()
    {
          $session_data = $this->session->userdata('user');
         
       
    }
   
}
