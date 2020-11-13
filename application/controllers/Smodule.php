<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Smodule extends CI_Controller {

    public static $path = "smodule/";

    function __construct() {
        parent::__construct();
        
        $this->load->model('Smodule_model', 'module');

    }


    function updateThemeLayout() {
        $this->module->updateThemeLayout_model();
    }
    
    function setThemeLayout() {
        $this->module->setThemeLayout_model();
    }
    
    function updatePermission() {
        $this->module->updatePermission_model();
    }
    

}
