<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsKeywords extends CI_Controller {

    public static $path = "snifsKeywords/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsKeywords_model', 'nifsKeywords');
        $this->load->model('Smodule_model', 'module');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function crimeValueLists() {

        echo json_encode($this->nifsKeywords->crimeValueLists_model(array(
                    'modId' => $this->input->get('modId'),
                    'departmentId' => $this->input->get('departmentId'),
                    'keyword' => $this->input->get('keyword'))));
    }

    public function insert($param = array()) {
        echo json_encode($this->nifsKeywords->insert_model(array(
                    'modId' => $this->input->post('modId'),
                    'departmentId' => $this->input->post('departmentId'),
                    'keyword' => $this->input->post('keyword'))));
    }
    
    
    public function agentNameLists() {

        echo json_encode($this->nifsKeywords->agentNameLists_model(array(
                    'modId' => $this->input->get('modId'),
                    'departmentId' => $this->input->get('departmentId'),
                    'keyword' => $this->input->get('keyword'))));
    }

    public function agentNameInsert($param = array()) {
        echo json_encode($this->nifsKeywords->agentNameInsert_model(array(
                    'modId' => $this->input->post('modId'),
                    'departmentId' => $this->input->post('departmentId'),
                    'keyword' => $this->input->post('keyword'))));
    }
    
    
    

}
