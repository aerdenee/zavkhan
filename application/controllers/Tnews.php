<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tnews extends CI_Controller {

    public static $path = "news/";

    function __construct() {
        parent::__construct();
        $this->load->model('Tnews_model', 'news');
    }

    public function downloadFile() {
        if ($this->uri->segment(2)) {
            $this->news->downloadFile_model(array('mediaId' => base64_decode($this->uri->segment(2))));
        }
        
    }
    public function showTimeLineYear() {
        echo json_encode(array('html' => $this->news->getItemMedia_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'type' => 1))));
    }

    public function showTimeLine() {
        echo json_encode(array('html' => $this->news->getItemMedia_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId'), 'type' => 1))));
    }

    public function trans() {
        $this->news->trans_model();
    }

    public function transList() {

        $this->transList = $this->news->transList_model();
        if ($this->transList) {
            foreach ($this->transList as $key => $row) {
                
//                $this->ppath = FCPATH . '/upload/image/' .$row->pic;
                echo '<pre>';
                var_dump($row->pic);
                echo '</pre>';
//                
//                echo '<pre>';
//                var_dump(is_file($this->ppath));
//                echo '</pre>';
//                $ext = explode(".", $row->pic);
//                $ok = rename ( $this->ppath . $row->pic, $this->ppath . $row->id . date('Ymdhis') . '.' . $ext['1']);
//                var_dump($ok);die;
            }
        }
    }

}
