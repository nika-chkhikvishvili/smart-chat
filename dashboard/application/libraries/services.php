<?php

/*
 * GET ISAD DESCRIPTION DATE
 * 
 */
 class services {
	 
     protected $CI;
	 
     public function __construct(){	 
		 $this->CI =& get_instance();
     }
     
     function update_services($id,$value)
	 {		
		$error = true;		
		 $this->CI->load->model('dashboard_model');
		 $this->CI->dashboard_model->update_services($id,$value);
		 $msg = array('status' => !$error, 'msg' => 'სერვისის დასახელება განახლებულია');
	    return  json_encode($msg);
     }
	 
	 function delete_services($id)
	 {
		$error = true;		
		$this->CI->load->model('dashboard_model');
		$this->CI->dashboard_model->delete_services($id);
		$msg = array('status' => !$error, 'msg' => 'სერვისის დასახელება წაშლილია');
	    return json_encode($msg);
	 }
     
 }
?>
