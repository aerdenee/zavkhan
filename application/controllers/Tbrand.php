<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tbrand extends CI_Controller {

    public static $path = "brand/";

    function __construct() {
        parent::__construct();
        $this->load->model('Tbrand_model', 'brand');
    }

    public function brandlist() {
         echo json_encode(array('html'=>$this->brand->lists_model(array('catId' => $this->input->post('catId')))));
    }
    
    public function update() {
         $this->brand->update_model();
    }
    
    
}