<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SdocFile extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('Spage_model', 'page');
        $this->load->model('SdocFile_model', 'sdocFile');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ScontentMediaType_model', 'contentMediaType');
    }

    public function insert() {
        echo json_encode($this->sdocFile->insert_model());
    }

    public function update() {
        echo json_encode($this->sdocFile->update_model());
    }

    public function delete() {
        echo json_encode($this->sdocFile->delete_model(array('attachFile' => $this->input->post('attachFile'), 'selectedId' => $this->input->post('id'))));
    }

    public function printFile() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->sdocFile->getData_model(array('selectedId' => $this->input->post('selectedId')));
            $body['row']->attach_file = $this->input->post('attachFile');
            echo json_encode(array(
                'title' => 'Хэвлэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/docFile/printFile', $body, TRUE)
            ));
        }
    }

    public function show() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->sdocFile->getData_model(array('selectedId' => $this->input->post('selectedId')));
            $body['row']->attach_file = $this->input->post('attachFile');

            echo json_encode(array(
                'title' => 'Албан бичгийн хавсралт',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/docFile/show', $body, TRUE)
            ));
        }
    }

}
