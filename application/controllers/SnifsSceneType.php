<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsSceneType extends CI_Controller {

    public static $path = "snifsSceneType/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsSceneType_model', 'nifsSceneType');
        $this->load->model('Smodule_model', 'module');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/nifsSceneType.js');

            $this->body['auth'] = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->uri->segment(3)));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));
            $this->page->deny_model(array('moduleMenuId' => $this->uri->segment(3), 'mode' => 'read', 'createdUserId' => $this->session->adminUserId));
            
            //load the view
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/nifsSceneType/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
            
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->module = $this->module->getData_model(array('id' => $this->input->get('modId')));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalRec = $this->nifsSceneType->listsCount_model(array(
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['total_rows'] = $totalRec;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initNifsSceneType';
            $this->ajax_pagination->initialize($config);


            //get posts data
            echo json_encode($this->nifsSceneType->lists_model(array(
                        'title' => $this->module->title,
                        'moduleMenuId' => $this->input->get('moduleMenuId'),
                        'catId' => $this->input->get('catId'),
                        'keyword' => $this->input->get('keyword'),
                        'space' => 10,
                        'totalRows' => $totalRec,
                        'limit' => $config["per_page"],
                        'page' => $page,
                        'path' => 'lists',
                        'paginationHtml' => $this->ajax_pagination->create_links())));

            //load the view
            //$this->load->view(MY_ADMIN . '/nifsSceneType/lists', $data, false);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsSceneType->addFormData_model();

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'insert', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));

            echo json_encode(array(
                'title' => $this->module->title . ' - Нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsSceneType/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsSceneType->editFormData_model(array('id' => $this->input->post('id')));

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'insert', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));

            echo json_encode(array(
                'title' => $this->module->title . ' - Засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsSceneType/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->nifsSceneType->insert_model());
    }

    public function update() {
        echo json_encode($this->nifsSceneType->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsSceneType->delete_model());
    }

    function searchForm() {
        $this->body['row'] = $this->nifsSceneType->addFormData_model();

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/nifsSceneType/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function controlNifsSceneTypeDropdown() {
        echo json_encode($this->nifsSceneType->controlNifsSceneTypeDropdown_model(array('catId' => $this->input->get('catId'), 'selectedId' => $this->input->get('selectedId'))));
    }

}
