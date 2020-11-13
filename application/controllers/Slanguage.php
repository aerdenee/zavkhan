<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slanguage extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Slanguage_model', 'slanguage');
    }

    public function changeLang() {

        $this->slanguage->setThemeSession_model(array('id' => $this->uri->segment(3)));
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

}
