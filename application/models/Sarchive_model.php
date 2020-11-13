<?php

class Sarchive_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
    }

    
    public function setCloseYear_model($param = array()) {

        return $this->session->set_userdata(array('adminCloseYear' => $param['closeYear']));

    }

}
