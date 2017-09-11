<?php

/**
 * Created by PhpStorm.
 * User: jedi
 * Date: 2017-09-11
 * Time: 11:51 PM
 */
class CheckSession extends CI_Controller {

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('user');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
    }

    public function index(){
        $session_data = $this->session->userdata('user');
        if ($session_data) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    }
}
