<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsCloseType extends CI_Controller {

    public static $path = "snifsCloseType/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');
        $this->load->model('Smodule_model', 'module');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/nifsCloseType.js');

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
                $this->load->view(MY_ADMIN . '/nifsCloseType/index', $this->body);
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
            $totalRec = $this->nifsCloseType->listsCount_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            //get posts data
            $result = $this->nifsCloseType->lists_model(array(
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

            $this->body['row'] = $this->nifsCloseType->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - Нэмэх';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsCloseType/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsCloseType->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - Засах';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsCloseType/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->nifsCloseType->insert_model());
    }

    public function update() {
        echo json_encode($this->nifsCloseType->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsCloseType->delete_model());
    }

    function searchForm() {
        $this->body['row'] = $this->nifsCloseType->addFormData_model();

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/nifsCloseType/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function controlNifsCloseTypeDropdown() {
        echo json_encode($this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('catId' => $this->input->get('catId'), 'selectedId' => $this->input->get('selectedId'))));
    }

}
