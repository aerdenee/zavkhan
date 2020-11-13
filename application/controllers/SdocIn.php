<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SdocIn extends CI_Controller {

    public static $path = "sdocIn/";

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
        

        $this->modId = 17;
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $footer = array();
            $header['jsFile'] = array('/assets/system/core/_docIn.js', '/assets/system/core/_docFile.js', '/assets/system/core/_docClose.js', '/assets/system/core/_docTransfer.js');

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
                $this->load->view(MY_ADMIN . '/docIn/index', $body);
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
            $totalRec = $this->docIn->listsCount_model(array(
                'auth' => $auth,
                'docTypeId' => $this->input->get('docTypeId'),
                'docNumber' => $this->input->get('docNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'fromDepartmentId' => $this->input->get('fromDepartmentId'),
                'fromPartnerId' => $this->input->get('fromPartnerId'),
                'fromPeopleId' => $this->input->get('fromPeopleId'),
                'keyword' => $this->input->get('keyword')));

            //get posts data
            $result = $this->docIn->lists_model(array(
                'auth' => $auth,
                'docTypeId' => $this->input->get('docTypeId'),
                'docNumber' => $this->input->get('docNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'fromDepartmentId' => $this->input->get('fromDepartmentId'),
                'fromPartnerId' => $this->input->get('fromPartnerId'),
                'fromPeopleId' => $this->input->get('fromPeopleId'),
                'keyword' => $this->input->get('keyword'),
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $body['controlDisabled'] = array();
            $body['controlDisableValue'] = 'false';
            $body['row'] = $this->docIn->addFormData_model();
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));
            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'create',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $body['row']->created_user_id,
                'currentUserId' => $this->session->userdata['adminUserId']));

            if ((!$body['auth']->our->update and $body['row']->created_user_id == $this->session->userdata['adminUserId']) or ( !$body['auth']->your->update and $body['row']->created_user_id != $this->session->userdata['adminUserId'])) {

                $body['controlDisabled'] = array('disabled' => true);
                $body['controlDisableValue'] = 'true';
            }

            $body['controlMasterDocTypeListDropdown'] = $this->smasterDocType->controlMasterDocTypeListDropdown_model(array('selectedId' => 0));

            $body['controlToHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'toDepartmentId',
                'selectedId' => $body['row']->to_department_id,
                'disabled' => $body['controlDisableValue']));

            $body['controlToPartnerDropdown'] = false;

            $body['controlToHrPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'toPeopleId',
                'selectedId' => $body['row']->to_people_id,
                'departmentId' => $body['row']->to_department_id,
                'disabled' => $body['controlDisableValue']));


            $body['controlFromHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'fromDepartmentId',
                'onlyMyDepartment' => false,
                'selectedId' => $body['row']->from_department_id,
                'disabled' => $body['controlDisableValue']));

            $body['controlFromPartnerDropdown'] = false;

            $body['controlFromHrPeopleListDropdown'] = false;

            $body['initDocFile'] = $this->sdocFile->lists_model(array('docId' => $body['row']->doc_id, 'formId' => '#form-doc-in', 'disabled' => $body['controlDisableValue']));
            
            $body['initDocTransfer'] = $this->sdocTransfer->addDeleteList_model(array('docDetialId' => $body['row']->id, 'formId' => '#form-doc-in', 'disabled' => $body['controlDisableValue']));

            echo json_encode(array(
                'title' => $module->title . ' - нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/docIn/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $body['controlDisabled'] = array();
            $body['controlDisableValue'] = 'false';
            $body['row'] = $this->docIn->editFormData_model(array('id' => $this->input->post('id')));
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'update',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $body['row']->created_user_id,
                'currentUserId' => $this->session->userdata['adminUserId']));

            if ((!$body['auth']->our->update and $body['row']->created_user_id == $this->session->userdata['adminUserId']) or ( !$body['auth']->your->update and $body['row']->created_user_id != $this->session->userdata['adminUserId'])) {

                $body['controlDisabled'] = array('disabled' => true);
                $body['controlDisableValue'] = 'true';
            }

            $body['controlMasterDocTypeListDropdown'] = $this->smasterDocType->controlMasterDocTypeListDropdown_model(array(
                'selectedId' => $body['row']->doc_type_id,
                'disabled' => $body['controlDisableValue']));

            $body['controlToHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'toDepartmentId',
                'selectedId' => $body['row']->to_department_id,
                'disabled' => $body['controlDisableValue']));

            if ($body['row']->to_partner_id != 0) {
                $body['controlToPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                    'name' => 'toPartnerId',
                    'selectedId' => $body['row']->to_partner_id,
                    'disabled' => $body['controlDisableValue']));
            } else {
                $body['controlToPartnerDropdown'] = false;
            }

            if ($body['row']->to_people_id != 0) {
                $body['controlToHrPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                    'name' => 'toPeopleId',
                    'selectedId' => $body['row']->to_people_id,
                    'departmentId' => $body['row']->to_department_id,
                    'disabled' => $body['controlDisableValue']));
            } else {
                $body['controlToHrPeopleListDropdown'] = false;
            }


            $body['controlFromHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'fromDepartmentId',
                'onlyMyDepartment' => false,
                'selectedId' => $body['row']->from_department_id,
                'disabled' => $body['controlDisableValue']));

            if ($body['row']->from_partner_id != 0) {
                $body['controlFromPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                    'name' => 'fromPartnerId',
                    'selectedId' => $body['row']->from_partner_id,
                    'disabled' => $body['controlDisableValue']));
            } else {
                $body['controlFromPartnerDropdown'] = false;
            }

            if ($body['row']->from_people_id != 0) {
                $body['controlFromHrPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                    'name' => 'fromPeopleId',
                    'selectedId' => $body['row']->from_people_id,
                    'departmentId' => $body['row']->from_department_id,
                    'disabled' => $body['controlDisableValue']));
            } else {
                $body['controlFromHrPeopleListDropdown'] = false;
            }

            $body['initDocFile'] = $this->sdocFile->lists_model(array('docId' => $body['row']->doc_id, 'formId' => '#form-doc-in', 'disabled' => $body['controlDisableValue']));

            $body['initDocTransfer'] = $this->sdocTransfer->addDeleteList_model(array('docDetialId' => $body['row']->id, 'formId' => '#form-doc-in', 'disabled' => ($body['auth']->our->update ? 'false' : 'true')));
            
            /**Албан бичгийг уншсэн тэмдэглэгээ**/
            $this->docIn->isRead_model();
            
            echo json_encode(array(
                'title' => $module->title . ' - засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/docIn/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        echo json_encode($this->docIn->insert_model(array('getUID' => getUID('doc'))));
    }

    public function update() {

        echo json_encode($this->docIn->update_model());
    }

    public function delete() {

        echo json_encode($this->docIn->delete_model());
    }

    function searchForm() {

        $body['controlDisabled'] = array();
        $body['row'] = $this->docIn->addFormData_model();
        $module = $this->module->getData_model(array('id' => $body['row']->mod_id));
        if ($body['row']->department_id != $this->session->userdata['adminDepartmentId']) {
            $body['controlDisabled'] = array('disabled' => true);
        }

        $body['controlMasterDocTypeListDropdown'] = $this->smasterDocType->controlMasterDocTypeListDropdown_model(array('selectedId' => 0));

        $body['controlFromHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
            'name' => 'fromDepartmentId',
            'onlyMyDepartment' => false,
            'selectedId' => 0,
            'disabled' => ($body['row']->from_department_id == $this->session->userdata['adminDepartmentId'] ? 'false' : 'true')));

        $body['controlFromPartnerDropdown'] = false;

        $body['controlFromHrPeopleListDropdown'] = false;

        echo json_encode(array(
            'title' => $module->title . ' - дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/docIn/formSearch', $body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function printBlank() {

        if ($this->session->isLogin === TRUE) {

            $body['row'] = $this->docIn->editFormData_model(array('id' => $this->input->post('id')));

            $body['row']->docType = $this->smasterDocType->getData_model(array('selectedId' => $body['row']->doc_type_id));
            $body['row']->department = $this->hrPeopleDepartment->getData_model(array('selectedId' => $body['row']->from_department_id));
            $body['row']->people = $this->hrPeople->getData_model(array('selectedId' => $this->session->userdata['adminPeopleId']));

            echo json_encode(array(
                'title' => 'Албан бичиг хэвлэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/docIn/printBlank', $body, TRUE)
            ));
        }
    }

}
