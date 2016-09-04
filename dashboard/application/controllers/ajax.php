<?php
class Ajax extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('signin');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
        $session_data = $this->session->userdata('signin');
        $this->load->view('admin_main_dashboard');
    }
}
