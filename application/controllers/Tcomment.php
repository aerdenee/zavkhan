<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tcomment extends CI_Controller {

    public static $path = "comment/";

    function __construct() {
        parent::__construct();
        $this->load->model('Tcomment_model', 'tcomment');
    }

    public function lists() {
         echo json_encode($this->tcomment->lists_model(array(
                'modId' => $this->input->post('modId'),
                'contId' => $this->input->post('contId'),
                'sortType' => $this->input->post('sortType'))));
    }
    
     public function insert() {
         echo json_encode($this->tcomment->insert_model(array(
                'modId' => $this->input->post('modId'),
                'contId' => $this->input->post('contId'),
                'langId' => $this->input->post('langId'),
                'sortType' => $this->input->post('sortType'),
             'parentId' => $this->input->post('parentId'),
             'title' => $this->input->post('title'),
             'comment' => ($this->input->post('parentId') != 0 ? $this->input->post('replyComment') : $this->input->post('comment')))));
    }
    
    
}