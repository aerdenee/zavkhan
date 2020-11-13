<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SdocTransfer extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('Spage_model', 'page');
        $this->load->model('SmasterDocType_model', 'smasterDocType');
        $this->load->model('SdocIn_model', 'docIn');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SdocFile_model', 'sdocFile');
        $this->load->model('SdocTransfer_model', 'sdocTransfer');
        
        $this->modId = 92;
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $footer = array();
            $header['jsFile'] = array('/assets/system/editor/ckeditor/ckeditor.js', '/assets/system/core/_docTransfer.js');

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'isModule',
                'moduleMenuId' => $this->uri->segment(3),
                'createdUserId' => $this->session->userdata['adminUserId'],
                'currentUserId' => $this->session->userdata['adminUserId']));

            $body['module'] = $this->module->getData_model(array('id' => $body['auth']->modId));
            $header['pageMeta'] = $this->page->meta_model(array('pageTitle' => $body['module']->title));

            //load the view
            $this->load->view(MY_ADMIN . '/header', $header);
            if ($body['auth']->permission) {
                $this->load->view(MY_ADMIN . '/docTransfer/index', $body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $body);
            }
            $this->load->view(MY_ADMIN . '/footer', $footer);
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
            $totalRec = $this->sdocTransfer->listsCount_model(array(
                'auth' => $auth,
                'docTypeId' => $this->input->get('docTypeId'),
                'docNumber' => $this->input->get('docNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword')));

            //get posts data
            $result = $this->sdocTransfer->lists_model(array(
                'auth' => $auth,
                'docTypeId' => $this->input->get('docTypeId'),
                'docNumber' => $this->input->get('docNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'keyword' => $this->input->get('keyword'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data']));
        }
    }

    public function addDeleteList() {

        if ($this->session->isLogin === TRUE) {

            echo json_encode($this->sdocTransfer->addDeleteList_model(array('docDetialId' => $this->input->post('docDetialId'), 'disabled' => $this->input->post('disabled'))));
        }
    }

    public function add() {
        if ($this->session->isLogin === TRUE) {

            $body['docDetialId'] = $this->input->post('docDetialId');
            $body['modId'] = $this->modId;

            $body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'departmentId',
                'selectedId' => $this->session->userdata['adminDepartmentId']));

            $body['controlHrPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'peopleId',
                'selectedId' => 0,
                'departmentId' => $this->session->userdata['adminDepartmentId']));

            echo json_encode(array(
                'title' => 'Албан бичиг шилжүүлэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 400,
                'html' => $this->load->view(MY_ADMIN . '/docTransfer/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->sdocTransfer->editData_model(array('id' => $this->input->post('id')));

            $body['controlDisabled'] = array('disabled' => true);
            $body['controlDisableValue'] = 'true';

            echo json_encode(array(
                'title' => 'Албан бичгийн хариу төсөл',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/docTransfer/formEdit', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->sdocTransfer->insert_model());
    }

    public function update() {
        echo json_encode($this->sdocTransfer->update_model());
    }

    public function delete() {
        echo json_encode($this->sdocTransfer->delete_model(array('id' => $this->input->post('docTransferId'))));
    }

    public function printFile() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->sdocTransfer->editData_model(array('id' => $this->input->post('id')));
            echo json_encode(array(
                'title' => 'Хэвлэх',
                'html' => $this->load->view(MY_ADMIN . '/docTransfer/printFile', $body, TRUE)
            ));
        }
    }

    public function show() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->sdocTransfer->getData_model(array('selectedId' => $this->input->post('selectedId')));

            echo json_encode(array(
                'title' => 'Албан бичиг шилжүүлсэн байдал',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 600,
                'html' => $this->load->view(MY_ADMIN . '/docTransfer/show', $body, TRUE)
            ));
        }
    }

}
