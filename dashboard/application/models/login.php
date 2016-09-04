<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
    }

    // მკლევრის მონაცმები სესიის დასაწყებად
    public function get_person_data($email, $pass)
    {
        $this->db->select('*');
        $this->db->from('persons');
        $this->db->where('email', $email);
        $this->db->where('person_password', $pass);
        $this->db->where('status_id', 0);
        $query = $this->db->get();
        return $query->row();
    }

    function add_login_history($add_login_his)
    {
        $add_login_his['php_session_id'] = $this->session->userdata['session_id'];
        $this->db->insert('xlog_login_history', $add_login_his);
        return $this->db->insert_id();
    }

    function add_token($token_data)
    {
        $this->db->insert('person_tokens', $token_data);
        $this->session->set_userdata('token', $token_data['token']);
    }


    function get_user_roles()
    {
        $this->db->select('*');
        $this->db->where('person_id', $this->session->userdata('user')->person_id);
        $this->db->from('person_roles');
        $this->db->join('zlib_roles', 'person_roles.role_id = zlib_roles.role_id', 'left');
        $query = $this->db->get();

        $user_roles = [];

        foreach ($query->result() as $row)
        {
            $role = new stdClass();
            $role->role_id = $row->role_id;
            $role->role_name = $row->role_name;
            $user_roles[$role->role_name] = $role;
            $user_roles[$role->role_id] = $role;
        }
        return $user_roles;
    }
}
