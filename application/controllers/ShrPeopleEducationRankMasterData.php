<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShrPeopleEducationRankMasterData extends CI_Controller {

    public static $path = "shrPeopleEducationRankMasterData/";

    function __construct() {
        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ShrPeopleRank_model', 'hrPeopleRank');
        $this->load->model('ShrPeopleEducationRankMasterData_model', 'hrPeopleEducationRankMasterData');

        $this->perPage = 2;
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/hrPeopleAcceptModule.js');

            //load the view
            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/hrPeopleAcceptModule/index', $this->body);
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
            $totalRec = $this->hrPeopleAcceptModule->listsCount_model(array(
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
            $config['link_func'] = '_initHrPeopleAcceptModule';
            $this->ajax_pagination->initialize($config);
            //get posts data
            echo json_encode($this->hrPeopleAcceptModule->lists_model(array(
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
            //$this->load->view(MY_ADMIN . '/hrPeopleAcceptModule/lists', $data, false);
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->hrPeopleAcceptModule->addFormData_model();

            $this->modData = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'insert', 'createdUserId' => $this->body['row']->created_user_id));
            
            $this->body['controlModuleListDropdown'] = $this->module->controlModuleListDropdown_model(array('selectedId' => 0));
            
            echo json_encode(array(
                'title' => $this->modData->title . ' - Нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeopleAcceptModule/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->hrPeopleAcceptModule->editFormData_model(array('id' => $this->input->post('id')));

            $this->modData = $this->module->getData_model(array('id' => $this->body['row']->mod_id));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'insert', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlModuleListDropdown'] = $this->module->controlModuleListDropdown_model(array('selectedId' => $this->body['row']->module_id));
            
            echo json_encode(array(
                'title' => $this->modData->title . ' - Засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/hrPeopleAcceptModule/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->hrPeopleAcceptModule->insert_model());
    }

    public function update() {
        echo json_encode($this->hrPeopleAcceptModule->update_model());
    }

    public function delete() {
        echo json_encode($this->hrPeopleAcceptModule->delete_model());
    }

    public function controlHrPeopleEducationRankMasterDataDropDown() {
        echo json_encode($this->hrPeopleEducationRankMasterData->controlHrPeopleEducationRankMasterDataDropDown_model(array(
                    'parentId' => $this->input->post('parentId'),
                    'selectedId' => $this->input->post('selectedId'),
                    'name' => $this->input->post('name'),
                    'disabled' => $this->input->post('disabled'),
                    'readonly' => $this->input->post('readonly'),
                    'required' => $this->input->post('required'))));
    }

    function searchForm() {
        $this->body['row'] = $this->hrPeopleAcceptModule->addFormData_model();

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/hrPeopleAcceptModule/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

}
