<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrPeopleRank extends CI_Controller {

    public static $path = "shrPeopleRank/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ShrPeopleRank_model', 'hrPeopleRank');

        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $header['cssFile'] = array();
            $header['jsFile'] = array('/assets/system/core/hrPeopleRank.js');

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
                $this->load->view(MY_ADMIN . '/hrPeopleRank/index', $body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $body);
            }
            $this->load->view(MY_ADMIN . '/footer');
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
            $totalRec = $this->hrPeopleRank->listsCount_model(array(
                'auth' => $auth,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            $result = $this->hrPeopleRank->lists_model(array(
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

            $body['row'] = $this->hrPeopleRank->addFormData_model();

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
                'html' => $this->load->view(MY_ADMIN . '/hrPeopleRank/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->hrPeopleRank->editFormData_model(array('id' => $this->input->post('id')));

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'update',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['row']->mod_id = $body['auth']->modId;
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            echo json_encode(array(
                'title' => $module->title . ' - Засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeopleRank/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->hrPeopleRank->insert_model());
    }

    public function update() {
        echo json_encode($this->hrPeopleRank->update_model());
    }

    public function delete() {
        echo json_encode($this->hrPeopleRank->delete_model());
    }

    public function controlHrPeopleRankDropDown() {
        echo json_encode($this->hrPeopleRank->controlHrPeopleRankDropDown_model(array(
                    'parentId' => $this->input->post('parentId'),
                    'selectedId' => $this->input->post('selectedId'),
                    'name' => $this->input->post('name'),
                    'disabled' => $this->input->post('disabled'),
                    'readonly' => $this->input->post('readonly'),
                    'required' => $this->input->post('required'))));
    }

    function searchForm() {
        $this->body['row'] = $this->hrPeopleRank->addFormData_model();

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/hrPeopleRank/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

}
