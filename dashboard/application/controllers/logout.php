<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული

class Logout extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
    }


    public function index(){
        
        $this->session->userdata('user');
        $this->session->userdata('roles');     
        # სესსიის დახურვა
        $this->session->sess_destroy();  
        # ავტორიზაციის გვერდზე გადასვლა
        redirect('welcome');

    }

}
