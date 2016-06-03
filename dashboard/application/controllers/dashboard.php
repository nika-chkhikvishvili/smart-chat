<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Dashboard extends CI_Controller {
	
	function __construct(){
            parent::__construct();
            $session_data = $this->session->userdata('signin');
            $this->load->library('vsession');
            $this->vsession->check_person_sessions($session_data);
            $this->lang->load('ge');
	}
	
	
	public function index()
    {
            $session_data = $this->session->userdata('signin');
            $this->load->view('admin_main_dashboard');

		
	}	
		
public function add_institution()
{	
        $session_data = $this->session->userdata('signin');
        $this->load->library('form_validation');		
        $this->load->model('dashboard_model');
        $data['get_institutions'] = $this->dashboard_model->get_institutions($session_data['repositorypersons_repository_id']);		
        $data['institution_name'] = "";

        if($this->input->post('add_institution'))
        {			
        $this->form_validation->set_rules(
        'institution_name', 'უწყების დასახელება', 
        'required|min_length[5]',
        array(
                        'required' => $this->lang->line('required'),                
                        'valid_email' => $this->lang->line('valid_email')
             ));
        if ($this->form_validation->run() == TRUE)
        {
                $add_institution_data = array 
                (
                        'repository_id'=> $session_data['repositorypersons_repository_id'],
                        'category_name'=> $this->input->post('institution_name')
                );
                $information_object =  array(
                  'information_object_repo' => $session_data['repositorypersons_repository_id'],
                  'information_object_table' => 'repocategories',
                  'information_object_rowid' => '',
                  'information_object_person' => $session_data['persons_id'],
                  'information_object_event' => 'insert',
                  'information_object_date' => date("Y-m-d H:i:s"),
               );
                if($this->dashboard_model->add_institution($add_institution_data,$information_object)){
                        echo '<meta http-equiv="refresh" content="0">';
                }				
        }			

}

$this->load->view('add_institution',$data);

}
	
	public function update_institution()
	{
		if($this->input->post('id'))
		{
		$this->load->library('institutions');
		echo ($this->institutions->update_institution($this->input->post('id'),$this->input->post('val')));
		}
	}
	
	public function delete_institution()
	{		
		if($this->input->post('id'))
		{
		$this->load->library('institutions');
		echo ($this->institutions->delete_institution($this->input->post('id')));
		}
	}
// service layer
public function add_services()
{	
		
$session_data = $this->session->userdata('signin');
$this->load->library('form_validation');		
$this->load->model('dashboard_model');
$data['get_sql_services'] = $this->dashboard_model->get_services($session_data['repositorypersons_repository_id']);		
$data['sql_institutions'] = $this->dashboard_model->get_institutions($session_data['repositorypersons_repository_id']);	

if($this->input->post('add_service'))
{			
$this->form_validation->set_rules(
'service_name', 'სერვისის დასახელება', 
'required|min_length[5]',
array(
        'required' => $this->lang->line('required'),                
        'valid_email' => $this->lang->line('valid_email')
 ));

if ($this->form_validation->run() == TRUE)
{
        $add_institution_data = array 
        (
                'repo_categories_id'=> $this->input->post('repo_categories'),
                'service_name'=> $this->input->post('service_name'),
                'start_time'=> $this->input->post('start_time'),
                'end_time'=> $this->input->post('end_time')
        );
        if($this->dashboard_model->add_services($add_institution_data)){
                echo '<meta http-equiv="refresh" content="0">';
        }				
}			

}
    #echo  $this->uri->segment(3, 0);
    $this->load->view('add_services',$data);
}
	
public function update_service(){
    $session_data = $this->session->userdata('signin');
    $this->load->library('form_validation');		
    $this->load->model('dashboard_model');
   		
    $data['sql_institutions'] = $this->dashboard_model->get_institutions($session_data['repositorypersons_repository_id']);
    $data['service'] = $this->dashboard_model->get_service($session_data['repositorypersons_repository_id'],$this->uri->segment(3, 0));
    
    if($data['service']==0)
    {
       $data['error_code'] = "Error!"; 
       $data['error_title'] = "არასწორი პარამეტრების გადმოცემა!"; 
       $data['error_description'] = "სერვისის რედაქტირებისთვის მოხდა არასწორი პარამეტრების გადმოცემა, ვერცერთი ოპერაცია ვერ შესრულდება.თქვენ შეგიძლიათ მთავარ გვერდზე დაბრუნება!"; 
       $this->load->view('notification',$data);
    }
    else 
    {
       if($this->input->post('update'))
       {
            $information_object =  array(
            'information_object_repo' => $session_data['repositorypersons_repository_id'],
            'information_object_table' => 'categoryservices',
            'information_object_rowid' => $this->uri->segment(3, 0),
            'information_object_person' => $session_data['persons_id'],
            'information_object_event' => 'UPDATE',
            'information_object_date' => date("Y-m-d H:i:s"),
             );   
        if($this->dashboard_model->update_services($this->uri->segment(3, 0),$this->input->post(),$information_object)){
                echo '<meta http-equiv="refresh" content="0">';
        }
       }    
       $this->load->view('update_services',$data);
    }    
   
}
	
	public function delete_service()
	{
		if($this->input->post('id'))
		{
		$this->load->library('services');
		echo ($this->services->delete_services($this->input->post('id')));
		}
	}
	// persons layer
	public function persons()
	{
		$this->load->view('persons');
	}
	
	public function add_person()
	{
		$session_data = $this->session->userdata('signin');
		$this->load->library('form_validation');		
		$this->load->model('dashboard_model');
		$data['get_sql_services'] = $this->dashboard_model->get_services($session_data['repositorypersons_repository_id']);		
		if($this->input->post('add_person'))
		{			
			$this->form_validation->set_rules(
			'firstname', 'მომხმარებლის სახელი', 
			'required',
			array('required' => $this->lang->line('required')));
			
                        $this->form_validation->set_rules(
			'lastname', 'მომხმარებლის გვარი', 
			'required',
			array('required' => $this->lang->line('required')));	 
				 
				if ($this->form_validation->run() == TRUE)
				{
					$add_institution_data = array 
					(
						'repo_categories_id'=> $this->input->post('repo_categories'),
						'service_name'=> $this->input->post('service_name')
					);
					if($this->dashboard_model->add_services($add_institution_data)){
						echo '<meta http-equiv="refresh" content="0">';
					}				
				}			
			
		}
		$this->load->view('add_persons',$data);
	}
	
	function logout(){
		redirect('logout');
	}
}
