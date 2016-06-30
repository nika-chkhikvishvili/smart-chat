<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# დოკუმენტში გამოყენებული 
class Login extends CI_Controller {
	
	function __construct(){
		 parent::__construct();
		$this->lang->load('ge');
	}
	
	public function index()
	{
		$this->load->library('form_validation');
		echo $this->lang->line('password'); 
		echo "asdasd";
		// ვამოწმებთ მომხმარებლის ელ-ფოსტას
		$this->form_validation->set_rules(
        'post_mail', 'ელ-ფოსტა', 
		'required|min_length[5]|valid_email',       
	   array(
                'required'      => 'მიუთითეთ %s.',
                'min_length'     => 'ელ-ფოსტის მისამართი უნდა შედგებოდეს მინიმუმ 5 სიმბოლოსგან.',
                'valid_email'     => 'ელ-ფოსტის მისამართის ფორმატი არ შეესაბამება ელექტრონული მეილის სტანდარტს'
             ));
		// ვამოწმებთ მომხმარებლის პაროლს
		$this->form_validation->set_rules(
        'post_pass', 'პაროლი', 
		'required|min_length[3]',
        array(
                'required'      => 'მიუთითეთ %s.',
                'min_length'     => 'პაროლი  უნდა შედგებოდეს მინიმუმ 8 სიმბოლოსგან.'               
             ));	 
			 
			  if ($this->form_validation->run() == FALSE)
                {
						#თუ მომხმარებლის ელ-ფოსტა ან პაროლი არასწორია რჩება იგივე გვერდზე.
                        $this->load->view('login');
                }
                else
                {
						$this->load->model('login');
						#$this->login->check_user($this->input->post('post_mail'),$this->input->post('post_pass'));
						# თუ ავტორიზაცია წარმატებით დასრულდა 
                        
                }
		
	}
}
