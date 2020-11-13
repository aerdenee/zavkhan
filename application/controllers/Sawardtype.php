<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sawardtype extends CI_Controller {

    public static $path = "sawardtype/";

    function __construct() {
        
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sthemelayout_model', 'themelayout');
        $this->load->model('Systemowner_model', 'systemowner');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        
    }

}