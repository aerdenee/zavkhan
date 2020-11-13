<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Spermission extends CI_Controller {

    public static $path = "spermission/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Spermission_model', 'permission');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Sbreadcrumb_model', 'breadcrumb');
        $this->load->model('Smodule_model', 'module');
        $this->header = $this->body = $this->footer = array();
        $this->perPage = 2;
    }

    public function index() {

        $this->footer['jsFile'] = array('/assets/system/core/_permission.js');

        if ($this->session->isLogin === TRUE) {

            $this->body['auth'] = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->uri->segment(3)));
            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['auth']->modId));

            $this->header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $this->body['module']->title));
            $this->page->deny_model(array('moduleMenuId' => $this->uri->segment(3), 'mode' => 'read', 'createdUserId' => $this->session->adminUserId));

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/permission/index', $this->body);
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(base_url('systemowner'));
        }
    }

    public function lists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->module = $this->module->getData_model(array('id' => $this->input->get('modId')));

            $this->page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;

            //total rows count
            $this->totalRows = $this->permission->listsCount_model(array(
                'modId' => $this->input->get('modId'),
                'catId' => $this->input->get('catId'),
                'keyword' => $this->input->get('keyword')));

            //pagination configuration
            $config['base_url'] = base_url('lists');
            $config['modId'] = $this->input->get('modId');
            $config['windowId'] = '#window-user';
            $config['total_rows'] = $this->totalRows;
            $config["cur_page"] = $this->page;
            $config['per_page'] = PAGINATION_PER_PAGE;
            $config['num_links'] = PAGINATION_NUM_LINKS;
            $config['link_func'] = '_initUser';
            $this->ajax_pagination->initialize($config);


            //get posts data
            echo json_encode($this->permission->lists_model(array(
                        'title' => $this->module->title,
                        'modId' => $this->input->get('modId'),
                        'catId' => $this->input->get('catId'),
                        'keyword' => $this->input->get('keyword'),
                        'limit' => $config["per_page"],
                        'page' => $this->page,
                        'path' => 'lists',
                        'paginationHtml' => $this->ajax_pagination->create_links())));

            //load the view
            //$this->load->view(MY_ADMIN . '/user/lists', $data, false);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {
            
            $this->body['row'] = $this->permission->addFormData_model();

            //$this->page->deny_model(array('modId' => $this->input->post('modId'), 'mode' => 'insert', 'createdUserId' => $this->session->adminUserId));
            $this->body['moduleMenu'] = $this->permission->getAllMenuHtml_model();
            echo json_encode(array(
                'title' => 'Хэрэглэгч нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 1200,
                'html' => $this->load->view(MY_ADMIN . '/permission/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array();
            $this->body = array();
            $this->body['mode'] = 'insert';
            $this->body['modId'] = $this->input->post('modId');
            $this->body['row'] = $this->permission->editFormData_model(array('selectedId' => $this->input->post('id')));

            $this->body['module'] = $this->module->getData_model(array('id' => $this->body['modId']));
            $this->header['breadcrumb'] = $this->breadcrumb->breadcrumb_model(array('title' => $this->body['module']->title, 'mode' => $this->body['mode']));

            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']->cat_id, 'parentId' => 0, 'space' => '', 'counter' => 1, 'required' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => $this->body['row']->partner_id, 'required' => true));

            echo json_encode(array(
                'title' => 'Хэрэглэгчийн мэдээлэл засварлах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 500,
                'html' => $this->load->view(MY_ADMIN . '/user/editForm', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function read() {

        if ($this->session->isLogin === TRUE) {
            $this->body['modId'] = $this->input->post('modId');
            $this->body['mode'] = 'update';
            $this->body['row'] = $this->permission->editFormData_model(array('id' => $this->input->post('id')));
            $this->page->deny_model(array('modId' => $this->body['modId'], 'mode' => $this->body['mode'], 'createdUserId' => $this->body['row']['created_user_id']));
            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => $this->body['row']['cat_id'], 'parentId' => 0, 'space' => '', 'counter' => 1, 'readonly' => true, 'disabled' => true));
            $this->body['controlCrimeResearchTypeDropdown'] = $this->permission->controlCrimeResearchTypeDropdown_model(array('selectedId' => $this->body['row']['crime_research_type_id'], 'readonly' => true, 'disabled' => true));
            $this->body['controlCrimeTypeDropdown'] = $this->permission->controlCrimeTypeDropdown_model(array('selectedId' => $this->body['row']['crime_type_id'], 'readonly' => true, 'disabled' => true));
            $this->body['controlMotiveDropdown'] = $this->permission->controlMotiveDropdown_model(array('selectedId' => $this->body['row']['crime_motive_id'], 'readonly' => true, 'disabled' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => $this->body['row']['partner_id'], 'readonly' => true, 'disabled' => true));
            $this->body['controlLatentPrintDropDown'] = $this->permission->controlLatentPrintDropDown_model(array('selectedId' => $this->body['row']['latent_print_expert_id'], 'name' => 'latentPrintExpertId', 'extraIsCrimeScene' => '1,2', 'readonly' => true, 'disabled' => true));
            $this->body['controlExpertDropDown'] = $this->permission->controlLatentPrintDropDown_model(array('selectedId' => $this->body['row']['expert_id'], 'name' => 'expertId', 'extraIsCrimeScene' => '2', 'readonly' => true, 'disabled' => true));

            echo json_encode(array(
                'title' => 'Шинжилгээний бүртгэл засварлах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/user/read', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->permission->insert_model(array('getUID'=>getUID('user'))));
    }

    public function update() {
        echo json_encode($this->permission->update_model());
    }

    public function delete() {
        echo json_encode($this->permission->delete_model());
    }

    public function resetPassword() {
        echo json_encode($this->permission->resetPassword_model());
    }
    
    public function checkUserName() {
        echo json_encode($this->permission->checkUserName_model(array('user' => $this->input->post('user'))));
    }
    
    public function checkUserEmail() {
        echo json_encode($this->permission->checkUserEmail_model(array('email' => $this->input->post('email'))));
    }
    
    function searchForm() {
        $this->body = array();
        $this->body['modId'] = $this->input->post('modId');
        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->body['modId'], 'selectedId' => 0, 'parentId' => 0, 'space' => '', 'counter' => 1));

        $this->body['controlCrimeResearchTypeDropdown'] = $this->permission->controlCrimeResearchTypeDropdown_model(array('selectedId' => 0));
        $this->body['controlCrimeTypeDropdown'] = $this->permission->controlCrimeTypeDropdown_model(array('selectedId' => 0));
        $this->body['controlMotiveDropdown'] = $this->permission->controlMotiveDropdown_model(array('selectedId' => 0));
        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => 0, 'required' => true));
        $this->body['controlCloseTypeDropDown'] = $this->permission->controlCloseTypeDropDown_model(array('selectedId' => 0));
        $this->body['controlLatentPrintDropDown'] = $this->permission->controlLatentPrintDropDown_model(array('selectedId' => 0, 'name' => 'latentPrintExpertId', 'extraIsCrimeScene' => '1,2'));
        $this->body['controlExpertDropDown'] = $this->permission->controlLatentPrintDropDown_model(array('selectedId' => 0, 'name' => 'expertId', 'extraIsCrimeScene' => '2'));

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/user/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function setPermissionForm() {
        echo json_encode(array(
                'title' => 'Нэвтрэх эрхийн тохиргоо',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 1200,
                'html' => $this->permission->setPermissionForm_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'userId' => $this->input->post('userId'), 'createdUserId' => $this->input->post('createdUserId')))
            ));

    }
    
    public function savePermission() {
        echo json_encode($this->permission->savePermission_model());
    }
    
    public function removeUserPermission() {
        echo json_encode($this->permission->removeUserPermission_model());
    }

    public function getModulePermission() {
        echo json_encode($this->permission->getModulePermission_model(array('userId' => $this->input->post('userId'), 'modId' => $this->input->post('modId'))));

    }
}
