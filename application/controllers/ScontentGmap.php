<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ScontentGmap extends CI_Controller {

    public static $path = "scontentGmap/";

    function __construct() {
        parent::__construct();
        $this->load->model('Scategory_model', 'category');
        $this->load->model('ScontentGmap_model', 'contentGmap');
        $this->load->model('Spage_model', 'page');
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            echo json_encode($this->contentGmap->lists_model(array(
                        'title' => 'Газрын зураг',
                        'auth' => $this->auth,
                        'modId' => $this->input->get('modId'),
                        'contId' => $this->input->get('contId'))));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {


            $body['contentJsFile'] = array();

            $body['row'] = $this->contentGmap->addFormData_model(array('contId' => $this->input->post('contId'), 'modId' => $this->input->post('modId')));

            $body['row']->module_title = 'Газрын зураг нэмэх';

            echo json_encode(array(
                'title' => $body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/map/google/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }
    
    public function edit() {

        if ($this->session->isLogin === TRUE) {


            $body['contentJsFile'] = array();

            $body['row'] = $this->contentGmap->editFormData_model(array('id' => $this->input->post('id')));
            $body['row']->module_title = 'Газрын зураг засах';

            echo json_encode(array(
                'title' => $body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/map/google/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('map');
        echo json_encode($this->contentGmap->insert_model(array('getUID' => $this->getUID)));
    }

    public function update() {
        echo json_encode($this->contentGmap->update_model());
    }

    public function delete() {
        echo json_encode($this->contentGmap->delete_model());
    }

}

?>