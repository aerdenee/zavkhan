<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrPeopleDepartment extends CI_Controller {

    public static $path = "shrPeopleDepartment/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('Smodule_model', 'module');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $header['cssFile'] = array();
            $header['jsFile'] = array('/assets/system/core/hrPeopleDepartment.js');

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
                $this->load->view(MY_ADMIN . '/hrPeopleDepartment/index', $body);
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
                'role' => 'read',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            $module = $this->module->getData_model(array('id' => $auth->modId));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalCount = $this->hrPeopleDepartment->listsCount_model(array(
                'auth' => $auth,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword'),
                'departmentId' => $this->input->get('departmentId')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['total_rows'] = $totalCount;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initCategory';
            $this->ajax_pagination->initialize($config);

            //get posts data
            echo json_encode($this->hrPeopleDepartment->lists_model(array(
                        'auth' => $auth,
                        'catId' => $this->input->get('catId'),
                        'title' => $module->title,
                        'keyword' => $this->input->get('keyword'),
                        'departmentId' => $this->input->get('departmentId'),
                        'limit' => $config["per_page"],
                        'page' => $page,
                        'path' => 'lists',
                        'totalRows' => $totalCount,
                        'paginationHtml' => $this->ajax_pagination->create_links())));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->hrPeopleDepartment->addFormData_model();

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'create',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['row']->mod_id = $body['auth']->modId;
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            $body['controlHrPeopleDepartmentParentMultiRowDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentParentMultiRowDropdown_model(array('modId' => $body['row']->mod_id, 'selectedId' => $body['row']->parent_id, 'id' => $body['row']->id, 'parentId' => 0, 'space' => ''));

            echo json_encode(array(
                'title' => $module->title . ' - Нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeopleDepartment/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->hrPeopleDepartment->editFormData_model(array('id' => $this->input->post('id')));

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'update',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['row']->mod_id = $body['auth']->modId;
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            $body['controlHrPeopleDepartmentParentMultiRowDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentParentMultiRowDropdown_model(array('modId' => $body['row']->mod_id, 'selectedId' => $body['row']->parent_id, 'id' => $body['row']->id, 'parentId' => 0, 'space' => ''));

            echo json_encode(array(
                'title' => $module->title . ' - Засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeopleDepartment/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->hrPeopleDepartment->insert_model());
    }

    public function update() {
        echo json_encode($this->hrPeopleDepartment->update_model());
    }

    public function delete() {
        echo json_encode($this->hrPeopleDepartment->delete_model());
    }

    function searchForm() {
        $this->body['row'] = $this->hrPeopleDepartment->addFormData_model();

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/hrPeopleDepartment/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function import() {
        $this->hrPeopleDepartment->import_model();
    }

    public function controlHrPeopleDepartmentDropdown() {
        echo json_encode($this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
            'name' => $this->input->post('name'), 
            'onlyMyDepartment' => $this->input->post('onlyMyDepartment'), 
            'modId' => $this->input->post('modId'), 
            'selectedId' => $this->input->post('selectedId'),
            'onchange' => $this->input->post('onchange'))));
    }

}
