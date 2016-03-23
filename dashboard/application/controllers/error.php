<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {
	
	function __construct(){
			parent::__construct();
	}
	
	
			
	public function index(){
		echo "redirect to main!";
	}

	public function permitions_eccess(){
		
		$data['heading']= "Session data false!";
		$data['message']= "You Don't have permission access!";
		$this->load->view('error_404',$data);
	}
}
