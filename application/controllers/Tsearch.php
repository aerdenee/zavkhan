<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tsearch extends CI_Controller {

    public static $path = "news/";

    function __construct() {
        parent::__construct();
        $this->load->model('Tnews_model', 'news');
    }

    public function search() {
        $this->body = '';
        echo json_encode(array(
            'title' => 'Хайлт хийх',
            'html' => $this->load->view(DEFAULT_THEME . 'search/form', $this->body, TRUE),
            'btn_ok' => 'Хайх',
            'btn_no' => 'Хаах'));
    }

}
