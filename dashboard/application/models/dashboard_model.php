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
    
   public function get_one_institutions($cat_id){
        $this->db->select('*');
        $this->db->from('repo_categories');
        $this->db->where('repo_category_id', $cat_id);
        $query = $this->db->get();
        return $query->row_array();

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
        $this->db->select('* from persons WHERE is_admin IS NULL');
    
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
   
   function delete_person($id)
   {
      $this->db->where('person_id', $id);
      $this->db->delete('persons');  
   }	
   // end of persons
    
    
    // message templates
    function add_message_template($data)
    {
       $this->db->insert('message_templates', $data);
       return $this->db->insert_id();
    }
    
    
    function update_message_template($id,$data)
    {
        unset($data['update']);
        $this->db->update('message_templates', $data, array('message_templates_id' => $id));
    }
    
    function get_message_templates()
    {
        $this->db->select('*');
        $this->db->from('message_templates');
        $this->db->join('category_services', 'category_services.category_service_id = message_templates.service_id','LEFT');
        $this->db->order_by("message_templates_id", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function get_one_message_templates($templates_id)
    {
        $this->db->select('*');
        $this->db->from('message_templates');
        $this->db->where('message_templates_id',$templates_id);
        $this->db->join('category_services', 'category_services.category_service_id = message_templates.service_id');        
        $query = $this->db->get();
        return $query->row_array();
    }
    
     function delete_message_templates($id){
        $this->db->delete('message_templates', array('message_templates_id' => $id));       
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
	
	function get_sys_control()
    {
        $this->db->select('*');
        $this->db->from('sys_control');           
        $query = $this->db->get();
        return $query->row_array();
    }
	
	function update_sys_control($id,$data)
    {
        $this->db->where('sys_control_id', $id);
        $this->db->update('sys_control', $data);
    }
    
    
    function update_answering($id,$data)
    {
        $this->db->where('auto_answering_id', $id);
        $this->db->update('auto_answering', $data);
    }
    
    //get ban list
    function get_banlist()
    {
        $this->db->select('*');
        $this->db->from('banlist');
        $this->db->where('status', 0);
        $this->db->join('persons', 'persons.person_id = banlist.person_id'); 
        $this->db->join('online_users', 'online_users.online_user_id = banlist.online_user_id'); 
        $this->db->order_by("ban_id", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function get_blocklist()
    {
        $this->db->select('*');
        $this->db->from('banlist');
        $this->db->where('status', 1);
        $this->db->join('persons', 'persons.person_id = banlist.person_id','LEFT'); 
        $this->db->join('online_users', 'online_users.online_user_id = banlist.online_user_id','LEFT'); 
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function get_one_banlist($chat_id)
    {
        $this->db->select('*');
        $this->db->from('banlist');
        $this->db->where('chat_id', $chat_id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    function confutation_banlist($chat_id)
    {
      $this->db->set('status', 2);  
      $this->db->where('chat_id', $chat_id);
      $this->db->update('banlist');  
    }
    
    function reconfirm_banlist($chat_id)
    {
      $this->db->set('status', 1);  
      $this->db->where('chat_id', $chat_id);
      $this->db->update('banlist');  
    }
    //
    
   function get_chat_history($chat_id)
   {
        $this->db->select('*');
        $this->db->from('chat_messages');
        $this->db->where('chat_id', $chat_id);
        $this->db->join('persons', 'persons.person_id = chat_messages.person_id','LEFT'); 
        $this->db->join('online_users', 'online_users.online_user_id = chat_messages.online_user_id','LEFT');
        $this->db->order_by("message_date", "asc");
        $query = $this->db->get();
        return $query->result_array();
   }
   
 // user profile
   
   function get_profile_services($user_id)
   {
      $this->db->select('*');
      $this->db->from('person_services');
      $this->db->where('person_id', $user_id);
      $this->db->join('category_services', 'category_services.category_service_id = person_services.service_id','LEFT');
      $query = $this->db->get();
      return $query->result_array();
   }
   
   function profile_person_data($user_id)
   {
      $this->db->select('*');
      $this->db->from('persons');
      $this->db->where('person_id', $user_id); 
      $query = $this->db->get();
      return $query->row_array();
   }
   
   function update_password($user_id,$password)
   {
     $this->db->set('person_password', $password);
     $this->db->where('person_id', $user_id);
     $this->db->update('persons');  
   }
   
   // files
   function add_files($data)
   {
     $this->db->insert('files', $data);
     return $this->db->insert_id();
   }
   
   function get_files($repo_id)
   {
      $this->db->select('*');
      $this->db->from('files');
      $this->db->where('files_repo_id', $repo_id); 
      $query = $this->db->get();
      return $query->result_array();
   }
   
   function get_one_file($file_id)
   {
      $this->db->select('*');
      $this->db->from('files');
      $this->db->where('files_id', $file_id); 
      $query = $this->db->get();
      return $query->row_array(); 
   }
   
   function del_files($files_id)
   {
      $this->db->where('files_id', $files_id);
      $this->db->delete('files');  
   }

   public function get_all_full_services(){
    	    $this->db->select('repositories.`repository_id`, repositories.name as repository_name, 
    	    repo_categories.`repo_category_id`, repo_categories.`category_name` , category_services.`category_service_id`, 
    	    category_services.`service_name_geo`');
            $this->db->from('repo_categories');
            $this->db->join('repositories', 'repositories.repository_id = repo_categories.repository_id');
            $this->db->join('category_services', 'category_services.repo_category_id = repo_categories.repo_category_id');
            $this->db->where('category_service_id > ', 0 );
            $query = $this->db->get();
            return $query->result_array();
	}
  // statistics      
   function get_statistic_allchats($service_id=false,$start_date=false,$end_date=false)
   {
      $this->db->select('*');
      $this->db->from('chats');
      
      if($service_id)
      {
        $this->db->where('service_id',$service_id);  
      }
      
      if($start_date == $end_date)
      {
          if($start_date && $end_date)
          {
              $this->db->like("add_date", $start_date);  
          }
      }
      else 
      {
       if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("add_date <=", $end_date);   
      }  
      }    
      
      
      $query = $this->db->get();
      return $query->num_rows();
   }

    function get_all_persons_by_repo_id($repo_id)
    {
        $this->db->select('*');
        $this->db->from('persons');
        $this->db->where('repo_id', $repo_id);
        $query = $this->db->get();
        return $query->result_array();
    }
   
   function get_all_persons()
   {
      $this->db->select("* from persons where is_admin IS NULL");
      $query = $this->db->get();
      return $query->result_array();
   }
   
   function get_all_persons_id($person_id,$start_date,$end_date)
   {
      $this->db->select('*');
      $this->db->from('chats'); 
      $this->db->join('chat_rooms', 'chat_rooms.chat_id = chats.chat_id','LEFT');
      
      if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("add_date <=", $end_date);   
      }
      
      $this->db->where('person_id', $person_id); 
      $query = $this->db->get();
      return $query->num_rows();
   }
   
   function get_all_services()
   {
      $this->db->select('*');
     // $this->db->where('repo_category_id',1);
      $this->db->from('category_services');
      $query = $this->db->get();
      return $query->result_array();
   }
   
   
   
   function get_by_service_id($service_id,$start_date,$end_date)
   {
      $this->db->select('*');
      $this->db->from('chats'); 
      if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("add_date <=", $end_date);   
      }
      $this->db->where('service_id', $service_id); 
      $query = $this->db->get();
      return $query->num_rows();
   }
   
   function get_statistic_waiting($chat_status_id)
   {
      $this->db->select('*');
      $this->db->from('chats'); 
      $this->db->where('chat_status_id', $chat_status_id); 
      $query = $this->db->get();
      return $query->num_rows();
   }
   
   function get_statistic_byarg($service_id, $persons_id, $by_date ,$chat_status_id)
   {
      $this->db->select('*');
      $this->db->from('chats');
      $this->db->join('chat_rooms', 'chat_rooms.chat_id = chats.chat_id');
      
      if($service_id>=1)
      {
        $this->db->where('service_id', $service_id); 
      }
      
      if($persons_id>=1)
      {
        $this->db->where('person_id', $by_date); 
      }
      
      $this->db->where('chat_status_id', $chat_status_id); 
      $query = $this->db->get();
      return $query->num_rows();
   }
   
   
   function get_mindate_chat()
   {
     $this->db->select('*');
      $this->db->from('chats'); 
       $this->db->limit("1");
      $this->db->order_by("add_date", "asc");
    //  $this->db->where('chat_status_id', $chat_status_id); 
      $query = $this->db->get();
      return $query->row_array();
   }
   
   function get_count_banlist($argument)
   {
      $this->db->select('*');
      $this->db->from('banlist'); 
      $this->db->where('person_id >=',$argument); 
      $query = $this->db->get();
      return $query->num_rows();
   }
   
   function get_count_banlist_admn($argument)
   {
      $this->db->select('*');
      $this->db->from('banlist'); 
      $this->db->where('person_id =',$argument); 
      $query = $this->db->get();
      return $query->num_rows();
   }
   
   
   
   # start 
   # სტატისტიკა ოპერატორის მიხედვით
   
   function statistic_byperson($person_id,$service_id=false,$start_date=false,$end_date=false)
   {
      $this->db->select('*');
      $this->db->from('chats');
      $this->db->join('chat_rooms', 'chat_rooms.chat_id = chats.chat_id');
      if($service_id)
      {
        $this->db->where('service_id',$service_id);  
      }
      
      if($start_date == $end_date)
      {
          if($start_date && $end_date)
          {
              $this->db->like("add_date", $start_date);  
          }
      }
      else 
      {
       if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("add_date <=", $end_date);   
      }  
      }    
      
      $this->db->where('person_id',$person_id);
      $query = $this->db->get();
      return $query->num_rows();
   }
   
    function stat_services_byperson($person_id)
    {
      $this->db->select('*');
      $this->db->from('person_services');
      $this->db->join('category_services', 'category_services.category_service_id = person_services.service_id');
      $this->db->where('person_id',$person_id);
      $query = $this->db->get();
      return $query->result_array();
    }
    
    function statservices_by_person($person_id,$service_id,$start_date,$end_date)
   {
      $this->db->select('*');
      $this->db->from('chats');
      $this->db->join('chat_rooms', 'chat_rooms.chat_id = chats.chat_id');
      if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("add_date <=", $end_date);   
      }
      $this->db->where('person_id', $person_id); 
      $this->db->where('service_id', $service_id); 
      $query = $this->db->get();
      return $query->num_rows();
   }
   # end of 
   # სტატისტიკა ოპერატორის მიხედვით
   # 
   # 
   # 
   # 
   # start 
   # სერვისი და ოპერატორი
   function check_person_service($service_id,$person_id)
   {
      $this->db->select('*');
      $this->db->from('person_services');
      $this->db->where('person_id', $person_id); 
      $this->db->where('service_id', $service_id); 
      $query = $this->db->get();
      return $query->num_rows();
   }
   
   function get_service_name($service_id)
   {
      $this->db->select('*');
      $this->db->from('category_services');  
      $this->db->where('category_service_id', $service_id); 
      $query = $this->db->get();
      return $query->row_array();
   }
   
   function get_peron_services($start_date,$end_date,$person_id,$service_id){
      $this->db->select('*');
      $this->db->from('chats');
      $this->db->join('chat_rooms', 'chat_rooms.chat_id = chats.chat_id');
      if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("add_date <=", $end_date);   
      }
      $this->db->where('person_id', $person_id); 
      $this->db->where('service_id', $service_id); 
      $query = $this->db->get();
      return $query->num_rows();
   }
    # 
   # end of  
   # სერვისი და ოპერატორი
   # 
   # start 
   # სერვისი
   
 function get_count_byservices($start_date,$end_date,$service_id){
      $this->db->select('*');
      $this->db->from('chats');
     
      if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("add_date <=", $end_date);   
      }      
      $this->db->where('service_id', $service_id); 
      $query = $this->db->get();
      return $query->num_rows();
   }

   # 
   # end of  
   # სერვისი
    // chat history 
  function get_all_history($service_id = false,$first_name = false,$last_name = false, $operator = false,  $start_date = false,$end_date = false,$start)
   {
      $this->db->select('*');
      $this->db->from('chats'); 
      $this->db->join('chat_rooms', 'chat_rooms.chat_id = chats.chat_id');
      $this->db->join('online_users', 'online_users.online_user_id = chats.online_user_id');
      $this->db->join('category_services', 'category_services.category_service_id = chats.service_id');     
      $this->db->join('persons', 'persons.person_id = chat_rooms.person_id');
	  
	  if($first_name)
      {        
        $this->db->where("online_users_name", $first_name);   
      }
	  
	  if($last_name)
      {        
        $this->db->where("online_users_lastname", $last_name);   
      }
	  
	  if($service_id)
      {        
        $this->db->where("category_service_id", $service_id);   
      }
      
	  if($operator)
      {        
        $this->db->where("persons.person_id", $operator);   
      }
	  
      if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("person_id", $end_date);   
      }
	  
	  
      $where = "chat_rooms.person_id is  NOT NULL"; 
      $this->db->where($where);
      $this->db->where('chat_status_id','3');
      $this->db->order_by("add_date", "desc");
      $this->db->limit('25', $start);
      $query = $this->db->get();
      return $query->result_array();
   }
   
   function count_history($service_id,$start_date,$end_date){
    $this->db->select('*');
      $this->db->from('chats'); 
      $this->db->join('chat_rooms', 'chat_rooms.chat_id = chats.chat_id');
      $this->db->join('online_users', 'online_users.online_user_id = chats.online_user_id');
      $this->db->join('category_services', 'category_services.category_service_id = chats.service_id');     
      $this->db->join('persons', 'persons.person_id = chat_rooms.person_id');
      
      if($start_date)
      {
        $start_date = $start_date." 00:00:00"  ;
        $this->db->where("add_date >=", $start_date);   
      }
      
      if($end_date)
      {
        $end_date = $end_date." 00:00:00";    
        $this->db->where("person_id", $end_date);   
      }
      $where = "chat_rooms.person_id is  NOT NULL"; 
      $this->db->where($where);
      $this->db->where('chat_status_id','3');
    
      $query = $this->db->get();
      return $query->result_array();
   }
   
   
    function view_chat_history($chat_id)
   {
        $this->db->select('*');
        $this->db->from('chat_messages');
        $this->db->where('chat_id', $chat_id);
        $this->db->join('persons', 'persons.person_id = chat_messages.person_id','LEFT'); 
        $this->db->join('online_users', 'online_users.online_user_id = chat_messages.online_user_id','LEFT');
        $this->db->order_by("message_date", "asc");
        $query = $this->db->get();
        return $query->result_array();
     
   }
   
}
