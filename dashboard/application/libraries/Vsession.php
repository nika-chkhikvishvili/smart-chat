<?php

/*
 * GET ISAD DESCRIPTION DATE
 * 
 */

class Vsession {
     
     public function __construct(){	 
	 $this->sys =& get_instance();	 
     }
     
     public function check_person_sessions($session_data){		
		if(!$session_data['persons_id'])
		{
			redirect('../chat');
		}
		
     }
}