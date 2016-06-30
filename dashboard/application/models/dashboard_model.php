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
        $this->db->insert('information_object', $information_object);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return $information_object['information_object_rowid'];
        }

    }

    function update_institution($id, $value){
        $this->db->set('category_name', $value);
        $this->db->where('repo_categories_id', $id);
        $this->db->update('repo_categories');

    }

    function delete_institution($id){
        $this->db->delete('repo_categories', array('repo_categories_id' => $id));
        $this->db->delete('category_services', array('repo_categories_id' => $id));
    }
    // institution

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
        $this->db->where('category_services_id', $service_id);
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

    function update_services($id, $data, $information_object){
        unset($data['update']);
        $this->db->trans_begin();
        $this->db->update('category_services', $data, array('category_services_id' => $id));
        $this->db->insert('information_object', $information_object);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    function delete_services($id){
        $this->db->delete('category_services', array('category_services_id' => $id));
    }

    // services


    function add_login_his($add_login_his){
        $this->db->insert('login_his', $add_login_his);
        return $this->db->insert_id();

    }

}
