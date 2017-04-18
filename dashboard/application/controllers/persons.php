<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persons extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
        $this->load->model('dashboard_model');
        $data['persons'] = $this->dashboard_model->get_persons();		
        $this->load->view('persons',$data);
    }

    public function add_person(){
        $session_data = $this->session->userdata('user');        
        $this->load->library('form_validation');
        $this->load->model('dashboard_model');
        $data['get_sql_services'] = $this->dashboard_model->get_services($session_data->repo_id);
	$data['zlib_roles'] = $this->dashboard_model->get_all_zlib_roles();
        if ($this->input->post('add_person')) {
            $this->form_validation->set_rules(
                'first_name', 'მომხმარებლის სახელი',
                'required',
                array('required' => $this->lang->line('required')));

            $this->form_validation->set_rules(
                'last_name', 'მომხმარებლის გვარი',
                'required',
                array('required' => $this->lang->line('required')));
            
            $this->form_validation->set_rules(
                'nickname', 'მეტსახელი',
                'required',
                array('required' => $this->lang->line('required')));
            
                $this->form_validation->set_rules(
                'person_mail', 'ელ-ფოსტა',
                'required|valid_email',
                array('required' => $this->lang->line('required')));

                if ($this->form_validation->run() == TRUE) {
                if($this->input->post('birthday')!=""){	
                $birth_date = explode("-", $this->input->post('birthday'));
                $birth_date2 = $birth_date[2]."-".$birth_date[1]."-".$birth_date[0];
                }
                else {
                        $birth_date2 ="";
                }
                $password = self::randomPassword(6,1,"lower_case,upper_case,numbers");
                $pass = $password[0];
                $person_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'nickname' => $this->input->post('nickname'),
                'email' => $this->input->post('person_mail'),
                'person_password' => md5($pass),    
                'birth_date' => $birth_date2,
                'repo_id' => $session_data->repo_id
                );
                $this->load->library('email');
                $this->email->from('smartchat@cloud.gov.ge', 'SMARTCHAT');
                $this->email->to($this->input->post('person_mail'));

                $this->email->subject('SmartChat Account Activation');
                $this->email->message('თქვენი ანგარიში გააქტიურებულია. მისამართი https://dashboard-smartchat.cloud.gov.ge 
		' მომხმარებელი : $this->input->post("person_mail") 
		პაროლი : '.$pass.' ');
                $this->email->send();
                 echo $this->email->print_debugger();
                
                $person_id = $this->dashboard_model->add_person($person_data);

                foreach ($data['zlib_roles'] as $get_roles){
                        if($this->input->post($get_roles['role_id'])){
                                $person_roles = array (
                                'person_id' => $person_id,
                                'role_id' => $get_roles['role_id']
                                );
                    $this->dashboard_model->add_person_roles($person_roles);
                        }	
                }

                foreach ($data['get_sql_services'] as $get_service){
                        if($this->input->post($get_service['category_service_id'])){
                                $person_service = array (
                                'person_id' => $person_id,
                                'service_id' => $get_service['category_service_id']
                                );
                    $this->dashboard_model->add_person_service($person_service);
                        }	
                }

            }

        }
        $this->load->view('add_persons', $data);
    }
    
    function edit_person()
    {
     $session_data = $this->session->userdata('user');        
     $this->load->library('form_validation');
     $this->load->model('dashboard_model');
     $data['get_sql_services'] = $this->dashboard_model->get_services($session_data->repo_id);
     $data['zlib_roles'] = $this->dashboard_model->get_all_zlib_roles();
     
     $edit_person_id =  $this->uri->segment(3);
     $data['edit_person'] = $this->dashboard_model->get_one_preson($edit_person_id);
     $data['edit_person_role'] = $this->dashboard_model->get_one_preson_role($edit_person_id);
     $data['edit_person_services'] = $this->dashboard_model->get_one_preson_services($edit_person_id);
     // განახლება
     if($this->input->post('update_person'))
     {
        if($this->input->post('birthday')!=""){	
               $birth_date = explode("-", $this->input->post('birthday'));
               $birth_date2 = $birth_date[2]."-".$birth_date[1]."-".$birth_date[0];
               }
               else {
                       $birth_date2 ="";
               }
        $person_data = array(
        'first_name' => $this->input->post('first_name'),
        'last_name' => $this->input->post('last_name'),
        'nickname' => $this->input->post('nickname'),
        'phone' => $this->input->post('phone'),
        'email' => $this->input->post('person_mail'),        
        'birth_date' => $birth_date2        
        );
        $this->dashboard_model->update_person($edit_person_id,$person_data);
         $this->dashboard_model->delete_person_role($edit_person_id,$person_data);
        $this->dashboard_model->delete_person_service($edit_person_id); 
        // roles update
        if($this->input->post('roles')){
           
        foreach ($_POST['roles'] as $role_items)
        {
            
            $roles_data = array (
             'person_id' => $edit_person_id,
             'role_id' => $role_items  
            );
        $this->dashboard_model->add_person_roles($roles_data);
        }  
        }
      if($this->input->post('service')){       
       
       
       foreach ($_POST['service'] as $service_items)
        {            
             $service_data = array (
             'person_id' => $edit_person_id,
             'service_id' => $service_items  
            );
        $this->dashboard_model->add_person_service($service_data);
        }
      }  
        
     }    
     $this->load->view('edit_persons', $data);
    }
    
public function randomPassword($length,$count, $characters) {
 
 
// define variables used within the function    
    $symbols = array();
    $passwords = array();
    $used_symbols = '';
    $pass = '';
 
// an array of different character types    
    $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
    $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $symbols["numbers"] = '1234567890';
    $symbols["special_symbols"] = '!?~@#-_+<>[]{}';
 
    @$characters = split(",",$characters); // get characters types to be used for the passsword
    foreach ($characters as $key=>$value) {
        $used_symbols .= $symbols[$value]; // build a string with all characters
    }
    $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1
     
    for ($p = 0; $p < $count; $p++) {
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $symbols_length); // get a random character from the string with all characters
            $pass .= $used_symbols[$n]; // add the character to the password string
        }
        $passwords[] = $pass;
    }
     
    return $passwords; // return the generated password
}
    

    function logout(){
        redirect('logout');
    }
}
