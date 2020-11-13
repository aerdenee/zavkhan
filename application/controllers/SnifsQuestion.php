<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsQuestion extends CI_Controller {

    public static $path = "snifsQuestion/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SnifsQuestion_model', 'nifsQuestion');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/nifsQuestion.js');

            $this->body['auth'] = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->uri->segment(3)));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));
            $this->page->deny_model(array('moduleMenuId' => $this->uri->segment(3), 'mode' => 'read', 'createdUserId' => $this->session->adminUserId));

            //load the view
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/nifsQuestion/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->input->get('moduleMenuId')));
            $this->module = $this->module->getData_model(array('id' => $this->auth->modId));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalRec = $this->nifsQuestion->listsCount_model(array(
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'modId' => $this->auth->modId,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['modId'] = $this->input->get('modId');
            $config['total_rows'] = $totalRec;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initNifsQuestion';
            $this->ajax_pagination->initialize($config);
            //get posts data
            echo json_encode($this->nifsQuestion->lists_model(array(
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
            //$this->load->view(MY_ADMIN . '/nifsQuestion/lists', $data, false);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsQuestion->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - Нэмэх';

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'insert', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsQuestion/form', $this->body, TRUE)
            ));
            exit();
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsQuestion->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - Засах';
            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'insert', 'createdUserId' => $this->body['row']->created_user_id));
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));


            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsQuestion/formEdit', $this->body, TRUE)
            ));
            exit();
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->nifsQuestion->insert_model());
    }

    public function update() {
        echo json_encode($this->nifsQuestion->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsQuestion->delete_model());
    }

    public function controlNifsQuestionDropDown() {
        echo json_encode($this->nifsQuestion->controlNifsQuestionDropDown_model(array(
                    'parentId' => $this->input->post('parentId'),
                    'selectedId' => $this->input->post('selectedId'),
                    'name' => $this->input->post('name'),
                    'disabled' => $this->input->post('disabled'),
                    'readonly' => $this->input->post('readonly'),
                    'required' => $this->input->post('required'))));
    }

    public function controlNifsQuestionMultipleDropDown() {
        echo json_encode($this->nifsQuestion->controlNifsQuestionMultipleDropDown_model(array(
                    'modId' => $this->input->post('modId'),
                    'contId' => $this->input->post('contId'),
                    'catId' => $this->input->post('catId'),
                    'initControlHtml' => $this->input->post('initControlHtml'),
                    'isDeleteButton' => $this->input->post('isDeleteButton'))));
    }

    function searchForm() {
        $this->body['row'] = $this->nifsQuestion->addFormData_model();
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));


        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/nifsQuestion/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

}
