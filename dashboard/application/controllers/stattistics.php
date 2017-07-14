<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class stattistics extends CI_Controller{

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }


    public function index(){
       $data['notify'] = ""; 
       $this->load->model('dashboard_model'); 
       $session_data = $this->session->userdata('user');
       $data['get_services'] = $this->dashboard_model->get_all_services();
       $data['get_persons'] = $this->dashboard_model->get_persons();
      
       $this->load->view('stattistics', $data);
    }
    
    
    function get_all_data()
    {
        $session_data = $this->session->userdata('user');
        $this->load->model('dashboard_model');       
        
        $service_id = false;
        $user_id = false;
        $start_date = false;
        $end_date = false;
       
        if(@$_POST['service_id']>=1 || @$_POST['user_id'] || @$_POST['start_date']!="" || @$_POST['end_date']!="")
        {
          $service_id = @$_POST['service_id'];
          $user_id = @$_POST['user_id'];
         
          
         $start_date = @date("Y-m-d", @strtotime(@$_POST['start_date']));
          
         $end_date = @date("Y-m-d", @strtotime(@$_POST['end_date'])); 
         
         if($start_date=='1970-01-01')
         {
             $start_date = false;
         }
         if($end_date=='1970-01-01')
         {
             $end_date = false;
         }
        }
       
       
        // არჩეულია სერვისით ფილტრი 
        if(@$_POST['service_id']>=1 && @$_POST['user_id']<1)
        {
           $byservice = $this->dashboard_model->get_count_byservices($start_date,$end_date,$_POST['service_id']);
            echo '<table class="table table-condensed">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody>
                
                <tr class="success">
                <td>საერთო ვიზიტორების რაოდენობა</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"><span class="badge"> 
                                   '.$byservice.'</span></td>
                </tr>
                ';
        }
        // არჩეულია user_id ფილტრი
        elseif (@$_POST['service_id']<1 && @$_POST['user_id']>=1){
            #///////////////
            # არჩეულია ოპერატორი
            $all_chats = $this->dashboard_model->statistic_byperson($_POST['user_id'],$service_id,$start_date,$end_date);
            $person_services = $this->dashboard_model->get_all_services();
            
            foreach($person_services as $keys => $values){                 
		  $person_services[$keys]['all_chats'] = $this->dashboard_model->statservices_by_person($_POST['user_id'],$values['category_service_id'],$start_date,$end_date); 
                }
          
            echo '<table class="table table-condensed">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody>
                
                <tr class="success">
                <td>ოპერატორის მიერ ჯამში გაწეული მომსახურება</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"><span class="badge"> 
                                   '.$all_chats.'</span></td>
                </tr>
                ';
                
                echo '<tr class="warning"><td>ოპერატორის მიერ ჯამში გაწეული მომსახურება საკურატორო სერვისების მიხედვით:</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"></td>
                </tr>';
                foreach($person_services as $services){

                  echo "<tr>
                        <td class='thick-line'></td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'>".$services['service_name_geo']."</td>
                        <td class='thick-line text-right'>".$services['all_chats']."</td>
                        </tr>";          
                }
                 // ცენზურის ფილტრით დაბლოკლი ვიზიტორების რაოდენობა
                 echo "<tr>
                        <td class='thick-line'>კონკრეტულ ოპერატორთან საუბრის დროს ცენზურის ფილტრის მიერ დაბლოკილი ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>0</td>
                        </tr>";
                 
                // ცენზურის ფილტრით დაბლოკლი ვიზიტორების რაოდენობა
                 $get_sql_banlist = $this->dashboard_model->get_count_banlist_admn($_POST['user_id']);
                 echo "<tr>
                        <td class='thick-line'>ოპერატორების მიერ დაბლოკილი ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>$get_sql_banlist</td>
                        </tr>";
            
            # არჩეულია ოპერატორი
            # /////////////////
        }
        // არჩეულია service_id da user_id ფილტრი
        elseif (@$_POST['service_id']>=1 && @$_POST['user_id']>=1){
          
            // check person service 
            $check_service_by_user = $this->dashboard_model->check_person_service($_POST['service_id'],$_POST['user_id']);
            if($check_service_by_user){
              // თუ ოპერატორის საკურატორო სივრცეშია სერვისი
              $get_service_name = $this->dashboard_model->get_service_name($_POST['service_id']);
              $count_person_chat_service = $this->dashboard_model->get_peron_services($start_date,$end_date,$_POST['user_id'],$_POST['service_id']);
           
              $service_name =  $get_service_name['service_name_geo'];
               echo'<table class="table table-condensed">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody>
                
                <tr class="info">
                <td>'.$service_name.'</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"><span class="badge">'.$count_person_chat_service.' 
                 </span></td>
                </tr>';
                
                echo '</tbody>
                </table>'; 
                
            } else
            {
                echo'<table class="table table-condensed">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody>
                
                <tr class="danger">
                <td>თქვენს მიერ არჩეული სერვისი არ შედის ოპერატორის საკურატორო სირვცეში</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"><span class="badge"> 
                 </span></td>
                </tr>';
                
                echo '</tbody>
                </table>'; 
            }
            
        }
        else {
        
        echo $start_date;
        echo "<br />";
        echo $end_date;
      
        $all_chats = $this->dashboard_model->get_statistic_allchats($service_id,$start_date,$end_date);
      
        echo '  <table class="table table-condensed">
                <thead>
                <tr>
                </tr>
                </thead>
                <tbody>
                
                <tr class="success">
                <td>საერთო ვიზიტორების რაოდენობა</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"><span class="badge"> 
                                   '.$all_chats.'</span></td>
                </tr>
                ';
                
                //საერთო ვიზიტორების რაოდენობა კატეგორიის მიხედვით :
                $sql_get_services = $this->dashboard_model->get_all_services(); 
                foreach($sql_get_services as $keys => $values){                 
		  $sql_get_services[$keys]['all_chats'] = $this->dashboard_model->get_by_service_id($values['category_service_id'],$start_date,$end_date); 
                }
               
                echo '<tr class="warning"><td>საერთო ვიზიტორების რაოდენობა კატეგორიის მიხედვით :</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"></td>
                </tr>';
                foreach($sql_get_services as $services){

                  echo "<tr>
                        <td class='thick-line'></td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'>".$services['service_name_geo']."</td>
                        <td class='thick-line text-right'>".$services['all_chats']."</td>
                        </tr>";          
                }
                // საერთო ვიზიტორების რაოდენობა ოპერატორების მიხედვით 
                echo '<tr class="warning"> <td>საერთო ვიზიტორების რაოდენობა ოპერატორების მიხედვით :</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"></td>
                </tr>';
                
                 $sql_get_operators = $this->dashboard_model->get_all_persons();
                 foreach($sql_get_operators as $p_keys => $p_values)
                 {                 
		  $sql_get_operators[$p_keys]['all_persons_chats'] = $this->dashboard_model->get_all_persons_id($p_values['person_id'],$start_date,$end_date); 
                 }
                 
                 foreach($sql_get_operators as $operators){

                  echo "<tr>
                        <td class='thick-line'></td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'>".$operators['first_name'] ."&nbsp;". $operators['last_name']."</td>
                        <td class='thick-line text-right'>".$operators['all_persons_chats']."</td>
                        </tr>";          
                }
                
                // დღეში საშუალო ვიზიტორების რაოდენობა კატეგორიის მიხედვით
                $get_sql_mindate = $this->dashboard_model->get_mindate_chat();
                
                $now = time(); // or your date as well
                $your_date = strtotime($get_sql_mindate['add_date']);
                $datediff = $now - $your_date;

                $interval_val =  floor($datediff / (60 * 60 * 24));
               
                $sul_sashualo = ($all_chats / $interval_val);
                
               
                
                echo "<tr>
                        <td class='thick-line'>დღეში საშუალო ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>".ceil($sul_sashualo)."</td>
                        </tr>";
                
                echo "<tr class='warning'>
                        <td class='thick-line'>დღეში საშუალო ვიზიტორების რაოდენობა კატეგორიის მიხედვით</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'></td>
                        </tr>";
               
                 foreach($sql_get_services as $services){
                       $sul_sashualo_byserv = ($services['all_chats'] / $interval_val);
                  echo "<tr>
                        <td class='thick-line'></td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'>".$services['service_name_geo']."</td>
                        <td class='thick-line text-right'>".ceil($sul_sashualo_byserv)."</td>
                        </tr>";          
                }
                
                 // საშუალოდ ოპერატორზე გადანაწილებული ვიზიტორთა რაოდენობა
                $sashualo_visitori_operatorze = ( $all_chats / count($sql_get_operators));
                 echo "<tr>
                        <td class='thick-line'>საშუალოდ ოპერატორზე გადანაწილებული ვიზიტორთა რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>".ceil($sashualo_visitori_operatorze)."</td>
                        </tr>";
                 // ცენზურის ფილტრით დაბლოკლი ვიზიტორების რაოდენობა
                 echo "<tr>
                        <td class='thick-line'>ცენზურის ფილტრით დაბლოკლი ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>0</td>
                        </tr>";
                 
                // ცენზურის ფილტრით დაბლოკლი ვიზიტორების რაოდენობა
                 $get_sql_banlist = $this->dashboard_model->get_count_banlist(1);
                 echo "<tr>
                        <td class='thick-line'>ოპერატორების მიერ დაბლოკილი ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>$get_sql_banlist</td>
                        </tr>";
                 
                 // ადმინისტრატორის მიერ დაბლოკილი ვიზიტორების რაოდენობა
                 $get_sql_banlist = $this->dashboard_model->get_count_banlist_admn(0);
                 echo "<tr>
                        <td class='thick-line'>ადმინისტრატორის მიერ დაბლოკილი ვიზიტორების რაოდენობა</td>
                        <td class='thick-line'></td>
                        <td class='thick-line text-center'></td>
                        <td class='thick-line text-right'>$get_sql_banlist</td>
                        </tr>";
               
                echo '</tbody>
                </table>';  
        
         
        }
        
       
        
       
    }
   

    function logout(){
        redirect('logout');
    }
}
