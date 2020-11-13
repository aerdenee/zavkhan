<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scamp extends CI_Controller {
    public static $path = "scategory/";

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Systemowner_model', 'systemowner');
    }

    public function index() {
        if ($this->session->isLogin === TRUE) {
            $header['cssFile'] = array();
            $header['jsFile'] = array();
            $header['breadcrumb'] = array(array('title' => 'Хэрэглэгчийн тохиргоо'));
            $body = array();
            $body['modId'] = "";
            $header['menuList'] = $this->systemowner->menulist_model();
            $this->load->view('header', $header);
            $this->load->view('camp/index', $body);
            $this->load->view('footer');
        }
    }
}