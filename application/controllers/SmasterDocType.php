<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SmasterDocType extends CI_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('Spage_model', 'page');
        $this->load->model('SmasterDocType_model', 'smasterDocType');
        $this->load->model('Smodule_model', 'module');

    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $header['cssFile'] = array();
            $footer['jsFile'] = array('/assets/system/core/_masterDocType.js');

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['module'] = $this->module->getData_model(array('id' => $body['auth']->modId));

            $header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $body['module']->title));

            $this->load->view(MY_ADMIN . '/header', $header);
            if ($body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/masterDocType/index', $body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $body);
            }
            $this->load->view(MY_ADMIN . '/footer', $footer);
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        if ($this->session->isLogin === TRUE) {

            $auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            //total rows count
            $totalRec = $this->smasterDocType->listsCount_model(array(
                'auth' => $auth,
                'keyword' => $this->input->get('keyword')));

            $result = $this->smasterDocType->lists_model(array(
                'auth' => $auth,
                'keyword' => $this->input->get('keyword'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));

        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->smasterDocType->addFormData_model();
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            echo json_encode(array(
                'title' => $module->title . ' нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 600,
                'html' => $this->load->view(MY_ADMIN . '/masterDocType/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {
        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->smasterDocType->editFormData_model(array('selectedId' => $this->input->post('id')));
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            echo json_encode(array(
                'title' => $module->title . ' засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 600,
                'html' => $this->load->view(MY_ADMIN . '/masterDocType/form', $body, TRUE)));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->getUID = getUID('master_doc_type');
        echo json_encode($this->smasterDocType->insert_model(array('getUID' => $this->getUID)));
    }

    public function update() {

        echo json_encode($this->smasterDocType->update_model(array()));
    }

    public function delete() {
        echo json_encode($this->smasterDocType->delete_model());
    }

    public function searchForm() {

        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        echo json_encode(array(
            'title' => 'Хайлт хийх түлхүүр үгээ бичнэ үү',
            'html' => $this->load->view(MY_ADMIN . '/masterDocType/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function controlMasterDocTypeListDropdown() {
        echo json_encode($this->smasterDocType->controlMasterDocTypeListDropdown_model(array('name' => $this->input->post('name'), 'modId' => $this->input->post('modId'), 'selectedId' => $this->input->post('selectedId'))));
    }
    
}
