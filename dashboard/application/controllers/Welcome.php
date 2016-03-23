<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Welcome extends CI_Controller {
	
	function __construct(){
			 parent::__construct();
			$this->lang->load('ge');
	}
	
	
	public function index()
	{
		$this->load->library('form_validation');		
		// ვამოწმებთ მომხმარებლის ელ-ფოსტას
		$this->form_validation->set_rules(
        'post_mail', $this->lang->line('email'), 
		'required|min_length[5]|valid_email',
        array(
                'required' => $this->lang->line('required'),                
                'valid_email' => $this->lang->line('valid_email')
             ));
		// ვამოწმებთ მომხმარებლის პაროლს
		$this->form_validation->set_rules(
        'post_pass', $this->lang->line('password'), 
		'required|min_length[5]',
        array(
                'required' => $this->lang->line('required'),
                'min_length' => $this->lang->line('min_length')              
             ));	 
			 
	    if ($this->form_validation->run() == FALSE)
		{
				$this->load->view('signin');
		}
		else
		{
		 $this->load->model('login');					
		 $get_user_data = $this->login->GetPersonData($this->input->post('post_mail'),
														 $this->input->post('post_pass'));
					
		if($get_user_data)
		{		$this->load->helper('date');			
				
				$add_login_his = array 
				(					
					"his_person_id" => $get_user_data['persons_id'],
					"login_his_date" => date("Y-m-d"), 
					"login_his_time" => date("Y-m-d H:i:s") 
				);
				$log_history = $this->login->add_login_his($add_login_his);
				
				if($log_history >=1)
				{
					# ადმინისტრატორი
					if($get_user_data['isadmin']==1)
					{
						$this->session->set_userdata('signin', $get_user_data);
						redirect('/dashboard');
					} 
					# ოპერატორი
					else 
					{
						$this->session->set_userdata('signin', $get_user_data);
						redirect('/dashboard');
					}
				}
				else 
				{
					echo "Login History Problems!";
				}
				
		}
		else 
		{
			 redirect('');	
		}					
					
		}
		
	}

	
	public function resetpass()
	{
		$this->load->library('form_validation');
		
		// ვამოწმებთ მომხმარებლის ელ-ფოსტას
		$this->form_validation->set_rules(
        'post_mail', $this->lang->line('email'), 
		'required|min_length[5]|valid_email',
        array(
                'required' => $this->lang->line('required'),                
                'valid_email' => $this->lang->line('valid_email')
             ));
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('resetpass');
		}
	 
		
	}
}
?>
