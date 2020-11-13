<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Saddress extends CI_Controller {

    public static $path = "saddress/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Saddress_model', 'address');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Saddress_model', 'address');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/_address.js');

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
                $this->load->view(MY_ADMIN . '/address/index', $this->body);
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

            $this->module = $this->module->getData_model(array('id' => $this->input->get('modId')));

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalRec = $this->address->listsCount_model(array(
                'auth' => $this->auth,
                'modId' => $this->auth->modId,
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['modId'] = $this->input->get('modId');
            $config['total_rows'] = $totalRec;
            $config["cur_page"] = $page;
            $config['per_page'] = /*PAGINATION_PER_PAGE*/100;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initAddress';
            $this->ajax_pagination->initialize($config);
            //get posts data
            echo json_encode($this->address->lists_model(array(
                        'title' => $this->module->title,
                        'auth' => $this->auth,
                        'catId' => $this->input->get('catId'),
                        'keyword' => $this->input->get('keyword'),
                        'space' => 10,
                        'totalRows' => $totalRec,
                        'limit' => $config["per_page"],
                        'page' => $page,
                        'path' => 'lists',
                        'paginationHtml' => $this->ajax_pagination->create_links())));

            //load the view
            //$this->load->view(MY_ADMIN . '/address/lists', $data, false);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->address->addFormData_model();

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAddressParentMultiRowDropdown'] = $this->address->controlAddressParentMultiRowDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->parent_id, 'id' => $this->body['row']->id, 'parentId' => 0, 'space' => ''));

            echo json_encode(array(
                'title' => $this->module->title . ' - Нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/address/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->address->editFormData_model(array('id' => $this->input->post('id')));

            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));
            $this->body['controlAddressParentMultiRowDropdown'] = $this->address->controlAddressParentMultiRowDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->parent_id, 'id' => $this->body['row']->id, 'parentId' => 0, 'space' => ''));

            echo json_encode(array(
                'title' => $this->module->title . ' - Засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/address/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->address->insert_model());
    }

    public function update() {
        echo json_encode($this->address->update_model());
    }

    public function delete() {
        echo json_encode($this->address->delete_model());
    }

    public function controlAddressDropDown() {
        echo json_encode($this->address->controlAddressDropDown_model(array(
                    'parentId' => $this->input->post('parentId'),
                    'selectedId' => $this->input->post('selectedId'),
                    'name' => $this->input->post('name'),
                    'disabled' => $this->input->post('disabled'),
                    'readonly' => $this->input->post('readonly'),
                    'required' => $this->input->post('required'))));
    }

    function searchForm() {
        $this->body['row'] = $this->address->addFormData_model();
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1));


        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/address/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

}
