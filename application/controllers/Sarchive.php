<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sarchive extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Sarchive_model', 'sarchive');
    }

    public function change() {

        $this->sarchive->setCloseYear_model(array('closeYear' => $this->uri->segment(3)));
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

}
