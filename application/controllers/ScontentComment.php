<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ScontentComment extends CI_Controller {

    public static $path = "scontentComment/";

    function __construct() {
        parent::__construct();

        $this->load->model('ScontentComment_model', 'contentComment');
    }


    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            echo json_encode($this->contentComment->lists_model(array(
                        'contId' => $this->input->get('contId'),
                        'modId' => $this->input->get('modId'),
                        'sortType' => $this->input->get('sortType'))));
        }
    }

    public function delete() {
        if ($this->session->isLogin === TRUE) {
            echo json_encode($this->contentComment->delete_model(array('selectedId' => $this->input->post('id'))));
        }
    }

}
