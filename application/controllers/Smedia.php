<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Smedia extends CI_Controller {

    public static $path = "smedia/";

    function __construct() {
        parent::__construct();

        $this->load->model('Spage_model', 'page');
        $this->load->model('Smedia_model', 'media');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SlinkType_model', 'linkType');
        $this->load->model('SmasterMediaType_model', 'masterMediaType');
        
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $header['jsFile'] = array('/assets/system/core/_media.js');

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
                $this->load->view(MY_ADMIN . '/media/index', $body);
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

            //total rows count
            $totalRec = $this->media->listsCount_model(array(
                'auth' => $auth,
                'contId' => $this->input->get('contId'),
                'catId' => $this->input->get('catId'),
                'partnerId' => $this->input->get('partnerId'),
                'peopleId' => $this->input->get('peopleId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword')));

            $result = $this->media->lists_model(array(
                    'auth' => $auth,
                    'catId' => $this->input->get('catId'),
                    'partnerId' => $this->input->get('partnerId'),
                    'peopleId' => $this->input->get('peopleId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'keyword' => $this->input->get('keyword'),
                    'departmentId' => $this->input->get('departmentId'),
                    'rows' => $this->input->get('rows'),
                    'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));

        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $auth = authentication(array(
                'permission' => $this->session->authentication, 
                'role' => 'create', 
                'moduleMenuId' => $this->input->post('moduleMenuId'), 
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            $body['row'] = $this->media->addFormData_model(array('contId' => $this->input->post('contId'), 'modId' => $auth->modId));

            $module = $this->module->getData_model(array('id' => $auth->modId));

            $body['controlMasterMediaTypeRadioButton'] = $this->masterMediaType->controlMasterMediaTypeRadioButton_model(array(
                'selectedId' => $body['row']->media_type_id,
                'required' => true));

            $body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $auth->modId,
                'selectedId' => $body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $body['row']->partner_id));

            echo json_encode(array(
                'title' => $module->title . ' нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 1000,
                'html' => $this->load->view(MY_ADMIN . '/media/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->media->editFormData_model(array('selectedId' => $this->input->post('id')));

            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            $body['controlMasterMediaTypeRadioButton'] = $this->masterMediaType->controlMasterMediaTypeRadioButton_model(array(
                'selectedId' => $body['row']->media_type_id,
                'required' => true));

            $body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $body['row']->mod_id,
                'selectedId' => $body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => $body['row']->partner_id));

            echo json_encode(array(
                'title' => $module->title . ' засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 1000,
                'html' => $this->load->view(MY_ADMIN . '/media/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->media->insert_model());
    }

    public function update() {
        echo json_encode($this->media->update_model());
    }

    public function delete() {
        echo json_encode($this->media->delete_model(array('uploadPath' => UPLOADS_MEDIA_PATH, 'selectedId' => $this->input->post('id'))));
    }

    public function searchForm() {
        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->media->addFormData_model();

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));
            $body['row']->mod_id = $body['auth']->modId;

            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            $body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $body['row']->mod_id, 'selectedId' => 0, 'required' => true));
            $body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('selectedId' => 0));

            echo json_encode(array(
                'title' => $module->title . ' дэлгэрэнгүй хайлт',
                'btn_yes' => 'Хайх',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/media/formSearch', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

}
