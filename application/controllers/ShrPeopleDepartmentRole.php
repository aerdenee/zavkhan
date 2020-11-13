<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrPeopleDepartmentRole extends CI_Controller {

    public static $path = "shrPeopleDepartment/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('ShrPeopleDepartmentRole_model', 'hrPeopleDepartmentRole');
        $this->load->model('Smodule_model', 'module');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/hrPeopleDepartmentRole.js');

            //load the view
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/hrPeopleDepartmentRole/index', $this->body);
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
            $totalRec = $this->hrPeopleDepartmentRole->listsCount_model(array(
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['total_rows'] = $totalRec;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initPartner';
            $this->ajax_pagination->initialize($config);


            //get posts data
            echo json_encode($this->hrPeopleDepartmentRole->lists_model(array(
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
            //$this->load->view(MY_ADMIN . '/hrPeopleDepartmentRole/lists', $data, false);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->hrPeopleDepartmentRole->addFormData_model();

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'insert', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlHrPeopleDepartmentParentMultiRowDropdown'] = $this->hrPeopleDepartmentRole->controlHrPeopleDepartmentParentMultiRowDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->parent_id, 'id' => $this->body['row']->id, 'parentId' => 0, 'space' => ''));

            echo json_encode(array(
                'title' => $this->module->title . ' - Нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeopleDepartmentRole/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->hrPeopleDepartmentRole->editFormData_model(array('id' => $this->input->post('id')));

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'insert', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlHrPeopleDepartmentParentMultiRowDropdown'] = $this->hrPeopleDepartmentRole->controlHrPeopleDepartmentParentMultiRowDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->parent_id, 'id' => $this->body['row']->id, 'parentId' => 0, 'space' => ''));

            echo json_encode(array(
                'title' => $this->module->title . ' - Засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeopleDepartmentRole/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->hrPeopleDepartmentRole->insert_model());
    }

    public function update() {
        echo json_encode($this->hrPeopleDepartmentRole->update_model());
    }

    public function delete() {
        echo json_encode($this->hrPeopleDepartmentRole->delete_model());
    }

    function searchForm() {
        $this->body['row'] = $this->hrPeopleDepartmentRole->addFormData_model();

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/hrPeopleDepartmentRole/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function import() {
        $this->hrPeopleDepartmentRole->import_model();
    }
    
    public function controlHrPeopleDepartmentDropdown() {
        echo json_encode($this->hrPeopleDepartmentRole->controlHrPeopleDepartmentDropdown_model(array('modId' => $this->input->get('modId'), 'selectedId' => $this->input->get('selectedId'))));
    }
    

}