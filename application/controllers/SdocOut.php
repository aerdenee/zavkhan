<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SdocOut extends CI_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('SmasterDocType_model', 'smasterDocType');
        $this->load->model('SdocOut_model', 'docOut');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeoplePosition_model', 'hrPeoplePosition');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SdocFile_model', 'sdocFile');

        $this->modId = 17;
    }

    public function index() {

        if ($this->session->isLogin === TRUE) {

            $header['jsFile'] = array('/assets/system/core/_docOut.js', '/assets/system/core/_docFile.js', '/assets/system/core/_docClose.js');
            $footer = array();

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
                $this->load->view(MY_ADMIN . '/docOut/index', $body);
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
            $totalRec = $this->docOut->listsCount_model(array(
                'auth' => $auth,
                'docTypeId' => $this->input->get('docTypeId'),
                'docNumber' => $this->input->get('docNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'toDepartmentId' => $this->input->get('toDepartmentId'),
                'toPartnerId' => $this->input->get('toPartnerId'),
                'toPeopleId' => $this->input->get('toPeopleId'),
                'keyword' => $this->input->get('keyword')));

            //get posts data
            $result = $this->docOut->lists_model(array(
                'auth' => $auth,
                'docTypeId' => $this->input->get('docTypeId'),
                'docNumber' => $this->input->get('docNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'toDepartmentId' => $this->input->get('toDepartmentId'),
                'toPartnerId' => $this->input->get('toPartnerId'),
                'toPeopleId' => $this->input->get('toPeopleId'),
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
            $body['row'] = $this->docOut->addFormData_model();
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));
            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'create',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $body['row']->created_user_id,
                'currentUserId' => $this->session->userdata['adminUserId']));
            
            if ((!$body['auth']->our->update and $body['row']->created_user_id == $this->session->userdata['adminUserId']) or (!$body['auth']->your->update and $body['row']->created_user_id != $this->session->userdata['adminUserId'])) {
            
                $body['controlDisabled'] = array('disabled' => true);
                $body['controlDisableValue'] = 'true';
            }
            
            $body['controlMasterDocTypeListDropdown'] = $this->smasterDocType->controlMasterDocTypeListDropdown_model(array('selectedId' => 0));

            $body['controlToHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'toDepartmentId[]',
                'onlyMyDepartment' => false,
                'selectedId' => $body['row']->to_department_id,
                'onchange' => '_toPartnerPeopleDocOutControl({elem:this})',
                'disabled' => $body['controlDisableValue']));

            $body['controlToPartnerDropdown'] = false;

            $body['controlToHrPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                    'name' => 'toPeopleId',
                    'selectedId' => $body['row']->to_people_id,
                    'departmentId' => $body['row']->to_department_id,
                    'disabled' => $body['controlDisableValue']));


            $body['controlFromHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
                'name' => 'fromDepartmentId',
                'selectedId' => $body['row']->from_department_id,
                'onlyMyDepartment' => true,
                'disabled' => $body['controlDisableValue']));

            $body['controlFromPartnerDropdown'] = false;

            $body['controlFromHrPeopleListDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
                'name' => 'fromPeopleId',
                'selectedId' => $body['row']->from_people_id,
                'departmentId' => $this->session->userdata['adminDepartmentId'],
                'disabled' => $body['controlDisableValue']));

            $body['initDocFile'] = $this->sdocFile->lists_model(array('docId' => $body['row']->doc_id, 'formId' => '#form-doc-out', 'disabled' => $body['controlDisableValue']));

            echo json_encode(array(
                'title' => $module->title . ' - нэмэх',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/docOut/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $body['controlDisabled'] = array();
            $body['controlDisableValue'] = 'false';
            $body['row'] = $this->docOut->editFormData_model(array('id' => $this->input->post('id')));
            $module = $this->module->getData_model(array('id' => $body['row']->mod_id));
            $body['auth'] = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'update',
                'moduleMenuId' => $this->input->post('moduleMenuId'),
                'createdUserId' => $body['row']->created_user_id,
                'currentUserId' => $this->session->userdata['adminUserId']));

            if ((!$body['auth']->our->update and $body['row']->created_user_id == $this->session->userdata['adminUserId']) or (!$body['auth']->your->update and $body['row']->created_user_id != $this->session->userdata['adminUserId'])) {
            
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
                'selectedId' => $body['row']->from_department_id,
                'onlyMyDepartment' => true,
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

            $body['initDocFile'] = $this->sdocFile->lists_model(array('docId' => $body['row']->doc_id, 'formId' => '#form-doc-out', 'disabled' => $body['controlDisableValue']));

            echo json_encode(array(
                'title' => $module->title . ' - засах',
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'width' => 800,
                'html' => $this->load->view(MY_ADMIN . '/docOut/form', $body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        echo json_encode($this->docOut->insert_model(array('getUID' => getUID('doc'))));
    }

    public function update() {

        echo json_encode($this->docOut->update_model());
    }

    public function delete() {

        echo json_encode($this->docOut->delete_model());
    }

    function searchForm() {

        $body['row'] = $this->docOut->addFormData_model();
        $module = $this->module->getData_model(array('id' => $body['row']->mod_id));

        $body['controlMasterDocTypeListDropdown'] = $this->smasterDocType->controlMasterDocTypeListDropdown_model(array('selectedId' => 0));

        $body['controlToHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array(
            'name' => 'toDepartmentId',
            'selectedId' => 0,
            'onchange' => '_searchToPartnerPeopleDocOutControl({elem:this})'));

        $body['controlToPartnerDropdown'] = false;

        $body['controlToHrPeopleListDropdown'] = false;

        echo json_encode(array(
            'title' => $module->title . ' - дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/docOut/formSearch', $body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

}
