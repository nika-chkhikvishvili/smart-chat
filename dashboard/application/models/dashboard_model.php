<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dashboard_model extends CI_Model{


    public function __construct(){
        parent::__construct();
    }

// institution
    public function get_institutions($repo_id){
        $this->db->select('*');
        $this->db->from('repo_categories');
        $this->db->where('repository_id', $repo_id);
        $query = $this->db->get();
        return $query->result_array();

    }

    function add_institution($data, $information_object){

        $this->db->trans_begin();
        $this->db->insert('repo_categories', $data);
        $information_object['information_object_rowid'] = $this->db->insert_id();
        //$this->db->insert('information_object', $information_object);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return $information_object['information_object_rowid'];
        }

    }

    function update_institution($id, $value){
        $this->db->set('category_name', $value);
        $this->db->where('repo_category_id', $id);
        $this->db->update('repo_categories');

    }

    function delete_institution($id){
        $this->db->delete('repo_categories', array('repo_category_id' => $id));
        $this->db->delete('category_services', array('repo_category_id' => $id));
    }
// end of  institution

    // services
    public function get_services($repo_id){
        $this->db->select('*');
        $this->db->from('repo_categories');
        $this->db->where('repository_id', $repo_id);
        $this->db->join('category_services', 'category_services.repo_category_id = repo_categories.repo_category_id');
        $query = $this->db->get();
        return $query->result_array();

    }

    public function get_service($repo_id, $service_id){
        $this->db->select('*');
        $this->db->from('category_services');
        # $this->db->where('repo_categories_id',$repo_id);
        $this->db->where('category_service_id', $service_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }

    }

    function add_services($data){
        $this->db->insert('category_services', $data);
        return $this->db->insert_id();
    }

    function update_services($id, $data){
        unset($data['update']);
        $this->db->trans_begin();
        $this->db->update('category_services', $data, array('category_service_id' => $id));
       

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    function delete_services($id){
        $this->db->delete('category_services', array('category_service_id' => $id));
    }
    
    // services


    function add_login_his($add_login_his){
        $this->db->insert('login_his', $add_login_his);
        return $this->db->insert_id();

    }
	
	function get_all_zlib_roles()
	{
	$this->db->select('*');
        $this->db->from('zlib_roles');      
        $query = $this->db->get();
        return $query->result_array();
	}
	
    // persons    
    public function get_persons(){
        $this->db->select('*');
        $this->db->from('persons');
        #$this->db->where('is_admin', 0);
        $query = $this->db->get();
        return $query->result_array();

    }
    
    function get_one_preson($person_id)
    {
        $this->db->select('*');
        $this->db->from('persons');       
        $this->db->where('person_id', $person_id);
        $query = $this->db->get();
        return $query->row_array();
        
    }
    
    function get_one_preson_role($person_id)
    {
        $this->db->select('*');
        $this->db->from('person_roles');       
        $this->db->where('person_id', $person_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function get_one_preson_services($person_id)
    {
        $this->db->select('*');
        $this->db->from('person_services');       
        $this->db->where('person_id', $person_id);
        $query = $this->db->get();
        return $query->result_array();
    }
	
	function add_person($person_data){
        $this->db->insert('persons', $person_data);
        return $this->db->insert_id();

    }
	
	function add_person_roles($person_roles){
        $this->db->insert('person_roles', $person_roles);
        return $this->db->insert_id();

    }
	
	function add_person_service($person_service){
        $this->db->insert('person_services', $person_service);
        return $this->db->insert_id();

    }
    
    function update_person($id,$data)
    {
        $this->db->where('person_id', $id);
        $this->db->update('persons', $data);
    }
    
    function delete_person_role($id)
    {
        $this->db->where('person_id', $id);
        $this->db->delete('person_roles'); 
    }
    
    function delete_person_service($id)
    {
        $this->db->where('person_id', $id);
        $this->db->delete('person_services'); 
    }
   // end of persons
    
    
    // message templates
    function add_message_template($data)
    {
       $this->db->insert('message_templates', $data);
       return $this->db->insert_id();
    }
    
    function get_message_templates()
    {
        $this->db->select('*');
        $this->db->from('message_templates');
        $this->db->join('category_services', 'category_services.category_service_id = message_templates.service_id');        
        $query = $this->db->get();
        return $query->result_array();
    }
    // end of message templates
    
    // update answering
    
    function get_answering()
    {
        $this->db->select('*');
        $this->db->from('auto_answering');           
        $query = $this->db->get();
        return $query->row_array();
    }
    
    
    function update_answering($id,$data)
    {
        $this->db->where('auto_answering_id', $id);
        $this->db->update('auto_answering', $data);
    }
}
