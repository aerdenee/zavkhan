<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrPeopleRelation extends CI_Controller {

    public static $path = "shrPeopleRelation/";

    function __construct() {
        parent::__construct();
        $this->load->model('ShrPeopleRelation_model', 'hrPeopleRelation');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function controlHrPeopleRelationDropdown() {
        echo json_encode($this->hrPeopleRelation->controlHrPeopleRelationDropdown_model(array('selectedId' => $this->input->post('selectedId'), 'name' => $this->input->post('name'))));
    }

}
