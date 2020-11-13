<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slayout extends CI_Controller {

    public static $path = "slayout/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Slayout_model', 'slayout');
        $this->load->model('Smodule_model', 'module');
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/_layout.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/layout/index', $this->body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $this->body);
            }
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
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

            //total rows count
            $totalRec = $this->slayout->listsCount_model(array(
                'auth' => $this->auth,
                'modId' => $this->auth->modId,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'userId' => $this->input->get('userId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId')));

            $result = $this->slayout->lists_model(array(
                'auth' => $this->auth,
                'modId' => $this->auth->modId,
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'userId' => $this->input->get('userId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {


            $body['contentJsFile'] = array();

            $body['row'] = $this->slayout->addFormData_model();

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'create',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['row']->mod_id = $body['auth']->modId;
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));
            $body['row']->module_title = $module->title . ' нэмэх';

            echo json_encode(array(
                'title' => $body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/layout/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['contentJsFiles'] = array('/assets/system/core/_layout.js');

            $this->body['row'] = $this->slayout->editFormData_model(array('selectedId' => $this->input->post('id')));

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            echo json_encode(array(
                'title' => $this->module->title . ' засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/layout/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        echo json_encode($this->slayout->insert_model());
    }

    public function update() {

        echo json_encode($this->slayout->update_model());
    }

    public function delete() {
        echo json_encode($this->slayout->delete_model());
    }

    public function searchForm() {
        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->slayout->addFormData_model();

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            $body['row']->mod_id = $body['auth']->modId;

            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            echo json_encode(array(
                'title' => $module->title . ' дэлгэрэнгүй хайлт',
                'btn_yes' => 'Хайх',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/layout/formSearch', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

}
