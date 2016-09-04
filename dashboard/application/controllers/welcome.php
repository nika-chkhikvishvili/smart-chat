<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Welcome extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->lang->load('ge');
    }


    public function index()
    {

        $this->load->library('form_validation');
        // ვამოწმებთ მომხმარებლის ელ-ფოსტას
        $this->form_validation->set_rules(
            'post_mail', $this->lang->line('email'),
            'required|min_length[5]|valid_email',
            array(
                'required'    => $this->lang->line('required'),
                'valid_email' => $this->lang->line('valid_email')
            ));
        // ვამოწმებთ მომხმარებლის პაროლს
        $this->form_validation->set_rules(
            'post_pass', $this->lang->line('password'),
            'required|min_length[5]',
            array(
                'required'   => $this->lang->line('required'),
                'min_length' => $this->lang->line('min_length')
            ));

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('signin');
        } else {
            $this->load->model('login');
            $get_user_data = $this->login->get_person_data($this->input->post('post_mail'),
//                sha1($this->input->post('post_pass')));
                ($this->input->post('post_pass')));

            if ($get_user_data) {
                $this->load->helper('date');

                $add_login_his = array (
                    "history_person_id"  => $get_user_data->person_id,
                    "login_his_date" => date("Y-m-d"),
                    "login_his_time" => date("Y-m-d H:i:s")
                );
                $log_history = $this->login->add_login_history($add_login_his);

                 $token_data = array (
                    "token"  => $this->session->userdata['session_id'],
                    "person_id"  => $get_user_data->person_id,
                    "add_date" => date("Y-m-d H:i:s")
                );

                $this->login->add_token($token_data);

                if ($log_history >= 1) {
                    $this->session->set_userdata('user', $get_user_data);
                    $this->session->set_userdata('roles',  $this->login->get_user_roles());
                    redirect('/dashboard');
                } else {
                    echo "Login History Problems!";
                }
            } else {
                redirect('');
            }

        }

    }


    public function resetpass()
    {
        $this->load->library('form_validation');

        // ვამოწმებთ მომხმარებლის ელ-ფოსტას
        $this->form_validation->set_rules(
            'post_mail', $this->lang->line('email'),
            'required|min_length[5]|valid_email',
            array(
                'required'    => $this->lang->line('required'),
                'valid_email' => $this->lang->line('valid_email')
            ));
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('resetpass');
        }

    }
}
