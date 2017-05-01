<?php

/*
 * GET ISAD DESCRIPTION DATE
 * 
 */
 class Vsession {

     public function __construct(){
         $this->sys =& get_instance();
     }

     public function check_person_sessions($session_data) {
		if(!is_object($session_data) || !property_exists($session_data, 'person_id') || !$session_data->person_id) {
			redirect('');
		}

     }
     
     public function is_login($session)
     {
         if($session)
         {
             redirect('dashboard');
         }
        else {
              
        }
     }

 }
