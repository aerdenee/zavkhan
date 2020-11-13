<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tpoll extends CI_Controller {

    public static $path = "poll/";

    function __construct() {
        parent::__construct();
        $this->load->model('Tpoll_model', 'poll');
    }

    public function pollBlockItem() {
        echo json_encode($this->poll->pollBlockItemData_model(array('pollId' => $this->input->post('pollId'))));
    }
    
    public function vote() {
        echo json_encode($this->poll->vote_model(array('pollId' => $this->input->post('pollId'), 'pollDetailId' => $this->input->post('pollDetailId'))));
    }

}
