<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Url_handler {

    var $ci;
    function __construct() {
        $this->ci = & get_instance();
    }
    /**
     * save url to cookie
     */
    function urlhistory_save() {
        $this->ci = & get_instance();
        $this->ci->load->library('session');

        $array = array(
            'oldUrl' => $this->ci->session->userdata('newurl'),
            'newurl' => $this->ci->uri->uri_string()
        );
        $this->ci->session->set_userdata($array);
    }
    /**
     * get old url from cookie
     */
    function urlhistory_get() {
        $CI = & get_instance();
        $CI->load->library('session');

        return $CI->session->userdata('oldurl');
    }
}
