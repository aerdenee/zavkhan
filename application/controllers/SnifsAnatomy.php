<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SnifsAnatomy extends CI_Controller {

    public static $path = "snifsanatomy/";

    function __construct() {

        parent::__construct();
        $this->load->model('Spage_model', 'page');
        $this->load->model('Smodule_model', 'module');
        $this->load->model('Scategory_model', 'category');
        $this->load->model('SnifsAnatomy_model', 'nifsAnatomy');
        $this->load->model('SnifsMotive_model', 'nifsMotive');
        $this->load->model('SnifsWork_model', 'nifsWork');
        $this->load->model('SnifsWhere_model', 'nifsWhere');
        $this->load->model('SnifsCrimeType_model', 'nifsCrimeType');
        $this->load->model('SnifsCrimeShortValue_model', 'nifsCrimeShortValue');
        $this->load->model('SnifsCloseType_model', 'nifsCloseType');
        $this->load->model('SnifsSolution_model', 'nifsSolution');
        $this->load->model('ShrPeopleDepartment_model', 'hrPeopleDepartment');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('SnifsIsMixx_model', 'nifsIsMixx');
        $this->load->model('SnifsDoctorView_model', 'nifsDoctorView');
        $this->load->model('SnifsStatus_model', 'nifsStatus');
        $this->load->model('ShrPeopleSex_model', 'hrPeopleSex');
        $this->load->model('SnifsKeywords_model', 'nifsKeywords');


        $this->perPage = 2;
        $this->hrPeoplePositionId = '5,6,7,13,27';
        $this->hrPeopleDepartmentId = '4,16,17,18';

        $this->nifsCrimeTypeId = 357;
        $this->nifsQuestionCatId = 371;
        $this->nifsSolutionCatId = 363;
        $this->nifsCloseTypeCatId = 369;
        $this->nifsWhereCatId = 377;
        $this->nifsResearchTypeCatId = 384;
        $this->nifsMotiveCatId = 390;
        $this->modId = 52;
        $this->nifsDepartmentId = getDepartmentId(array('userDepartmentId' => $this->session->adminDepartmentId, 'modId' => $this->modId));
        if ($this->nifsDepartmentId == 7) {
            $this->nifsDepartmentId = 4;
        }
        $this->hrPeopleExpertOnlyDepartment = ($this->session->adminDepartmentId == 7 ? 4 : $this->session->adminDepartmentId);
        $this->header = $this->body = $this->footer = array();
    }

    public function index() {

        $this->urlString = '';
        if ($this->session->isLogin === TRUE) {
            $this->header['cssFile'] = array();
            $this->header['jsFile'] = array('/assets/system/core/nifsAnatomy.js', '/assets/system/core/nifsIsMixx.js', '/assets/system/core/nifsSendDocument.js', '/assets/system/core/nifsQuestion.js', '/assets/system/core/_hrPeople.js');

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
                $this->load->view(MY_ADMIN . '/nifsAnatomy/index', $this->body);
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

            //total rows count
            $totalRec = $this->nifsAnatomy->listsCount_model(array(
                'auth' => $this->auth,
                'selectedId' => $this->input->get('selectedId'),
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'shortValueId' => $this->input->get('shortValueId'),
                'workId' => $this->input->get('workId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'crimeDate' => $this->input->get('crimeDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'short_value' => $this->input->get('shortValue'),
                'crimeShortValueId' => $this->input->get('crimeShortValueId'),
                'expertId' => $this->input->get('expertId'),
                'whereId' => $this->input->get('whereId'),
                'catId' => $this->input->get('catId'),
                'payment' => $this->input->get('payment'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'departmentId' => $this->input->get('departmentId'),
                'solutionId' => $this->input->get('solutionId'),
                'isMixx' => $this->input->get('isMixx'),
                'closeDate' => $this->input->get('closeDate'),
                'statusId' => $this->input->get('statusId'),
                'sex' => $this->input->get('sex'),
                'closeDescription' => $this->input->get('closeDescription')));

            //get posts data
            $result = $this->nifsAnatomy->lists_model(array(
                'auth' => $this->auth,
                'selectedId' => $this->input->get('selectedId'),
                'catId' => $this->input->get('catId'),
                'createNumber' => $this->input->get('createNumber'),
                'keywordTypeId' => $this->input->get('keywordTypeId'),
                'keyword' => $this->input->get('keyword'),
                'shortValueId' => $this->input->get('shortValueId'),
                'workId' => $this->input->get('workId'),
                'motiveId' => $this->input->get('motiveId'),
                'partnerId' => $this->input->get('partnerId'),
                'inDate' => $this->input->get('inDate'),
                'outDate' => $this->input->get('outDate'),
                'protocolInDate' => $this->input->get('protocolInDate'),
                'protocolOutDate' => $this->input->get('protocolOutDate'),
                'closeInDate' => $this->input->get('closeInDate'),
                'closeOutDate' => $this->input->get('closeOutDate'),
                'crimeDate' => $this->input->get('crimeDate'),
                'age1' => $this->input->get('age1'),
                'age2' => $this->input->get('age2'),
                'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
                'crimeShortValueId' => $this->input->get('crimeShortValueId'),
                'short_value' => $this->input->get('shortValue'),
                'expertId' => $this->input->get('expertId'),
                'whereId' => $this->input->get('whereId'),
                'catId' => $this->input->get('catId'),
                'payment' => $this->input->get('payment'),
                'closeTypeId' => $this->input->get('closeTypeId'),
                'departmentId' => $this->input->get('departmentId'),
                'solutionId' => $this->input->get('solutionId'),
                'isMixx' => $this->input->get('isMixx'),
                'closeDate' => $this->input->get('closeDate'),
                'statusId' => $this->input->get('statusId'),
                'sex' => $this->input->get('sex'),
                'closeDescription' => $this->input->get('closeDescription'),
                'totalRows' => $totalRec,
                'rows' => $this->input->get('rows'),
                'page' => $this->input->get('page')));

            echo json_encode(array('total' => $totalRec, 'rows' => $result['data'], 'search' => $result['search']));
        }
    }

    public function add() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsAnatomy->addFormData_model();
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - нэмэх';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'selectedId' => $this->body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1,
                'required' => 'true'));

            $this->body['controlWorkDropdown'] = $this->nifsWork->controlWorkDropdown_model(array(
                'selectedId' => $this->body['row']->work_id,
                'required' => 'true'));

            $this->body['controlNifsWhereDropdown'] = $this->nifsWhere->controlNifsWhereDropdown_model(array(
                'selectedId' => $this->body['row']->where_id,
                'catId' => $this->nifsWhereCatId));

            $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'name' => 'typeId',
                'selectedId' => $this->body['row']->type_id,
                'catId' => $this->nifsCrimeTypeId));

            $this->body['controlCrimeShortValueDropdown'] = $this->nifsCrimeShortValue->controlCrimeShortValueDropdown_model(array(
                'selectedId' => $this->body['row']->short_value_id));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => $this->body['row']->partner_id));

            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
                'selectedId' => $this->body['row']->motive_id,
                'catId' => $this->nifsMotiveCatId));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'positionId' => $this->hrPeoplePositionId,
                'departmentId' => $this->hrPeopleExpertOnlyDepartment,
                'selectedId' => 0,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isMixx' => $this->body['row']->is_mixx,
                'researchTypeId' => $this->body['row']->research_type_id,
                'isCurrenty' => 1,
                'isRead' => 0,
                'addFunction' => '_addNifsAnatomyExpert({elem:this, initControlHtml: \'initAnatomyControlExpertHtml\', departmentId: \'' . $this->hrPeopleDepartmentId . '\', positionId: \'' . $this->hrPeoplePositionId . '\', isExtraValue: \'true\'});',
                'removeFunction' => '_removeNifsAnatomyExpert({elem:this, initControlHtml: \'initAnatomyControlExpertHtml\'});',
                'addButtonName' => 'addNifsAnatomyExpertButton',
                'removeButtonName' => 'removeNifsAnatomyExpertButton',
                'initControlHtml' => 'initAnatomyControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array(
                'isMixx' => $this->body['row']->is_mixx,
                'initControlHtml' => 'initAnatomyControlExpertHtml',
                'addButtonName' => 'addNifsAnatomyExpertButton'));

            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsAnatomy/form', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function edit() {

        if ($this->session->isLogin === TRUE) {

            $this->body['row'] = $this->nifsAnatomy->editFormData_model(array('id' => $this->input->post('id')));
            $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
            $this->body['row']->module_title = $this->module->title . ' - засах';

            $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
                'modId' => $this->body['row']->mod_id,
                'selectedId' => $this->body['row']->cat_id,
                'parentId' => 0,
                'space' => '',
                'counter' => 1));

            $this->body['controlWorkDropdown'] = $this->nifsWork->controlWorkDropdown_model(array(
                'selectedId' => $this->body['row']->work_id));

            $this->body['controlNifsWhereDropdown'] = $this->nifsWhere->controlNifsWhereDropdown_model(array(
                'selectedId' => $this->body['row']->where_id,
                'catId' => $this->nifsWhereCatId));

            $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
                'name' => 'typeId',
                'selectedId' => $this->body['row']->type_id,
                'catId' => $this->nifsCrimeTypeId));

            $this->body['controlCrimeShortValueDropdown'] = $this->nifsCrimeShortValue->controlCrimeShortValueDropdown_model(array(
                'selectedId' => $this->body['row']->short_value_id));

            $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
                'allInId' => array($this->session->adminPartnerId),
                'selectedId' => $this->body['row']->partner_id));

            $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
                'selectedId' => $this->body['row']->motive_id,
                'catId' => $this->nifsMotiveCatId));

            $this->body['controlHrPeopleExpertMultiListDropdown'] = $this->hrPeople->controlHrPeopleMultiListDropdown_model(array(
                'name' => 'expertId[]',
                'positionId' => $this->hrPeoplePositionId,
                'departmentId' => $this->hrPeopleExpertOnlyDepartment,
                'selectedId' => 0,
                'modId' => $this->body['row']->mod_id,
                'contId' => $this->body['row']->id,
                'isMixx' => $this->body['row']->is_mixx,
                'researchTypeId' => $this->body['row']->research_type_id,
                'isCurrenty' => 1,
                'isRead' => 0,
                'addFunction' => '_addNifsAnatomyExpert({elem:this, initControlHtml: \'initAnatomyControlExpertHtml\', departmentId: \'' . $this->hrPeopleDepartmentId . '\', positionId: \'' . $this->hrPeoplePositionId . '\', isExtraValue: \'true\'});',
                'removeFunction' => '_removeNifsAnatomyExpert({elem:this, initControlHtml: \'initAnatomyControlExpertHtml\'});',
                'addButtonName' => 'addNifsAnatomyExpertButton',
                'removeButtonName' => 'removeNifsAnatomyExpertButton',
                'initControlHtml' => 'initAnatomyControlExpertHtml',
                'isExtraValue' => 'true'));

            $this->body['controlNifsIsMixxCheckBox'] = $this->nifsIsMixx->controlNifsIsMixxCheckBox_model(array(
                'isMixx' => $this->body['row']->is_mixx,
                'initControlHtml' => 'initAnatomyControlExpertHtml',
                'addButtonName' => 'addNifsAnatomyExpertButton'));


            echo json_encode(array(
                'title' => $this->body['row']->module_title,
                'btn_yes' => 'Хадгалах',
                'btn_no' => 'Хаах',
                'btn_save_close' => 'Хадгалах, хаалт',
                'departmentId' => $this->nifsDepartmentId,
                'html' => $this->load->view(MY_ADMIN . '/nifsAnatomy/formEdit', $this->body, TRUE)
            ));
        } else {
            redirect(MY_ADMIN);
        }
    }

    public function insert() {

        $this->nifsKeywords->insert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('shortValue')));

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('expertName')));

        echo json_encode($this->nifsAnatomy->insert_model(array('getUID' => getUID('nifs_anatomy'))));
    }

    public function update() {

        $this->nifsKeywords->insert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('shortValue')));

        $this->nifsKeywords->agentNameInsert_model(array(
            'modId' => $this->input->post('modId'),
            'departmentId' => $this->nifsDepartmentId,
            'keyword' => $this->input->post('expertName')));

        echo json_encode($this->nifsAnatomy->update_model());
    }

    public function delete() {
        echo json_encode($this->nifsAnatomy->delete_model());
    }

    public function closeFrom() {
        $this->body['row'] = $this->nifsAnatomy->editFormData_model(array('id' => $this->input->post('id')));
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' хаах';
        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => $this->body['row']->solution_id, 'catId' => $this->nifsSolutionCatId));
        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => $this->body['row']->close_type_id, 'catId' => $this->nifsCloseTypeCatId));

        echo json_encode(array(
            'title' => 'Шинжилгээ хаах',
            'btn_yes' => 'Хадгалах',
            'btn_no' => 'Хаах',
            'html' => $this->load->view(MY_ADMIN . '/nifsAnatomy/closeForm', $this->body, TRUE)
        ));
    }

    public function close() {
        echo json_encode($this->nifsAnatomy->close_model());
    }

    function searchForm() {

        $this->body['row'] = $this->nifsAnatomy->addFormData_model();
        $this->module = $this->module->getData_model(array('id' => $this->body['row']->mod_id));
        $this->body['row']->module_title = $this->module->title . ' - хайлт';

        $this->body['controlCategoryListDropdown'] = $this->category->controlCategoryListDropdown_model(array(
            'modId' => $this->body['row']->mod_id,
            'selectedId' => 0,
            'parentId' => 0,
            'space' => '',
            'counter' => 1,
            'required' => 'true'));

        $this->body['controlWorkDropdown'] = $this->nifsWork->controlWorkDropdown_model(array(
            'selectedId' => 0,
            'required' => 'true'));

        $this->body['controlNifsWhereDropdown'] = $this->nifsWhere->controlNifsWhereDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsWhereCatId));

        $this->body['controlNifsCrimeTypeDropdown'] = $this->nifsCrimeType->controlNifsCrimeTypeDropdown_model(array(
            'name' => 'typeId',
            'selectedId' => 0,
            'catId' => $this->nifsCrimeTypeId));

        $this->body['controlCrimeShortValueDropdown'] = $this->nifsCrimeShortValue->controlCrimeShortValueDropdown_model(array(
            'selectedId' => 0));

        $this->body['controlPartnerDropdown'] = $this->partner->controlPartnerDropdown_model(array(
            'allInId' => array($this->session->adminPartnerId),
            'selectedId' => 0));

        $this->body['controlExpertDropdown'] = $this->hrPeople->controlHrPeopleListDropdown_model(array(
            'name' => 'expertId',
            'positionId' => $this->hrPeoplePositionId,
            'departmentId' => $this->hrPeopleDepartmentId,
            'selectedId' => 0,
            'isExtraValue' => 'true'));

        $this->body['controlNifsMotiveDropdown'] = $this->nifsMotive->controlNifsMotiveDropdown_model(array(
            'selectedId' => 0,
            'catId' => $this->nifsMotiveCatId));

        $this->body['controlNifsIsMixxDropdown'] = $this->nifsIsMixx->controlNifsIsMixxDropdown_model(array('selectedId' => 0));

        $this->body['controlNifsSolutionDropdown'] = $this->nifsSolution->controlNifsSolutionDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsSolutionCatId));

        $this->body['controlNifsCloseTypeDropdown'] = $this->nifsCloseType->controlNifsCloseTypeDropdown_model(array('selectedId' => 0, 'catId' => $this->nifsCloseTypeCatId));

        $this->body['controlNifsStatusDropdown'] = $this->nifsStatus->controlNifsStatusDropdown_model(array('selectedId' => 0));

        $this->body['controlSexListDropdown'] = $this->hrPeopleSex->controlSexListDropdown_model(array('selectedId' => 0));

        $this->body['controlHrPeopleDepartmentDropdown'] = $this->hrPeopleDepartment->controlHrPeopleDepartmentDropdown_model(array('name' => 'departmentId', 'modId' => 69, 'selectedId' => 0));

        echo json_encode(array(
            'title' => $this->body['row']->module_title,
            'html' => $this->load->view(MY_ADMIN . '/nifsAnatomy/formSearch', $this->body, TRUE),
            'btn_yes' => 'Хайх',
            'btn_no' => 'Болих'
        ));
    }

    public function getReportWorkInformationData() {
        echo json_encode($this->nifsAnatomy->getReportWorkInformationData_model(array(
            'reportIsClose' => $this->input->get('reportIsClose'),
            'departmentId' => $this->input->get('departmentId'), 
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportWeightData() {
        echo json_encode($this->nifsAnatomy->getReportWeightData_model(array(
            'reportIsClose' => $this->input->get('reportIsClose'),
            'departmentId' => $this->input->get('departmentId'), 
            'inDate' => $this->input->get('inDate'), 
            'outDate' => $this->input->get('outDate'), 
            'reportModId' => $this->input->get('reportModId'), 
            'reportMenuId' => $this->input->get('reportMenuId'))));
    }

    public function getReportPartnerData() {
        echo json_encode($this->nifsAnatomy->getReportPartnerData_model(array(
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

        $data = $this->nifsAnatomy->export_model(array(
            'auth' => $auth,
            'catId' => $this->input->get('catId'),
            'createNumber' => $this->input->get('createNumber'),
            'keywordTypeId' => $this->input->get('keywordTypeId'),
            'keyword' => $this->input->get('keyword'),
            'shortValueId' => $this->input->get('shortValueId'),
            'workId' => $this->input->get('workId'),
            'motiveId' => $this->input->get('motiveId'),
            'partnerId' => $this->input->get('partnerId'),
            'inDate' => $this->input->get('inDate'),
            'outDate' => $this->input->get('outDate'),
            'protocolInDate' => $this->input->get('protocolInDate'),
            'protocolOutDate' => $this->input->get('protocolOutDate'),
            'closeInDate' => $this->input->get('closeInDate'),
            'closeOutDate' => $this->input->get('closeOutDate'),
            'crimeDate' => $this->input->get('crimeDate'),
            'age1' => $this->input->get('age1'),
            'age2' => $this->input->get('age2'),
            'isAgeInfinitive' => $this->input->get('isAgeInfinitive'),
            'crimeShortValueId' => $this->input->get('crimeShortValueId'),
            'short_value' => $this->input->get('shortValue'),
            'expertId' => $this->input->get('expertId'),
            'whereId' => $this->input->get('whereId'),
            'catId' => $this->input->get('catId'),
            'payment' => $this->input->get('payment'),
            'closeTypeId' => $this->input->get('closeTypeId'),
            'departmentId' => $this->input->get('departmentId'),
            'solutionId' => $this->input->get('solutionId'),
            'isMixx' => $this->input->get('isMixx'),
            'closeDate' => $this->input->get('closeDate'),
            'statusId' => $this->input->get('statusId'),
            'sex' => $this->input->get('sex'),
            'closeDescription' => $this->input->get('closeDescription')));

        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4')->setCellValue('A3', '№');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4')->setCellValue('B3', 'Журнал №');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:D3')->setCellValue('C3', 'Бүртгэсэн огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4')->setCellValue('E3', 'Бүрэлдэхүүнтэй эсэх');
        $objPHPExcel->getActiveSheet()->mergeCells('F3:F4')->setCellValue('F3', 'Үндэслэл');
        $objPHPExcel->getActiveSheet()->mergeCells('G3:G4')->setCellValue('G3', 'Эцэг/эх/-ийн нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:H4')->setCellValue('H3', 'Өөрийн нэр');
        $objPHPExcel->getActiveSheet()->mergeCells('I3:I4')->setCellValue('I3', 'Регистр');
        $objPHPExcel->getActiveSheet()->mergeCells('J3:J4')->setCellValue('J3', 'Нас');
        $objPHPExcel->getActiveSheet()->mergeCells('K3:K4')->setCellValue('K3', 'Хүйс');
        $objPHPExcel->getActiveSheet()->mergeCells('L3:L4')->setCellValue('L3', 'Хаяг');
        $objPHPExcel->getActiveSheet()->mergeCells('M3:M4')->setCellValue('M3', 'Ажил');
        $objPHPExcel->getActiveSheet()->mergeCells('N3:N4')->setCellValue('N3', 'Хэргийн төрөл');
        $objPHPExcel->getActiveSheet()->mergeCells('O3:O4')->setCellValue('O3', 'Ирүүлсэн байгууллага');
        $objPHPExcel->getActiveSheet()->mergeCells('P3:P4')->setCellValue('P3', 'Албан хаагч');
        $objPHPExcel->getActiveSheet()->mergeCells('Q3:Q4')->setCellValue('Q3', 'Болсон хэргийн товч');
        $objPHPExcel->getActiveSheet()->mergeCells('R3:R4')->setCellValue('R3', 'Шинжээч');
        $objPHPExcel->getActiveSheet()->mergeCells('S3:S4')->setCellValue('S3', 'Хаана');
        $objPHPExcel->getActiveSheet()->mergeCells('T3:T4')->setCellValue('T3', 'Тайлбар');
        $objPHPExcel->getActiveSheet()->mergeCells('U3:U4')->setCellValue('U3', 'Төлбөр');
        $objPHPExcel->getActiveSheet()->mergeCells('V3:V4')->setCellValue('V3', 'Хэргийн дугаар');
        $objPHPExcel->getActiveSheet()->mergeCells('W3:X3')->setCellValue('W3', 'Тогтоолын огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('W4', 'Эхлэх');
        $objPHPExcel->getActiveSheet()->setCellValue('X4', 'Дуусах');
        $objPHPExcel->getActiveSheet()->mergeCells('Y3:Z3')->setCellValue('Y3', 'Хаасан огноо');
        $objPHPExcel->getActiveSheet()->setCellValue('Y4', 'Хийгдсэн');
        $objPHPExcel->getActiveSheet()->setCellValue('Z4', 'Дууссан');
        $objPHPExcel->getActiveSheet()->mergeCells('AA3:AA4')->setCellValue('AA3', 'Шалтгаан');
        $objPHPExcel->getActiveSheet()->mergeCells('AB3:AB4')->setCellValue('AB3', 'Спирт илэрсэн эсэх');
        $objPHPExcel->getActiveSheet()->mergeCells('AC3:AC4')->setCellValue('AC3', 'Дүгнэлт');
        $objPHPExcel->getActiveSheet()->mergeCells('AD3:AD4')->setCellValue('AD3', 'Илгээх бичиг');


        $objPHPExcel->getActiveSheet()->getStyle('A3:AD4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:AD4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A3:AD4')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '085f35')
                    )
                )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('ffffff');

        $objPHPExcel->getActiveSheet()->getStyle('A3:AD4')->getFont()->setColor($phpColor);

        $i = 4;
        $j = 0;

        if ($data) {
            foreach ($data as $key => $row) {

                $i++;
                $j++;

                $param = json_decode($row->param);

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $j);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $row->create_number);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $row->in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $row->out_date);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $row->is_mixx);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $param->motive);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $row->lname);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $row->fname);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $row->register);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $row->age);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $row->sex);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $row->address);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $param->work);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $param->type);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $param->partner);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $row->expert_name);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $row->short_value);
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $row->expert);
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $param->where);
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $row->description);
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $row->payment);
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, '№:' . $row->protocol_number, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $row->protocol_in_date);
                $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $row->protocol_out_date);
                $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $row->begin_date);
                $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $row->end_date);
                $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i, $param->solution);
                $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i, $param->closeType);
                $objPHPExcel->getActiveSheet()->setCellValue('AC' . $i, $row->close_description);
                $objPHPExcel->getActiveSheet()->setCellValue('AD' . $i, $row->send_document);

            }
        }

        $objPHPExcel->getActiveSheet()->getStyle('A3:AD' . $i)->applyFromArray($this->styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AD' . $i)->getFont()->setName('Arial');
        $objPHPExcel->getActiveSheet()->getStyle('A1:AD' . $i)->getFont()->setSize(10);

        $objPHPExcel->getActiveSheet()->getStyle('A4:AD' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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

    public function tempUpdateData() {
        $this->nifsAnatomy->tempUpdateData_model();
    }

    public function dataUpdate() {
        echo json_encode($this->nifsAnatomy->dataUpdate_model());
    }

}
