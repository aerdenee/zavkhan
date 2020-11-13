<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Spage extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Smodule_model', 'module');
    }
    
    public function deny() {
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            
            $this->body['dataHtml'] = 'sdafdsa';
            
            
            echo json_encode(array(
            'title' => 'Хандах эрх хүрэхгүй хуудас',
            'html' => $this->load->view(MY_ADMIN . '/page/deny', $this->body, TRUE),
            'btn_yes' => 'Хаах',
            'btn_no' => 'Болих'
        ));
    }

}
