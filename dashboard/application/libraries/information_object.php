<?php

/*
 * CRUD log INFORMATION OBJECT TABLE
 * io_lib information_object_librarie
 */
 class io_lib {
	 
     protected $CI;
	 
     public function __construct(){	 
		 $this->CI =& get_instance();
     }
     
function insert($id,$value)
{		
    $information_object =  array(
      'information_object_repo' => $session_data['repositorypersons_repository_id'],
      'information_object_table' => 'repocategories',
      'information_object_rowid' => '',
      'information_object_person' => $session_data['persons_id'],
      'information_object_event' => 'insert',
      'information_object_date' => date("Y-m-d H:i:s"),
   );
}

 }

