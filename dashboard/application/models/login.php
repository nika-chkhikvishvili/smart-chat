<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Model {


    public function __construct()
        
	{
         parent::__construct();
    }

	
	// მკლევრის მონაცმები სესიის დასაწყებად
	public function GetPersonData($email,$pass)
	{	  
	   $this->db->select('*');
	   $this->db->from('persons');
	   $this->db->where('email',$email);
	   $this->db->where('password',$pass);
	   $this->db->join('repositorypersons', 'repositorypersons.repositorypersons_person_id = persons.persons_id','left');
	   #$this->db->join('repository', 'repository.repository_id	 = repositorypersons.repositorypersons_repository_id');
	   $query = $this->db->get();
	   
	   return $query->row_array();
	  
	}
	
	function add_login_his($add_login_his)
	{		
		$this->db->insert('login_his', $add_login_his); 
		return $this->db->insert_id();
	
	}

}
?>
