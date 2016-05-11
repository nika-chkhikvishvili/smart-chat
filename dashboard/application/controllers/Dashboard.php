<?php
defined('BASEPATH') OR exit('No direct script access allowed');
# string mail $this->lang->line('email')
# string Password $this->lang->line('password')
# დოკუმენტში გამოყენებული 
class Dashboard extends CI_Controller{

    var $data;

    function __construct(){
        parent::__construct();
        $session_data = $this->session->userdata('signin');
        $this->load->library('vsession');
        $this->vsession->check_person_sessions($session_data);
        $this->lang->load('ge');
        $this->data = array('title' => 'ჩეთის ადმინისტრატორი', 'content' => '');


        $this->data['admin_topbar'] = $this->load->view('components/admin_topbar', $this->data, true);

        $this->data['sidebar_menu'] = $this->load->view('components/admin_menu.php', $this->data, true);
        $this->data['main_left_slidebar'] = $this->load->view('main_left_slidebar', $this->data, true);

    }

    public function index(){
        $session_data = $this->session->userdata('signin');
        $this->load->model('chatModel');
        $this->data['chats'] = $this->chatModel->get_all_chats();
        $this->data['content'] = $this->load->view('admin_main_dashboard', $this->data, true);
        $this->load->view('main', $this->data);
    }

    public function add_institution(){
        $session_data = $this->session->userdata('signin');
        $this->load->library('form_validation');
        $this->load->model('dashboardModel');
        $data['get_institutions'] = $this->dashboardModel->get_institutions($session_data['repositorypersons_repository_id']);
        $data['institution_name'] = "";

        if ($this->input->post('add_institution')) {
            $this->form_validation->set_rules('institution_name', 'უწყების დასახელება', 'required|min_length[5]',
                array('required' => $this->lang->line('required'), 'valid_email' => $this->lang->line('valid_email'))
            );

            if ($this->form_validation->run() == TRUE) {
                $add_institution_data = array(
                    'repository_id' => $session_data['repositorypersons_repository_id'],
                    'category_name' => $this->input->post('institution_name')
                );
                if ($this->dashboardModel->add_institution($add_institution_data)) {
                    echo '<meta http-equiv="refresh" content="0">';
                }
            }
        }
        $this->data['content'] = $this->load->view('add_institution', $data, true);
        $this->load->view('main', $this->data);
    }

    public function update_institution(){
        if ($this->input->post('id')) {
            $this->load->library('institutions');
            echo($this->institutions->update_institution($this->input->post('id'), $this->input->post('val')));
        }
    }

    public function delete_institution(){
        if ($this->input->post('id')) {
            $this->load->library('institutions');
            echo($this->institutions->delete_institution($this->input->post('id')));
        }
    }

    // service layer
    public function add_services(){

        $session_data = $this->session->userdata('signin');
        $this->load->library('form_validation');
        $this->load->model('dashboardModel');
        $data['get_sql_services'] = $this->dashboardModel->get_services($session_data['repositorypersons_repository_id']);
        $data['sql_institutions'] = $this->dashboardModel->get_institutions($session_data['repositorypersons_repository_id']);

        if ($this->input->post('add_service')) {
            $this->form_validation->set_rules(
                'service_name', 'სერვისის დასახელება',
                'required|min_length[5]',
                array(
                    'required' => $this->lang->line('required'),
                    'valid_email' => $this->lang->line('valid_email')
                ));

            if ($this->form_validation->run() == TRUE) {
                $add_institution_data = array
                (
                    'repo_categories_id' => $this->input->post('repo_categories'),
                    'service_name' => $this->input->post('service_name')
                );
                if ($this->dashboardModel->add_services($add_institution_data)) {
                    echo '<meta http-equiv="refresh" content="0">';
                }
            }

        }
        $this->data['content'] = $this->load->view('add_services', $data, true);
        $this->load->view('main', $this->data);
    }

    public function update_service()
    {
        if ($this->input->post('id')) {
            $this->load->library('services');
            echo($this->services->update_services($this->input->post('id'), $this->input->post('val')));
        }
    }

    public function delete_service(){
        if ($this->input->post('id')) {
            $this->load->library('services');
            echo($this->services->delete_services($this->input->post('id')));
        }
    }

    // persons layer
    public function persons(){
        $this->data['title'] = 'მომხმარებლის დამატება';
        $this->data['content'] = $this->load->view('persons', null, true);
        $this->load->view('main', $this->data);
    }

    public function add_person(){
        $session_data = $this->session->userdata('signin');
        $this->load->library('form_validation');
        $this->load->model('dashboardModel');
        $data['get_sql_services'] = $this->dashboardModel->get_services($session_data['repositorypersons_repository_id']);
        if ($this->input->post('add_person')) {
            $this->form_validation->set_rules('firstname', 'მომხმარებლის სახელი', 'required', array('required' => $this->lang->line('required')));
            $this->form_validation->set_rules('lastname', 'მომხმარებლის გვარი', 'required', array('required' => $this->lang->line('required')));

            if ($this->form_validation->run() == TRUE) {
                //TODO ბაზაში დამატება პიროვნების
//					$add_institution_data = array(
//						'repo_categories_id'=> $this->input->post('repo_categories'),
//						'service_name'=> $this->input->post('service_name')
//					);
//					if($this->dashboardModel->add_services($add_institution_data)){
//						echo '<meta http-equiv="refresh" content="0">';
//					}
            }

        }
        $this->data['content'] = $this->load->view('add_persons', $data, true);
        $this->load->view('main', $this->data);
    }

    function logout(){
        redirect('logout');
    }
}
