<?php

/*
 * GET ISAD DESCRIPTION DATE
 * 
 */
class institutions {
	 
     protected $CI;
	 
     public function __construct(){	 
		 $this->CI =& get_instance();
     }
     
     function update_institution($id,$value)
	 {		
		$error = true;		
		 $this->CI->load->model('dashboard_model');
		 $this->CI->dashboard_model->update_institution($id,$value);
		 $msg = array('status' => !$error, 'msg' => 'ინსტიტუტის დასახელება განახლდა წარმატებით');
	    return  json_encode($msg);
     }
	 
	 function delete_institution($id)
	 {
		$error = true;		
		$this->CI->load->model('dashboard_model');
		$this->CI->dashboard_model->delete_institution($id);
		$msg = array('status' => !$error, 'msg' => 'დასახელების წაშლა წარმატებით შესრულდა');
	    return json_encode($msg);
	 }
}

