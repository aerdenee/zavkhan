<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsMotive extends CI_Controller {

    public static $path = "snifsCrimeType/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('Smodule_model', 'module');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/nifsMotive.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));
            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));

            //load the view
            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/nifsMotive/index', $this->body);
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
                'role' => 'read',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));

            //total rows count
            $totalRec = $this->nifsMotive->listsCount_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            //get posts data
            $result = $this->nifsMotive->lists_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword'),
                'totalRows' => $totalRec,
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
            
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsMotive->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - Нэмэх';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'required' => true));
            
            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsMotive/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsMotive->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - Засах';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'required' => true));
            
            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsMotive/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->nifsMotive->insert_model());
    }

    public function update() {
        echo json_encode($this->nifsMotive->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsMotive->delete_model());
    }

    function searchForm() {
        $this->body['row'] = $this->nifsMotive->addFormData_model();
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => 0, 'required' => true));
        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/nifsMotive/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function controlNifsMotiveDropdown() {
        echo json_encode($this->nifsMotive->controlNifsMotiveDropdown_model(array('selectedId' => $this->input->get('selectedId'))));
    }

}
