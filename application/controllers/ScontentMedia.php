<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ScontentMedia extends CI_Controller {

    public static $path = "scontentmedia/";

    function __construct() {
        parent::__construct();

        $this->load->model('Spage_model', 'page');
        $this->load->model('ScontentMedia_model', 'contentMedia');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SmasterMediaType_model', 'masterMediaType');
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/_contentMedia.js');

            $this->load->view(MY_ADMIN . '/header', $this->header);
            $this->load->view(MY_ADMIN . '/contentMedia/index');
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

            echo json_encode($this->contentMedia->lists_model(array(
                    'auth' => $auth,
                    'contId' => $this->input->get('contId'),
                    'modId' => $this->input->get('modId'),
                    'catId' => $this->input->get('catId'),
                    'partnerId' => $this->input->get('partnerId'),
                    'authorId' => $this->input->get('authorId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'keyword' => $this->input->get('keyword'))));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->contentMedia->addFormData_model(array('contId' => $this->input->post('contId'), 'modId' => $this->input->post('modId')));

            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            $body['controlMasterMediaTypeRadioButton'] = $this->masterMediaType->controlMasterMediaTypeRadioButton_model(array('selectedId' => $body['row']->media_type_id, 'required' => true));

            echo json_encode(array(
                'title' => $module->title . ' медиа нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/contentMedia/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->contentMedia->editFormData_model(array('selectedId' => $this->input->post('id')));

            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            $body['controlMasterMediaTypeRadioButton'] = $this->masterMediaType->controlMasterMediaTypeRadioButton_model(array('selectedId' => $body['row']->media_type_id, 'required' => true));


            echo json_encode(array(
                'title' => $module->title . ' медиа засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/contentMedia/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }
    
    public function show() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->contentMedia->getData_model(array('selectedId' => $this->input->post('id')));

            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            echo json_encode(array(
                'title' => $module->title . ' медиа',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/contentMedia/show', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->contentMedia->insert_model());
    }

    public function update() {
        echo json_encode($this->contentMedia->update_model());
    }

    public function delete() {
        echo json_encode($this->contentMedia->delete_model(array('uploadPath' => UPLOADS_CONTENT_PATH, 'selectedId' => $this->input->post('id'))));
    }

    public function searchForm() {
        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->contentMedia->addFormData_model();

            $this->auth = authentication(array('authentication' => $this->session->authentication, 'moduleMenuId' => $this->input->post('moduleMenuId')));
            $this->body['row']->mod_id = $this->auth->modId;

            $this->module = $this->module->getData_model(array('id' => $this->auth->modId));

            $this->page->deny_model(array('moduleMenuId' => $this->input->post('moduleMenuId'), 'mode' => 'read', 'createdUserId' => $this->body['row']->created_user_id));

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array('modId' => $this->auth->modId, 'selectedId' => $this->body['row']->cat_id, 'required' => true));
            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array('allInId' => array($this->session->adminPartnerId), 'selectedId' => 0, 'required' => true));
            $this->body['controlAuthorDropdown'] = $this->user->controlUserDropDown_model(array('selectedId' => $this->session->adminUserId, 'name' => 'authorId'));
            $this->body['controlThemeLayoutRadio'] = $this->themelayout->controlThemeLayoutRadio_model(array('themeLayoutId' => 1, 'modId' => $this->auth->modId, 'isCategory' => 0));


            echo json_encode(array(
                'title' => $this->module->title . ' дэлгэрэнгүй хайлт',
                'btn_yes' => 'Хайх',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/contentMedia/formSearch', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

}
