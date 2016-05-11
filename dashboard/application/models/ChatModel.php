<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChatModel extends CI_Model {


    public function __construct(){
         parent::__construct();
    }

	public function get_all_chats(){
	   $query = $this->db->query('SELECT c.`chat_id`, c.`online_user_id`, c.`repo_id`, c.`service_id`, c.`chat_status_id`,
				c.`chat_uniq_id`, c.`add_date`, ou.`first_name`, ou.`last_name`, ou.`phone`, ou.`personal_no`, ou.`email`,
				ou.`card_number`, ou.`reg_date`, r.`repository_id`, r.`name`, r.`other_name`, r.`abr`, r.`address`, r.`phone`,
                 ou.`birth_date`, r.`fax`, r.`email`, r.`reg_date`, r.`expire_date` FROM `chats` c, `online_users` ou,
                 `repositories` r WHERE c.online_user_id = ou.online_user_id AND c.repo_id = r.repository_id
                 ORDER BY c.`chat_id` DESC ');
	   
	   return $query->result();
	}

}

