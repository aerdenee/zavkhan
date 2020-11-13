<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tlanguage extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Tlanguage_model', 'tlanguage');
    }

    public function changeLang($shortCode) {
        $this->langPath = '';
        $this->tlanguage->setThemeSession_model(array('shortCode' => $shortCode, 'langPath' => '/'));
        if ($shortCode == 'en') {
            $this->langPath = $shortCode;
        }
        redirect($this->langPath, 'refresh');
    }
    
    
    
    
    
}