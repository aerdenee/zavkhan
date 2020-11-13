<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sorganization extends CI_Controller {

    public static $path = "sorganization/";

    function __construct() {
        parent::__construct();
        $this->load->model('Sorganization_model', 'organization');
        $this->load->model('Systemowner_model', 'systemowner');
    }

    public function index($modId = '') {
        if ($this->session->isLogin === TRUE) {
            $header['cssFile'] = array();
            $header['jsFile'] = array();
            $header['breadcrumb'] = array(array('title' => 'Хэрэглэгчийн тохиргоо'));
            $body = array();
            $body['modId'] = $modId;
            $header['menuList'] = $this->systemowner->menulist_model();
            $body['lists'] = $this->organization->lists_model($modId);
            $this->load->view('header', $header);
            $this->load->view('organization/index', $body);
            $this->load->view('footer');
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {
        echo json_encode($this->organization->lists_model());
    }

    public function add($modId = '') {
        if ($this->session->isLogin === TRUE) {
            $header['cssFile'] = array();
            $header['jsFile'] = array();
            $header['breadcrumb'] = array(array('title' => 'Байгууллага нэмэх'));
            $body = array();
            $body['modId'] = $modId;
            $header['menuList'] = $this->systemowner->menulist_model();
            $body['row'] = $this->organization->addFormData_model();
            $body['mode'] = 'insert';
            $body['title'] = 'Шинэ ангилал нэмэх';
            $this->load->view('header', $header);
            $this->load->view('organization/form', $body);
            $this->load->view('footer');
        } else {
            redirect(MY_ADMIN);
        }
    }
    
    public function edit($modId = '', $id = '') {
        if ($this->session->isLogin === TRUE) {
            $header['cssFile'] = array();
            $header['jsFile'] = array();
            $header['breadcrumb'] = array(array('title' => 'Ангилал засах'));
            $body = array();
            $body['modId'] = $modId;
            $header['menuList'] = $this->systemowner->menulist_model();
            $body['row'] = $this->organization->editFormData_model($id);
            $body['mode'] = 'update';
            $body['title'] = 'Ангилал засах';
            $this->load->view('header', $header);
            $this->load->view('organization/form', $body);
            $this->load->view('footer');
        } else {
            redirect(MY_ADMIN);
        }
    }
    
    public function insert() {
        echo json_encode($this->organization->insert_model());
    }
    
    public function update() {
        echo json_encode($this->organization->update_model());
    }
    
    public function isActive() {
        echo json_encode($this->organization->isActive_model());
    }
    
    public function delete() {
        echo json_encode($this->organization->delete_model());
    }
    
    public function searchContent() {
        echo json_encode($this->organization->searchContent_model());
    }
}

?>