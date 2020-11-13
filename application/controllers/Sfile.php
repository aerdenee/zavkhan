<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sfile extends CI_Controller {

    public static $path = "sfile/";

    function __construct() {

        parent::__construct();
        $this->load->model('Sfile_model', 'sfile');
    }

    public function fileUpload() {
        echo json_encode($this->sfile->fileUpload_model(array(
                    'uploadPath' => $this->input->post('uploadPath'),
                    'oldFile' => ($this->input->post($this->input->post('prefix') . 'AttachFile') != '' ? $this->input->post($this->input->post('prefix') . 'AttachFile') : $this->input->post($this->input->post('prefix') . 'OldAttachFile')),
                    'prefix' => $this->input->post('prefix'))));
    }

    public function fileDelete() {
        echo json_encode($this->sfile->fileDelete_model(array(
                    'prefix' => $this->input->post('prefix'),
                    'table' => $this->input->post('table'),
                    'selectedId' => $this->input->post('selectedId'),
                    'oldFile' => ($this->input->post($this->input->post('prefix') . 'AttachFile') != '' ? $this->input->post($this->input->post('prefix') . 'AttachFile') : $this->input->post($this->input->post('prefix') . 'OldAttachFile')),
                    'uploadPath' => $this->input->post('uploadPath'))));
    }

}
