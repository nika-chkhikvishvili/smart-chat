<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {


    public function __construct()
        
	{
         parent::__construct();
    }
	
	// მკლევრის მონაცმები სესიის დასაწყებად
	public function get_institutions($repo_id)
	{	  
	   $this->db->select('*');
	   $this->db->from('repocategories');
	   $this->db->where('repository_id',$repo_id);	  
	   $query = $this->db->get();	   
	   return $query->result_array();
	  
	}
	// institution
	function add_institution($data){
		$this->db->insert('repocategories', $data); 
		return $this->db->insert_id();
	}
	
	function update_institution($id,$value)
	{
	    $this->db->set('category_name', $value);
		$this->db->where('repo_categories_id', $id);
		$this->db->update('repocategories'); 

	}
	
	function delete_institution($id)
	{
		$this->db->delete('repocategories', array('repo_categories_id' => $id)); 
	}
	// institution
	
	// services
	function add_services($data){
		$this->db->insert('categoryservices', $data); 
		return $this->db->insert_id();
	}
	
	function update_services($id,$value)
	{
	    $this->db->set('service_name', $value);
		$this->db->where('category_services_id', $id);
		$this->db->update('categoryservices'); 

	}
	
	function delete_services($id)
	{
		$this->db->delete('categoryservices', array('category_services_id' => $id)); 
	}
	// services
	
	public function get_services($repo_id){	   
	   $this->db->select('*');
	   $this->db->from('repocategories');
	   $this->db->where('repository_id',$repo_id);
	   $this->db->join('categoryservices', 'categoryservices.repo_categories_id = repocategories.repo_categories_id');
	   $query = $this->db->get();	
	   return $query->result_array();

	}
	function add_login_his($add_login_his)
	{		
		$this->db->insert('login_his', $add_login_his); 
		return $this->db->insert_id();
	
	}

}
?>
