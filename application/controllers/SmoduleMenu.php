<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SmoduleMenu extends CI_Controller {

    public static $path = "smodulemenu/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SmoduleMenu_model', 'moduleMenu');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('SmoduleMenuType_model', 'moduleMenuType');

        $this->body = $this->footer = array();
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/_moduleMenu.js');

            $this->body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId'],
                'role' => 'read'));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));


            $this->load->view(MY_ADMIN . '/header', $this->header);
            if ($this->body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/moduleMenu/index', $this->body);
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

            $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $totalCount = $this->moduleMenu->listsCount_model(array(
                'auth' => $this->auth,
                'catId' => $this->input->get('catId')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['modId'] = $this->input->get('moduleId');
            $config['total_rows'] = $totalCount;
            $config["cur_page"] = $page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initModuleMenu';
            $this->ajax_pagination->initialize($config);

            //get posts data
            echo json_encode($this->moduleMenu->lists_model(array(
                        'auth' => $this->auth,
                        'catId' => $this->input->get('catId'),
                        'moduleId' => $this->input->get('moduleId'),
                        'moduleMenuId' => $this->input->get('moduleMenuId'),
                        'title' => 'Модуль меню',
                        'limit' => $config["per_page"],
                        'page' => $page,
                        'path' => 'lists',
                        'totalRows' => $totalCount,
                        'paginationHtml' => $this->ajax_pagination->create_links())));
        }
    }

    public function add() {

        $this->body = array();

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->moduleMenu->addFormData_model();

            $this->body['controlParentModuleMenuListDropdown'] = $this->moduleMenu->controlParentModuleMenuListDropdown_model();
            $this->body['controlModuleListDropdown'] = $this->module->controlModuleListDropdown_model(array('selectedId' => $this->body['row']->mod_id));
            $this->body['controlModuleMenuTypeRadioButton'] = $this->moduleMenuType->controlModuleMenuTypeRadioButton_model(array('selectedId' => $this->body['row']->menu_type_id));
            $this->body['controlCategoryDropdown'] = $this->moduleMenu->controlCategoryDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id));
            $this->body['controlContentDropdown'] = $this->moduleMenu->controlContentDropdown_model(array('modId' => $this->body['row']->mod_id, 'catId' => $this->body['row']->cat_id, 'selectedId' => $this->body['row']->cont_id));

            echo json_encode(array(
                'title' => 'Системийн меню бүртгэл',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 500,
                'html' => $this->load->view(MY_ADMIN . '/moduleMenu/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->moduleMenu->editFormData_model(array('id' => $this->input->post('id')));
            $this->body['controlParentModuleMenuListDropdown'] = $this->moduleMenu->controlParentModuleMenuListDropdown_model(array('selectedId' => $this->body['row']->parent_id));
            $this->body['controlModuleListDropdown'] = $this->module->controlModuleListDropdown_model(array('selectedId' => $this->body['row']->mod_id));
            $this->body['controlModuleMenuTypeRadioButton'] = $this->moduleMenuType->controlModuleMenuTypeRadioButton_model(array('selectedId' => $this->body['row']->menu_type_id));
            $this->body['controlCategoryDropdown'] = $this->moduleMenu->controlCategoryDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => $this->body['row']->cat_id));
            $this->body['controlContentDropdown'] = $this->moduleMenu->controlContentDropdown_model(array('modId' => $this->body['row']->mod_id, 'catId' => $this->body['row']->cat_id, 'selectedId' => $this->body['row']->cont_id,  'menuTypeId' => $this->body['row']->menu_type_id));

            echo json_encode(array(
                'title' => 'Системийн меню засварлах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 500,
                'html' => $this->load->view(MY_ADMIN . '/moduleMenu/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->moduleMenu->insert_model(array()));
    }

    public function update() {
        echo json_encode($this->moduleMenu->update_model());
    }

    public function delete() {
        echo json_encode($this->moduleMenu->delete_model());
    }

    public function controlCategoryDropdown() {
        echo json_encode($this->moduleMenu->controlCategoryDropdown_model(array('modId' => $this->input->post('modId'), 'selectedId' => $this->input->post('selectedId'))));
    }

    public function controlContentDropdown() {
        echo json_encode($this->moduleMenu->controlContentDropdown_model(array('modId' => $this->input->post('modId'), 'catId' => $this->input->post('catId'), 'selectedId' => $this->input->post('selectedId'))));
    }

    function searchForm() {
        $this->body['row'] = $this->moduleMenu->addFormData_model();
        $this->body['modId'] = $this->body['row']->mod_id;
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['row']->mod_id, 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));

        $this->body['controlCrimeResearchTypeDropdown'] = $this->moduleMenu->controlCrimeResearchTypeDropdown_model(array('selectedId' => 0));
        $this->body['controlCrimeTypeDropdown'] = $this->moduleMenu->controlCrimeTypeDropdown_model(array('selectedId' => 0));
        $this->body['controlMotiveDropdown'] = $this->moduleMenu->controlMotiveDropdown_model(array('selectedId' => 0));
        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => 0, 'required' => true));
        $this->body['controlCloseTypeDropDown'] = $this->moduleMenu->controlCloseTypeDropDown_model(array('selectedId' => 0));
        $this->body['controlLatentPrintDropDown'] = $this->moduleMenu->controlLatentPrintDropDown_model(array('selectedId' => 0, 'name' => 'latentPrintExpertId', 'extraIsCrimeScene' => '1,2'));
        $this->body['controlExpertDropDown'] = $this->moduleMenu->controlLatentPrintDropDown_model(array('selectedId' => 0, 'name' => 'expertId', 'extraIsCrimeScene' => '2'));

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/crime/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

}
