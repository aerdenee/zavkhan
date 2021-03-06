<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsSendDocument extends CI_Controller {

    public static $path = "snifsSendDocument/";

    function __construct() {

        parent::__construct();

        $this->load->model('Spage_model', 'page');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('Sauthentication_model', 'authentication');
        $this->load->model('SnifsSendDocument_model', 'nifsSendDocument');
        $this->load->model('SnifsWork_model', 'nifsWork');
        $this->load->model('SnifsWhere_model', 'nifsWhere');
        $this->load->model('SnifsCrimeShortValue_model', 'nifsCrimeShortValue');
        $this->load->model('SnifsResearchType_model', 'nifsResearchType');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('SnifsCrimeType_model', 'nifsCrimeType');
        $this->load->model('Spartner_model', 'partner');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsIsMixx_model', 'nifsIsMixx');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('SnifsInjury_model', 'nifsInjury');
        $this->load->model('SnifsKeywords_model', 'nifsKeywords');
        $this->load->model('SnifsQuestion_model', 'nifsQuestion');
        $this->load->model('SnifsAnatomy_model', 'nifsAnatomy');
        $this->load->model('SnifsDoctorView_model', 'nifsDoctorView');
        $this->load->model('SnifsFileFolder_model', 'nifsFileFolder');



        $this->perPage = 2;
        $this->modId = 81;
        $this->hrPeoplePositionId = '5,6,7,13,28,16,27';
        $this->hrPeopleDepartmentId = '4,16,17,18';
        $this->nifsCrimeTypeId = 354;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 364;
        $this->nifsCloseTypeCatId = 530;
        $this->nifsWhereCatId = 378;
        $this->nifsMotiveCatId = 391;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        $this->hrPeopleExpertPositionId = '5,6,7,10,3,27,13';
        $this->hrPeopleExpertDepartmentId = '5';
        $this->hrPeopleExpertOnlyDepartment = ($this->session->adminDepartmentId == 7 ? 4 : $this->session->adminDepartmentId);
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->footer['jsFile'] = array('/assets/system/core/nifsSendDocument.js', '/assets/system/core/nifsIsMixx.js', '/assets/system/core/_hrPeople.js');

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
                $this->load->view(MY_ADMIN . '/nifsSendDocument/index', $this->body);
            } else {
                $this->load->view(MY_ADMIN . '/page/deny', $this->body);
            }
            $this->load->view(MY_ADMIN . '/footer', $this->footer);
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function oldLists() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {

            $this->auth = authentication(array(
                'permission' => $this->session->authentication,
                'role' => 'read',
                'moduleMenuId' => $this->input->get('moduleMenuId'),
                'createdUserId' => 0,
                'currentUserId' => $this->session->userdata['adminUserId']));

            //total rows count
            $totalRec = $this->nifsSendDocument->oldListsCount_model(array(
                'auth' => $this->auth,
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'createNumber' => $this->input->get('createNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'expertId' => $this->input->get('expertId'),
                'typeId' => $this->input->get('typeId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'questionId' => $this->input->get('questionId'),
                'statusId' => $this->input->get('statusId')));

            //get posts data
            $result = $this->nifsSendDocument->oldLists_model(array(
                'auth' => $this->auth,
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'createNumber' => $this->input->get('createNumber'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'expertId' => $this->input->get('expertId'),
                'typeId' => $this->input->get('typeId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'questionId' => $this->input->get('questionId'),
                'statusId' => $this->input->get('statusId'),
                'totalRows' => $totalRec,
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function oldCloseFrom() {

        $this->body['row'] = $this->nifsSendDocument->oldEditFormData_model(array('id' => $this->input->post('id')));
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' хаах';
        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => $this->body['row']->solution_id, 'catId' => $this->nifsSolutionCatId));
        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => $this->body['row']->close_type_id, 'catId' => $this->nifsCloseTypeCatId));
        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'html' => $this->load->view(MY_ADMIN . '/nifsSendDocument/oldCloseForm', $this->body, TRUE)
        ));
    }

    public function oldClose() {
        echo json_encode($this->nifsSendDocument->oldClose_model());
    }

    function oldSearchForm() {

        $this->body['row'] = $this->nifsSendDocument->oldAddFormData_model();
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - хайлт';

        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
            'allInId' => array($this->session->adminPartnerId),
            'selectedId' => 0));

        $this->body['controlExpertDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'expertId',
            'positionId' => $this->hrPeopleExpertPositionId,
            'selectedId' => 0,
            'departmentId' => $this->hrPeopleExpertDepartmentId));

        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => $this->body['row']->close_type_id, 'catId' => $this->nifsCloseTypeCatId));

        $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionDropDown_model(array(
            'selectedId' => 0));

        $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
            'name' => 'typeId',
            'selectedId' => 0,
            'catId' => $this->nifsCrimeTypeId));

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/nifsSendDocument/oldFormSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsSendDocument->addFormData_model(array('modId' => $this->input->post('modId'), 'contId' => $this->input->post('contId')));

            $this->body['row']->type_id = $this->input->post('typeId');

            $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionMultipleDropDown_model(array(
                'catId' => $this->nifsQuestionCatId,
                'modId' => $this->body['row']->mod_id,
                'contId' => 0,
                'name' => 'questionId[]',
                'initControlHtml' => 'initControlQuestionHtml'));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'positionId' => $this->hrPeopleExpertPositionId,
                'departmentId' => $this->body['row']->department_id/*$this->hrPeopleExpertDepartmentId*/,
                'modId' => $this->body['row']->mod_id,
                'contId' => 0,
                'isCurrenty' => 1,
                'initControlHtml' => 'initSendDocumentControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'name' => 'typeId',
                'selectedId' => 0,
                'catId' => $this->nifsCrimeTypeId));

            $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array(
                'selectedId' => $this->body['row']->solution_id,
                'catId' => $this->nifsSolutionCatId));

            $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array(
                'selectedId' => $this->body['row']->type_id,
                'catId' => $this->nifsCloseTypeCatId));

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->nifsSendDocument->controlHrPeopleDepartmentDropdown_model(array('selectedId' => $this->body['row']->department_id, 'disabled' => 'true'));

            echo json_encode(array(
                'title' => $this->nifsSendDocument->setSendDocumentWindowTitle_model(array('typeId' => $this->input->post('typeId'))),
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsSendDocument/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsSendDocument->editFormData_model(array('id' => $this->input->post('id')));
            $this->body['row']->type_id = $this->input->post('typeId');

            $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionMultipleDropDown_model(array(
                'catId' => $this->nifsQuestionCatId,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'name' => 'questionId[]',
                'initControlHtml' => 'initControlQuestionHtml'));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'positionId' => $this->hrPeopleExpertPositionId,
                'departmentId' => $this->hrPeopleExpertDepartmentId,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isCurrenty' => 1,
                'isLogView' => 1,
                'initControlHtml' => 'initSendDocumentControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'name' => 'typeId',
                'selectedId' => 0,
                'catId' => $this->nifsCrimeTypeId));

            $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array(
                'selectedId' => $this->body['row']->solution_id,
                'catId' => $this->nifsSolutionCatId));

            $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array(
                'selectedId' => $this->body['row']->type_id,
                'catId' => $this->nifsCloseTypeCatId));

            $this->body['controlHrPeopleDepartmentDropdown'] = $this->nifsSendDocument->controlHrPeopleDepartmentDropdown_model(array('selectedId' => $this->body['row']->department_id, 'disabled' => 'true'));

            echo json_encode(array(
                'title' => $this->nifsSendDocument->setSendDocumentWindowTitle_model(array('typeId' => $this->input->post('typeId'))),
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'btn_delete' => 'Устгах',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsSendDocument/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {
        echo json_encode($this->nifsSendDocument->insert_model(array('getUID' => getUID('nifs_send_doc'))));
    }

    public function update() {
        echo json_encode($this->nifsSendDocument->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsSendDocument->delete_model());
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

            //total rows count
            $totalRec = $this->nifsSendDocument->listsCount_model(array(
                'auth' => $this->auth,
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'typeId' => $this->input->get('typeId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'expertId' => $this->input->get('expertId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'questionId' => $this->input->get('questionId'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId')));

            //get posts data
            $result = $this->nifsSendDocument->lists_model(array(
                'auth' => $this->auth,
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'typeId' => $this->input->get('typeId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'expertId' => $this->input->get('expertId'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'questionId' => $this->input->get('questionId'),
                'departmentId' => $this->input->get('departmentId'),
                'statusId' => $this->input->get('statusId'),
                'totalRows' => $totalRec,
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function closeFrom() {

        $this->body['row'] = $this->nifsSendDocument->editFormData_model(array('id' => $this->input->post('id')));
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' хаах';
        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => $this->body['row']->solution_id, 'catId' => $this->nifsSolutionCatId));
        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => $this->body['row']->close_type_id, 'catId' => $this->nifsCloseTypeCatId));
        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'html' => $this->load->view(MY_ADMIN . '/nifsSendDocument/closeForm', $this->body, TRUE)
        ));
    }

    public function close() {
        echo json_encode($this->nifsSendDocument->close_model());
    }

    function searchForm() {

        $this->body['row'] = $this->nifsSendDocument->addFormData_model();

        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
            'allInId' => array($this->session->adminPartnerId),
            'selectedId' => 0));

        $this->body['controlExpertDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'expertId',
            'positionId' => $this->hrPeopleExpertPositionId,
            'selectedId' => 0,
            'departmentId' => $this->hrPeopleExpertDepartmentId));

        $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsMotiveCatId,
            'tabindex' => 2));

        $this->body['controlCrimeShortValueDropdown'] = $this->nifsCrimeShortValue->controlCrimeShortValueDropdown_model(array(
            'selectedId' => 0,
            'tabindex' => 16));

        $this->body['controlNifsWhereDropdown'] = $this->nifsWhere->controlNifsWhereDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsWhereCatId));

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
            'modId' => 0,
            'selectedId' => 0,
            'parentId' => 0,
            'space' => '',
            'counter' => 1,
            'required' => true));

        $this->body['controlWorkDropdown'] = $this->nifsWork->controlWorkDropdown_model(array(
            'selectedId' => 0,
            'tabindex' => 11));

        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('selectedId' => 0));

        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => $this->body['row']->close_type_id, 'catId' => $this->nifsCloseTypeCatId));

        $this->body['controlNifsQuestionDropDown'] = $this->nifsQuestion->controlNifsQuestionDropDown_model(array(
            'selectedId' => 0));

        $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
            'name' => 'typeId',
            'selectedId' => 0,
            'catId' => $this->nifsCrimeTypeId));

        echo json_encode(array(
            'title' => 'Дэлгэрэнгүй хайлт',
            'html' => $this->load->view(MY_ADMIN . '/nifsSendDocument/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function dataUpdate() {
        echo json_encode($this->nifsSendDocument->dataUpdate_model());
    }

    public function readResult() {

        $body = array();
        $resultTitle = 'Хариу';

        $body['row'] = $this->nifsSendDocument->readResult_model(array(
            'contId' => $this->input->post('contId'),
            'moduleId' => $this->input->post('moduleId'),
            'typeId' => $this->input->post('typeId')));

        if ($this->input->post('typeId') == 11) {
            $resultTitle = 'Химийн лабораторийн хариу';
        } else if ($this->input->post('typeId') == 8) {
            $resultTitle = 'Биологийн лабораторийн хариу';
        } else if ($this->input->post('typeId') == 10) {
            $resultTitle = 'Бактериологийн лабораторийн хариу';
        }

        if ($body['row']) {
            if ($body['row']->module_id == 50) {
                //Хавтаст хэрэг
                $body['orginal'] = $this->nifsFileFolder->getData_model(array('selectedId' => $body['row']->cont_id));
            } else if ($body['row']->module_id == 51) {
                //Эмчийн үзлэг
                $body['orginal'] = $this->nifsDoctorView->getData_model(array('selectedId' => $body['row']->cont_id));
            } else if ($body['row']->module_id == 52) {
                //Задлан шинжилгээ
                $body['orginal'] = $this->nifsAnatomy->getData_model(array('selectedId' => $body['row']->cont_id));
            }
            echo json_encode(array(
                'title' => $resultTitle,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsSendDocument/readResult', $body, TRUE)));
        } else {
            echo json_encode(array(
                'title' => $resultTitle,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'html' => $this->load->view(MY_ADMIN . '/nifsSendDocument/readEmpty', $body, TRUE)));
        }
    }

    public function getReportWorkInformationData() {
        echo json_encode($this->nifsSendDocument->getReportWorkInformationData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportWeightData() {
        echo json_encode($this->nifsSendDocument->getReportWeightData_model(array(
                    'reportIsClose' => $this->input->get('reportIsClose'),
                    'departmentId' => $this->input->get('departmentId'),
                    'inDate' => $this->input->get('inDate'),
                    'outDate' => $this->input->get('outDate'),
                    'reportModId' => $this->input->get('reportModId'),
                    'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function export() {

        $auth = authentication(array(
            'permission' => $this->session->authentication,
            'role' => 'export',
            'moduleMenuId' => $this->input->get('moduleMenuId'),
            'createdUserId' => 0,
            'currentUserId' => $this->session->userdata['adminUserId']));
        $module = $this->module->getData_model(array('id' => $auth->modId));

        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
        require APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator($this->session->adminFullName);
        $objPHPExcel->getProperties()->setLastModifiedBy();
        $objPHPExcel->getProperties()->setTitle(DEFAULT_ORGANIZATION);
        $objPHPExcel->getProperties()->setSubject(DEFAULT_SYSTEM_NAME);
        $objPHPExcel->getProperties()->setDescription($module->title . ' шинжилгээний бүртгэл');
        $objPHPExcel->getActiveSheet()->setTitle($module->title);

        $objPHPExcel->setActiveSheetIndex(0);

        $this->styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1')->setCellValue('A1', $module->title . ' (' . base_url() . ')');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2')->setCellValue('A2', date('Y оны m сарын d'));
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

        $data = $this->nifsSendDocument->export_model(array(
            'auth' => $auth,
            'createNumber' => $this->input->get('createNumber'),
            'keywordTypeId' => $this->input->get('keywordTypeId'),
            'keyword' => $this->input->get('keyword'),
            'typeId' => $this->input->get('typeId'),
            'inDate' => $this->input->get('inDate'),
            'outDate' => $this->input->get('outDate'),
            'closeInDate' => $this->input->get('closeInDate'),
            'closeOutDate' => $this->input->get('closeOutDate'),
            'expertId' => $this->input->get('expertId'),
            'closeTypeId' => $this->input->get('closeTypeId'),
            'questionId' => $this->input->get('questionId'),
            'statusId' => $this->input->get('statusId')));
//        if ($data) {
//
//            foreach ($data as $key => $row) {
//                echo '<pre>';
//                var_dump($row);
//                echo '</pre>';
//            }
//        }

        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4')->setCellValue('A3', '№');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4')->setCellValue('B3', 'Журнал №');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:D3')->setCellValue('C3', 'Бүртгэсэн огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:G3')->setCellValue('E3', 'Бүртгэл');
        $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Бүртгэл');
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Дугаар');
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Шинжээч');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:H4')->setCellValue('H3', 'Тогтоол ирүүлсэн байгууллага');
        $objPHPExcel->getActiveSheet()->mergeCells('I3:I4')->setCellValue('I3', 'Албан тушаалтаны нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:J4')->setCellValue('J3', 'Болсон хэргийн тухай');
        $objPHPExcel->getActiveSheet()->mergeCells('K3:K4')->setCellValue('K3', 'Шинжлүүлэгч');
        $objPHPExcel->getActiveSheet()->mergeCells('L3:M3')->setCellValue('L3', 'Объект');
        $objPHPExcel->getActiveSheet()->setCellValue('L4', 'Тоо');
        $objPHPExcel->getActiveSheet()->setCellValue('M4', 'Объект');
        $objPHPExcel->getActiveSheet()->mergeCells('N3:N4')->setCellValue('N3', 'Асуулт');
        $objPHPExcel->getActiveSheet()->mergeCells('O3:O4')->setCellValue('O3', 'Эмч');
        $objPHPExcel->getActiveSheet()->mergeCells('P3:P4')->setCellValue('P3', 'Лаб');
        $objPHPExcel->getActiveSheet()->mergeCells('Q3:Q4')->setCellValue('Q3', 'Хариу');

        $objPHPExcel->getActiveSheet()->getStyle('A3:Q4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:Q4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A3:Q4')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '085f35')
                    )
                )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('ffffff');

        $objPHPExcel->getActiveSheet()->getStyle('A3:Q4')->getFont()->setColor($phpColor);

        $i = 4;
        $j = 0;

        if ($data) {

            foreach ($data as $key => $row) {
                $i++;
                $j++;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $j);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row['create_number']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row['in_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row['out_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row['dir_type']);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $row['dir_create_number']);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row['dir_expert']);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row['partner']);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row['dir_full_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $row['crime_value']);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row['dir_full_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $row['object_count']);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $row['send_object']);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $row['question']);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $row['expert']);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $row['lab']);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $row['close_type']);
            }
        }



        $objPHPExcel->getActiveSheet()->getStyle('A3:Q' . $i)->applyFromArray($this->styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:Q' . $i)->getFont()->setName('Arial');
        $objPHPExcel->getActiveSheet()->getStyle('A1:Q' . $i)->getFont()->setSize(10);

        $objPHPExcel->getActiveSheet()->getStyle('A4:Q' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

        foreach ($objPHPExcel->getActiveSheet()->getColumnDimension() as $col) {
            $col->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();

        $this->fileName = $module->title . ' ' . date('YmdHis') . '.xlsx';
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename=' . $this->fileName);
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        exit();
    }

}
