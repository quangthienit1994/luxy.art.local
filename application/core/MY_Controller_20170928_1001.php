<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $current_action = $this->router->fetch_method();
        $current_controller = $this->router->fetch_class();
        
        if ($this->session->userdata('login_state') == false && $current_controller != 'admin') {
            redirect("/admin");    // no session established, kick back to login page
        }

        $this->load->model('course_model');
        $this->load->model('class_model');
        $this->load->model('teacher_model');
    }

}