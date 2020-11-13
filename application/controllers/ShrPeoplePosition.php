<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrPeoplePosition extends CI_Controller {

    public static $path = "shrPeoplePosition/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $header['cssFile'] = array();
            $footer['jsFile'] = array('/assets/system/core/hrPeoplePosition.js');

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
                $this->load->view(MY_ADMIN . '/hrPeoplePosition/index', $body);
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
            $totalRec = $this->hrPeoplePosition->listsCount_model(array(
                'auth' => $auth,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            $result = $this->hrPeoplePosition->lists_model(array(
                'auth' => $auth,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $body['contentJsFile'] = array();

            $body['row'] = $this->hrPeoplePosition->addFormData_model();

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'create',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['row']->mod_id = $body['auth']->modId;
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            echo json_encode(array(
                'title' => $module->title . ' - Нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeoplePosition/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->hrPeoplePosition->editFormData_model(array('id' => $this->input->post('id')));

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'create',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['row']->mod_id = $body['auth']->modId;
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            echo json_encode(array(
                'title' => $module->title . ' - Засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeoplePosition/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->hrPeoplePosition->insert_model());
    }

    public function update() {
        echo json_encode($this->hrPeoplePosition->update_model());
    }

    public function delete() {
        echo json_encode($this->hrPeoplePosition->delete_model());
    }

    public function controlHrPeoplePositionDropDown() {
        echo json_encode($this->hrPeoplePosition->controlHrPeoplePositionDropDown_model(array(
                    'parentId' => $this->input->post('parentId'),
                    'selectedId' => $this->input->post('selectedId'),
                    'name' => $this->input->post('name'),
                    'disabled' => $this->input->post('disabled'),
                    'readonly' => $this->input->post('readonly'),
                    'required' => $this->input->post('required'))));
    }

    function searchForm() {
        $this->body['row'] = $this->hrPeoplePosition->addFormData_model();

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/hrPeoplePosition/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

}
